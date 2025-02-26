<template>
  <div class="row">
    <div class="col-12">
      <p class="news-label">Latest News:</p>
      <div
        class="swiper-container brand-active swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
        <ul class="list-wrap">
          <!-- Fixed label -->
          <!-- Iterate over the news data and render the items -->
          <li v-for="(item, index) in newsData" :key="item.id">
            <a href="#" @click.prevent="openNews(item)" style="color: aliceblue;">
              {{ getLatestNewsTitle(item) }}
            </a>
            <span v-if="index < newsData.length - 1"> | </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, inject, provide } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
const language = inject("language");
const router = useRouter();
provide("language", language); // Provide language to all child components
const newsData = ref([]);  // Holds the news data
const loadingNews = ref(false);  // Tracks if the news data is loading
// const cacheDuration = 24 * 60 * 60 * 1000;  // Cache duration (1 day)
const cacheDuration = 60 * 1000;  // Cache duration (1 minute) in milliseconds
const props = defineProps({
  language: {
    type: String,
    required: true
  }
});

// Fetch latest news data
const fetchLatestNews = async () => {
  const now = new Date().getTime();

  // Check if cached data exists and is valid
  const cachedData = sessionStorage.getItem('latestNews');
  const cachedTimestamp = sessionStorage.getItem('latestNewsTimestamp');
  if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    console.log('Using cached latest news data from sessionStorage');
    newsData.value = JSON.parse(cachedData);  // Set cached data
    return;
  }

  // If cache is invalid or expired, fetch new data
  loadingNews.value = true;
  try {
    const response = await axios.get('/get_latest_news');
    console.log('Fetched latest news:', response.data.data);

    if (response.data && Array.isArray(response.data.data)) {
      newsData.value = response.data.data;

      // Store data in sessionStorage with a timestamp
      sessionStorage.setItem('latestNews', JSON.stringify(response.data.data));
      sessionStorage.setItem('latestNewsTimestamp', now.toString());
    } else {
      console.error('Invalid data format');
    }
  } catch (error) {
    console.error('Failed to fetch latest news:', error);
  } finally {
    loadingNews.value = false;
  }
};

// Function to get the correct title based on selected language
const getLatestNewsTitle = (newsItem) => {

  switch (props.language) {
    case 'Hindi':
      return newsItem.hindi_title || newsItem.title;  // Fallback to default title if hindi_title is missing
    case 'Khasi':
      return newsItem.khasi_title || newsItem.title;  // Fallback to default title if khasi_title is missing
    case 'English':
    default:
      return newsItem.title;
  }
};

// Method to open the news item based on its type (link or file)
const openNews = (item) => {
  if (item.page) {
    const routeUrl = router.resolve({ name: 'Page', params: { id: item.page } }).href;
    
    // Open in new tab
    window.open(routeUrl, '_blank');
  } else {
    console.error('No menu_id found for the item', item);
  }
};

// Fetch news data when the component is mounted
onMounted(() => {
  fetchLatestNews();
});
</script>

<style scoped>
/* Styling for the marquee effect */
.swiper-container {
  overflow: hidden;
  white-space: nowrap;
}
.list-wrap {
  display: flex;
  animation: marquee 20s linear infinite;
  color: aliceblue;
  white-space: nowrap;
}

.list-wrap:hover {
  animation-play-state: paused; /* Stop animation on hover */
}


.news-label {
  font-weight: bold;
  margin-left: -150px;
  /* Adjust horizontal position */
  position: relative;
  /* Necessary for transform adjustments */
  transform: translateY(40px);
  /* Move label downward */
  color: aliceblue;
}



.list-wrap li {
  display: inline-block;
  margin-right: 20px;
  white-space: nowrap;
}

@keyframes marquee {
  0% {
    transform: translateX(100%);
  }

  100% {
    transform: translateX(-100%);
  }
}
</style>