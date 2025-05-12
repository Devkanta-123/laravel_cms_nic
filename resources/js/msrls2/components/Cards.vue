<template>
  <div class="container">
    <div class="row gutter-24">
      <div v-for="(card, index) in cards" :key="card.id" class="col-lg-4 col-md-6">
        <div class="project__item-two">
          <div class="project__thumb-two">
            <img :src="card.image" alt="Card Image" />
          </div>
          <div class="project__content-two">
            <h2 class="title">
              <router-link :to="`/page/query?page_name=${card.card_title}&cardid=${card.more_link}`">
                {{ getCardsTitle(card) }}
              </router-link>
            </h2>
            <div class="link-arrow link-arrow-two">
              <router-link :to="`/page/query?page_name=${card.card_title}&cardid=${card.more_link}`">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 15" fill="none">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.6293 3.27956C17.7117 2.80339 17.4427 2.34761 17.0096 2.17811C16.9477 2.15384 16.8824 2.13551 16.8144 2.12375L6.96087 0.419136C6.4166 0.325033 5.89918 0.689841 5.80497 1.23409C5.71085 1.77828 6.0757 2.29576 6.61988 2.38991L14.0947 3.68293L1.3658 12.6573C0.914426 12.9756 0.806485 13.5994 1.12473 14.0508C1.44298 14.5022 2.06688 14.6101 2.51825 14.2919L15.2471 5.31752L13.954 12.7923C13.8599 13.3365 14.2248 13.854 14.7689 13.9481C15.3132 14.0422 15.8306 13.6774 15.9248 13.1332L17.6293 3.27956Z"
                    fill="currentColor" />
                </svg>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
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
    const response = await axios.get('/get_cards', {
      params: { flag: 'A' }
    });
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
.services-content .btn::after {
  content: none !important;
}


.services-thumb img {
  transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
  /* Smoother animation */
}

.services-thumb:hover img {
  transform: scale(1.15);
  /* Slightly more zoom for better effect */
}
</style>