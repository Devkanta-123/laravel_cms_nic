<template>
  <!-- <div class="row">
    <div class="col-12">
      <p class="news-label">Latest News:</p>
      <div
        class="swiper-container brand-active swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
        <ul class="list-wrap">
        
          <li v-for="(item, index) in newsData" :key="item.id">
            <a href="#" @click.prevent="openNews(item)" style="color: #014E4E;">
              {{ getLatestNewsTitle(item) }}
            </a>
            <span v-if="index < newsData.length - 1"> | </span>
          </li>
        </ul>
      </div>
    </div>
  </div> -->
  <div class="bg-img position-absolute top-0 bottom-0 start-0 end-0 z-0 "></div>
  <div class="container position-relative z-1">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-8">
        <div class="section-title text-center mb-40 tg-heading-subheading animation-style3">
          <h1 class="sub-title">LATEST NEWS</h1>
        </div>
      </div>
    </div>

    <div class="position-relative">
      <div class="row justify-content-center">
        <div v-for="(item, index) in paginatedNews" :key="item.id" class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
          <div class="services-item shine-animate-item">
            <div class="services-thumb">
              <a href="#" class="shine-animate" target="_blank">
                <img :src="logo" alt="News Image" />
              </a>
            </div>
            <div class="services-content">
              <div class="icon">
                <i class="fa-solid fa-newspaper"></i>
              </div>
              <h4 class="services__top-title">
                <a href="#" @click.prevent="openNews(item)">
                  {{ getLatestNewsTitle(item) }}
                </a>
              </h4>
            </div>
          </div>
        </div>
      </div>
      <div class="project__nav-wrap">
        <div class="project-button-prev" tabindex="0" role="button" aria-label="Previous slide" @click="prevSlide"
          :class="{ 'disabled': currentIndex === 0 }">
          <i class="fa fa-chevron-right"></i>
        </div>
        <div class="project-button-next" tabindex="0" role="button" aria-label="Next slide" @click="nextSlide"
          :class="{ 'disabled': currentIndex + itemsPerPage >= newsData.length }">
          <i class="fa fa-chevron-right"></i>
        </div>
      </div>
      <router-link :to="{ name: 'Page', params: { id: 73 }, query: { page_name: 'Archive Data' } }" class="btn" data-aos="fade-up"
        data-aos-delay="600">
        See Archive data
      </router-link>

    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, computed, inject, provide } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import logo from '@/assets/images/latest_news.png'
const language = inject("language");
const router = useRouter();
const currentIndex = ref(0);
const itemsPerPage = 4;
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

const paginatedNews = computed(() => {
  return newsData.value.slice(currentIndex.value, currentIndex.value + itemsPerPage);
});

const fetchLatestNews = async () => {
  const now = new Date().getTime();
  const cachedData = sessionStorage.getItem('latestNews');
  const cachedTimestamp = sessionStorage.getItem('latestNewsTimestamp');

  if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    console.log('Using cached latest news data from sessionStorage');
    newsData.value = JSON.parse(cachedData);
    return;
  }

  loadingNews.value = true;
  try {
    // const response = await axios.get('/get_latest_news');
    const response = await axios.get('/get_latest_news', {
      params: { flag: 'A' }
    });
    if (response.data && Array.isArray(response.data)) {
      newsData.value = response.data;
      sessionStorage.setItem('latestNews', JSON.stringify(response.data));
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

const nextSlide = () => {
  if (currentIndex.value + itemsPerPage < newsData.value.length) {
    currentIndex.value += itemsPerPage;
  }
};

const prevSlide = () => {
  if (currentIndex.value - itemsPerPage >= 0) {
    currentIndex.value -= itemsPerPage;
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
  if (item.link) {
    window.open(item.link, '_blank');
  } else if (item.file) {
    const filePath = '/storage/' + item.file.replace('public/', '');
    window.open(filePath, '_blank');
  }
}

// Fetch news data when the component is mounted
onMounted(() => {
  fetchLatestNews();
});
</script>

<style scoped></style>