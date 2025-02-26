<template>
  <div class="row gy-4 justify-content-center">
    <!-- Loop through the cards and display each one -->
    <div v-for="(card, index) in cards" :key="card.id" class="col-lg-4 col-md-4">
      <div class="event-item wow fade-in-bottom" :data-wow-delay="`${(index + 1) * 100}ms`">
        <div class="event-thumb">
          <!-- Dynamically bind image with the base URL from Laravel's public storage -->
          <img :src="card.image" alt="event">
          <div class="date-wrap">
            <p class="date">{{ card.card_title }}</p>
          </div>
        </div>
        <div class="event-content">
          <a :href="card.page_section_id" class="ed-primary-btn">View More</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const cards = ref([]);  // Holds the cards data
const loadingCards = ref(false);  // Loading state for cards
const cacheDuration = 24 * 60 * 60 * 1000;  // Cache duration (1 day)

// Fetch cards data from API with caching
const fetchCards = async () => {
  const now = new Date().getTime();

  // Check if cached data exists and is valid
  const cachedData = localStorage.getItem('cards');
  const cachedTimestamp = localStorage.getItem('cardsTimestamp');

  if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    console.log('Using cached cards from localStorage');
    cards.value = JSON.parse(cachedData);  // Set cached data
    return;
  }

  // If cache is invalid or expired, fetch new data from API
  loadingCards.value = true;
  try {
    const response = await axios.get('/get_cards');  // Replace with your correct API endpoint
    console.log('Fetched Cards:', response.data.data);

    if (response.data && Array.isArray(response.data.data)) {
      // Process the fetched data and map the image URLs
      cards.value = response.data.data
        .map(card => ({
          ...card,
          image: '/storage/' + card.image.replace('public/', '')  // Correct the image URL path
        }))
        .sort((a, b) => a.order - b.order);  // Sort cards by 'order' field in ascending order

      // Store the new data in localStorage with a timestamp
      localStorage.setItem('cards', JSON.stringify(cards.value));
      localStorage.setItem('cardsTimestamp', now.toString());
    } else {
      console.error('Invalid data format');
    }
  } catch (error) {
    console.error('Failed to fetch cards:', error);
  } finally {
    loadingCards.value = false;
  }
};

// Fetch cards data when the component is mounted
onMounted(() => {
  fetchCards();  // Call the fetchCards function when the component is mounted
});
</script>

<style scoped>
/* Add styles specific to cards here */
</style>