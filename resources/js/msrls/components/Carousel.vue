<template>
  <div id="carouselExample" class="carousel slide px-3 pt-0" data-bs-ride="carousel">
    <div class="carousel-inner shadow-lg">
      <!-- Displaying the current image -->
      <div class="post-thumb">
        <div class="carousel-container">
          <div v-if="slides.length > 0" class="carousel-slide">
            <!-- Directly using :src for image source -->
            <img :src="slides[currentIndex]" class="d-block" :alt="'Image ' + (currentIndex + 1)"
              style="height: 600px; width: 100%;">

          </div>

          <!-- Navigation buttons (Previous and Next) -->
          <button @click="prevImage" class="carousel-prev"> &lt; </button>
          <button @click="nextImage" class="carousel-next"> &gt; </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const slides = ref([]);  // Holds the image data
const currentIndex = ref(0);  // Tracks the index of the current slide
const cacheDuration = 24 * 60 * 60 * 1000;  // Cache duration (1 day)

// Fetch the carousel slides
const fetchSlides = async () => {
  const now = new Date().getTime();
  const cacheDuration = 3600000;  // Example cache duration: 1 hour (in milliseconds)

  // Check if cached data exists and is valid
  const cachedSlides = localStorage.getItem('carouselSlides');
  const cachedTimestamp = localStorage.getItem('carouselSlidesTimestamp');

  if (cachedSlides && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    console.log('Using cached carousel slides from localStorage');
    slides.value = JSON.parse(cachedSlides);  // Use cached slides data
    return;
  }

  // If the cache is invalid or expired, fetch new data
  try {
    const response = await axios.get('/get_carousel');
    console.log('Fetched slides:', response.data);

    if (response.data && Array.isArray(response.data)) {
      // Build file URL based on Laravel's storage path
      slides.value = response.data.map(slide => {
        const filePath = '/storage/' + slide.image.replace('public/', '');  // Build URL
        return filePath;
      });

      // Cache the slides and timestamp in localStorage
      localStorage.setItem('carouselSlides', JSON.stringify(slides.value));
      localStorage.setItem('carouselSlidesTimestamp', now.toString());
    } else {
      console.error('Invalid data format');
    }
  } catch (error) {
    console.error('Failed to fetch slides:', error);
  }
};



// Call fetchSlides when the component is mounted
onMounted(() => {
  fetchSlides();
});
  
// Navigation functions to handle image sliding
const prevImage = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  } else {
    currentIndex.value = slides.value.length - 1; // Loop back to the last image
  }
};

const nextImage = () => {
  if (currentIndex.value < slides.value.length - 1) {
    currentIndex.value++;
  } else {
    currentIndex.value = 0; // Loop back to the first image
  }
}
</script>

<style scoped>
.carousel-container {
  position: relative;
}

.carousel-prev,
.carousel-next {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 2rem;
  background: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
}

.carousel-prev {
  left: 10px;
}

.carousel-next {
  right: 10px;
}

.carousel-slide img {
  width: 100%;
  height: auto;
  object-fit: cover;
}
</style>