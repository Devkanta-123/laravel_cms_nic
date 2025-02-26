<template>
  <div class="container position-relative z-1">
    <div class="services-item-wrap">
      <div class="row justify-content-center">
        <div v-for="(card, index) in cards" :key="card.id" class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
          <div class="services-item shine-animate-item">
            <div class="services-thumb">
              <router-link :to="`/page/${card.page_section_id}?cardsid=${card.menu_id}`" class="shine-animate">
                <img :src="card.image" alt="cards">
              </router-link>
            </div>
            <div class="services-content">
              <h4 class="title">
                <router-link :to="`/page/${card.page_section_id}?cardid=${card.menu_id}`">
                  {{ getCardsTitle(card) }}
                </router-link>
              </h4>
              <router-link :to="`/page/${card.page_section_id}?cardid=${card.menu_id}`" class="btn">
                Read More
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="services-bottom-content">
        <router-link to="/services" class="btn">See All Services</router-link>
      </div> -->
  </div>
</template>



<script setup>
import { ref, onMounted, inject, provide } from 'vue';
import axios from 'axios';

const cards = ref([]);  // Holds the cards data
const loadingCards = ref(false);  // Loading state for cards
// const cacheDuration = 24 * 60 * 60 * 1000;  // Cache duration (1 day)
const cacheDuration = 10 * 60 * 1000; // Cache duration (10 minute)
const language = inject("language");
provide("language", language); // Provide language to all child components
const props = defineProps({
  language: {
    type: String,
    required: true
  }
});

// Fetch cards data from API with caching
const fetchCards = async () => {
  debugger;
  const now = new Date().getTime();

  // Check if cached data exists and is valid
  const cachedData = localStorage.getItem("cards");
  const cachedTimestamp = localStorage.getItem("cardsTimestamp");

  if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    console.log("Using cached cards from localStorage");
    cards.value = JSON.parse(cachedData); // Set cached data
    return;
  }

  // If cache is invalid or expired, fetch new data from API
  loadingCards.value = true;
  try {
    const response = await axios.get("/get_cards"); // Replace with your correct API endpoint
    console.log("Fetched Cards:", response.data.data);

    if (response.data && Array.isArray(response.data.data)) {
      // Process the fetched data and map the image URLs
      const cardData = await Promise.all(
        response.data.data.map(async (card) => {
          const filePath = "/storage/" + card.image.replace("public/", ""); // Correct the image URL path

          // Fetch image as a Base64 encoded string
          try {
            const imageResponse = await fetch(filePath);
            const imageBlob = await imageResponse.blob();
            const imageBase64 = await new Promise((resolve, reject) => {
              const reader = new FileReader();
              reader.onloadend = () => resolve(reader.result);
              reader.onerror = reject;
              reader.readAsDataURL(imageBlob);
            });

            return {
              ...card,
              image: imageBase64, // Store Base64 encoded image
            };
          } catch (imageError) {
            console.error(`Failed to fetch image for card ${card.id}:`, imageError);
            return {
              ...card,
              image: filePath, // Fallback to URL if Base64 conversion fails
            };
          }
        })
      );

      // Sort cards by 'order' field in ascending order
      cards.value = cardData.sort((a, b) => a.order - b.order);

      // Store the new data in localStorage with a timestamp
      localStorage.setItem("cards", JSON.stringify(cards.value));
      localStorage.setItem("cardsTimestamp", now.toString());
    } else {
      console.error("Invalid data format");
    }
  } catch (error) {
    console.error("Failed to fetch cards:", error);
  } finally {
    loadingCards.value = false;
  }
};


const getCardsTitle = (newsItem) => {
  switch (props.language) {
    case 'Hindi':
      return newsItem.hindi_title || newsItem.english_title;  // Fallback to default title if hindi_title is missing
    case 'Khasi':
      return newsItem.khasi_title || newsItem.english_title;  // Fallback to default title if khasi_title is missing
    case 'English':
    default:
      return newsItem.card_title;
  }
};

// Fetch cards data when the component is mounted
onMounted(() => {
  fetchCards();  // Call the fetchCards function when the component is mounted
});
</script>

<style scoped>
/* Add styles specific to cards here */
.services-bottom.btn::after,
.services-content .btn::after {
  content: none !important;
}
</style>