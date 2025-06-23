<template>
  <div class="containerscrollable-content">
    <!-- Form for Adding New Card -->
    <div class="card p-4 shadow-sm">
      <!-- <h4 class="mb-4 text-primary">Add New Card</h4> -->

      <form @submit.prevent="addCard" enctype="multipart/form-data">
        <div class="row">
          <!-- Left Column: Basic Info -->
          <div class="col-md-7">
            <div class="mb-3">
              <label class="form-label">Card Title <span class="text-danger">*</span></label>
              <input type="text" v-model="newCard.card_title" class="form-control" placeholder="Enter Card Title"
                required>
            </div>
            <div class="mb-3">
              <label class="form-label">Card Description</label>
              <textarea v-model="newCard.card_description" class="form-control" rows="2"
                placeholder="Enter Card Description"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Card Logo</label>
              <input type="file" @change="handleFileChange" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
              <label class="form-label">Link to another page</label>
              <select class="form-control" v-model="newCard.more_link">
                <option value="" disabled selected>Select the page menu</option>
                <option v-for="pagemenu in pagemenudata" :key="pagemenu.id" :value="pagemenu.id">
                  {{ pagemenu.menu_name }}
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">External Link</label>
              <input type="text" v-model="newCard.ex_link" class="form-control" placeholder="Enter Link (optional)">
            </div>
            <div class="mb-3">
              <label class="form-label">Card Sort Order<span class="text-danger">*</span></label>
              <input type="number" v-model="newCard.order" class="form-control" placeholder="Enter Order Number"
                required>
            </div>
          </div>

          <!-- Right Column: Advanced Info (Toggle) -->
          <div class="col-md-5">
            <p>More Languages</p>

            <div class="advanced-fields ">


              <div class="mb-3">
                <label class="form-label">Hindi Title</label>
                <input type="text" v-model="newCard.hindi_title" class="form-control" placeholder="Hindi Title">
                <textarea v-model="newCard.hindi_description" class="form-control mt-2" rows="2"
                  placeholder="Hindi Description"></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Khasi Title</label>
                <input type="text" v-model="newCard.khasi_title" class="form-control" placeholder="Khasi Title">
                <textarea v-model="newCard.khasi_description" class="form-control mt-2" rows="2"
                  placeholder="Khasi Description"></textarea>
              </div>


            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4 w-100">Add Card</button>
      </form>
    </div>

    <!-- Display Cards -->
    <div class="row mt-5">
      <div class="col-md-4" v-for="card in cards" :key="card.id">
        <div class="card card-item shadow-lg">
          <img :src="getAssetUrl(card.image)" class="card-img-top" alt="Card Logo">
          <div class="card-body">
            <h5 class="card-title">
              <a :href="card.more_link" target="_blank" class="text-decoration-none text-primary">
                {{ card.card_title }}
              </a>
            </h5>
            <p class="card-text">{{ card.card_description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps,onMounted} from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
const pagemenudata = ref([]); // Store fetched categories
const props = defineProps({
  menu: String,
  section: Number,
});
const toastr = useToastr();

const newCard = ref({
  card_title: '',
  card_description: '',
  image: null,
  more_link: '',
  ex_link: '',
  order: '',
  hindi_title: '',
  khasi_title: '',
  other_title: '',
  hindi_description: '',
  khasi_description: '',
  other_description: ''
});

const handleFileChange = (event) => {
  newCard.value.image = event.target.files[0];
};

const addCard = () => {
  const formData = new FormData();
  for (const key in newCard.value) {
    formData.append(key, newCard.value[key]);
  }
  formData.append("menu", props.menu);
  formData.append("page_section", props.section.page_section_id); 
  try {
    axios.post('/api/save_card', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
      .then(response => {
        toastr.success(response.data.message);
      })
      .catch(error => {
        toastr.error('An error occurred while saving the card.');
      });

    newCard.value = {
      card_title: '',
      card_description: '',
      image: null,
      more_link: '',
      ex_link: '',
      order: '',
      hindi_title: '',
      khasi_title: '',
      other_title: '',
      hindi_description: '',
      khasi_description: '',
      other_description: ''
    };
  } catch (error) {
    toastr.error('Error adding card.');
  }
};

const getAllPageMenu = async () => {
    try {
        const response = await axios.get('/get_allpagemenu');
        pagemenudata.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error.response || error);
        toastr.error("Failed to load categories.");
    }
};

onMounted(()=>{
  getAllPageMenu();
})
</script>

<style scoped>
.card-img-top {
  max-height: 150px;
  object-fit: cover;
  border-radius: 5px;
}

.card-item {
  border-radius: 10px;
  overflow: hidden;
  transition: transform 0.3s ease;
}

.card-item:hover {
  transform: scale(1.05);
}

.card-body {
  padding: 1.5rem;
}

.scrollable-content {
  max-height: 600px;
  overflow: auto;
}

.advanced-fields {
  background-color: #f9f9f9;
  padding: 1.5rem;
  border-radius: 10px;
}

.advanced-fields input,
.advanced-fields textarea {
  background-color: #f1f1f1;
}

button[type="submit"] {
  font-weight: bold;
  padding: 12px;
  background-color: #007bff;
  color: white;
  border: none;
  transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
  background-color: #3790f0;
}

button[type="button"]:focus {
  outline: none;
}

.form-label {
  font-weight: 600;
  color: #555;
}

button {
  transition: transform 0.3s ease;
}

button:hover {
  transform: scale(1.05);
}

.card-title a {
  font-weight: bold;
}

.card-item .card-body p {
  color: #666;
  font-size: 0.9rem;
}
</style>
