<template>
  <div class="container mt-4 scrollable-content">
    <!-- Form for Adding New Card -->
    <div class="mb-4 ">
      <form @submit.prevent="addCard" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
          <input type="text" id="title" v-model="newCard.card_title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <input type="text" id="description" v-model="newCard.card_description" class="form-control">
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Logo</label>
          <input type="file" id="image" @change="handleFileChange" class="form-control">
        </div>
        <div class="mb-3">
          <label for="more_link" class="form-label">More Link</label>
          <input type="text" id="more_link" v-model="newCard.more_link" class="form-control">
        </div>
        <div class="mb-3">
          <label for="order" class="form-label">Order</label>
          <input type="text" id="order" v-model="newCard.order" class="form-control" required>
        </div>
        <!-- Additional localized fields -->
        <div class="mb-3">
          <label for="hindi_title" class="form-label">Hindi Title</label>
          <input type="text" id="hindi_title" v-model="newCard.hindi_title" class="form-control">
        </div>
        <div class="mb-3">
          <label for="khasi_title" class="form-label">Khasi Title</label>
          <input type="text" id="khasi_title" v-model="newCard.khasi_title" class="form-control">
        </div>
        <div class="mb-3">
          <label for="other_title" class="form-label">Other Title</label>
          <input type="text" id="other_title" v-model="newCard.other_title" class="form-control">
        </div>
        <!-- Additional description fields -->
        <div class="mb-3">
          <label for="hindi_description" class="form-label">Hindi Description</label>
          <input type="text" id="hindi_description" v-model="newCard.hindi_description" class="form-control">
        </div>
        <div class="mb-3">
          <label for="khasi_description" class="form-label">Khasi Description</label>
          <input type="text" id="khasi_description" v-model="newCard.khasi_description" class="form-control">
        </div>
        <div class="mb-3">
          <label for="other_description" class="form-label">Other Description</label>
          <input type="text" id="other_description" v-model="newCard.other_description" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Card</button>
      </form>
    </div>

    <!-- Display Cards -->
    <div class="row">
      <div class="col-md-4" v-for="card in cards" :key="card.id">
        <div class="card mb-4">
          <img :src="getAssetUrl(card.image)" class="card-img-top" alt="Logo">
          <div class="card-body">
            <h5 class="card-title">{{ card.card_title }}</h5>
            <p class="card-text">{{ card.card_description }}</p>
            <a :href="card.more_link" class="btn btn-primary" target="_blank">Go to Link</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const newCard = ref({
  card_title: '',
  card_description: '',
  image: null,
  more_link: '',
  order: '',
  hindi_title: '',
  khasi_title: '',
  other_title: '',
  hindi_description: '',
  khasi_description: '',
  other_description: ''
});

// const cards = ref([]);

const fetchCards = async () => {
  try {
    const response = await axios.get('/api/get_cards'); // Replace with your API endpoint
    items.value = response.data.data; // Update with the correct data path
  } catch (error) {
    console.error('Error fetching cards:', error);
  }
};

const handleFileChange = (event) => {
  newCard.value.image = event.target.files[0]; // Store the selected file
};

const addCard = async () => {
  const formData = new FormData();
  for (const key in newCard.value) {
    formData.append(key, newCard.value[key]);
  }

  try {
    axios.post('/api/save_card', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
      .then(response => {
        console.log('Card saved successfully:', response.data.message);
        toastr.success(response.data.message);
      })
      .catch(error => {
        console.error('Error saving card:', error);
        toastr.error('An error occurred while saving the card.');
      });
    items.value.push(response.data); // Add the new card to the list
    newCard.value = {
      card_title: '',
      card_description: '',
      image: null,
      more_link: '',
      order: '',
      hindi_title: '',
      khasi_title: '',
      other_title: '',
      hindi_description: '',
      khasi_description: '',
      other_description: ''
    }; // Clear the form
  } catch (error) {
    console.error('Error adding card:', error);
  }
};

onMounted(() => {
  // fetchCards(); 
});
</script>

<style scoped>
.card-img-top {
  max-height: 150px;
  object-fit: cover;
}

.scrollable-content {
    max-height: 500px;
    overflow: auto;
  }
</style>
