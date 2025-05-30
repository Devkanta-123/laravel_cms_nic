<template>
  <div class="course-sidebar sticky-widget">
    <h4 class="sidebar-title">Latest News</h4>
    <div class="news-marquee-wrapper">
      <div class="news-marquee">
        <ul class="course-sidebar-list">
          <!-- Iterate over the news data and render the items -->
          <li v-for="(item, index) in newsData" :key="item.id">
            <i :class="item.type === 'link' ? 'fa-solid fa-globe' : 'fa-solid fa-file-pdf'"></i>
            <a href="#" @click.prevent="openNews(item)">
              {{ getLatestNewsTitle(item) }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const newsData = ref([]);  // Holds the news data
const loadingNews = ref(false);  // Tracks if the news data is loading
// const cacheDuration = 24 * 60 * 60 * 1000;  // Cache duration (1 day)
const cacheDuration = 1 * 60 * 1000;  // Cache duration (1 minute) in milliseconds
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
  if (item.type === 'link' && item.link) {
    window.open(item.link, '_blank');  // Open link in a new tab
  } else if (item.type === 'file' && item.file) {
    const filePath = '/storage/' + item.file.replace('public/', '');  // Build file URL based on Laravel's storage path
    window.open(filePath, '_blank');  // Open the file in a new tab
  } else {
    console.error('Invalid news item', item);
  }
};

// Fetch news data when the component is mounted
onMounted(() => {
  fetchLatestNews();
});
</script>

<style scoped>
/* Add your existing styles here */
.course-sidebar-list {
  list-style-type: none;
  padding: 0;
}

.course-sidebar-list li {
  margin-bottom: 10px;
  display: block;
}

.course-sidebar-list i {
  margin-right: 10px;
}

.news-marquee-wrapper {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.news-marquee {
  position: absolute;
  bottom: 0;
  animation: scrollNewsUp 10s linear infinite;
}

/* Pause the animation on hover */
.news-marquee:hover {
  animation-play-state: paused;
  /* Pause the animation when the marquee container is hovered */
}

@keyframes scrollNewsUp {
  0% {
    transform: translateY(100%);
  }

  100% {
    transform: translateY(-100%);
  }
}

.course-sidebar-list a {
  color: #333;
  text-decoration: none;
  font-weight: bold;
}

.fa-solid.fa-file-pdf {
  color: #e80c2d;
  /* Change the icon color to red (adjust the color as needed) */
}
</style>
