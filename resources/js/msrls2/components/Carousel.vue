<template>
  <section class="slider__area">
    <div class="slider-container">
      <div class="slider-wrapper" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
        <div v-for="(slide, index) in slides" :key="index" class="slider-item">
          <div class="card">
            <img :src="slide" alt="Slide Image" />
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation buttons -->
    <div class="box-button-slider-bottom">
      <button @click="prevImage" class="slider-button prev">❮</button>
      <button @click="nextImage" class="slider-button next">❯</button>
    </div>
  </section>
</template>


<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const slides = ref([]); // Holds the image data
const currentIndex = ref(0); // Tracks the index of the current slide
// const cacheDuration = 24 * 60 * 60 * 1000; // Cache duration (1 day)
const cacheDuration = 10 * 60 * 1000; // Cache duration (10 minute)
const gap = ref(3); // Define gap


// Fetch the carousel slides
const fetchSlides = async () => {
  const now = new Date().getTime();

  // Check if cached data exists and is valid
  const cachedSlides = localStorage.getItem('carouselSlides');
  const cachedTimestamp = localStorage.getItem('carouselSlidesTimestamp');

  if (cachedSlides && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    console.log('Using cached carousel slides from localStorage');
    slides.value = JSON.parse(cachedSlides); // Use cached slides data
    return;
  }

  // If the cache is invalid or expired, fetch new data
  try {
    const response = await axios.get('/get_carousel');
    console.log('Fetched slides:', response.data);

    if (response.data && Array.isArray(response.data)) {
      const slideData = await Promise.all(response.data.map(async (slide) => {
        const filePath = '/storage/' + slide.image.replace('public/', ''); // Build URL

        // Fetch image as a base64 encoded string
        const imageResponse = await fetch(filePath);
        const imageBlob = await imageResponse.blob();
        const imageBase64 = await new Promise((resolve, reject) => {
          const reader = new FileReader();
          reader.onloadend = () => resolve(reader.result);
          reader.onerror = reject;
          reader.readAsDataURL(imageBlob);
        });

        return { filePath, imageBase64 };
      }));

      slides.value = slideData.map(slide => slide.imageBase64);

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

// Navigation functions to handle image sliding
const prevImage = () => {
  currentIndex.value =
    currentIndex.value > 0 ? currentIndex.value - 1 : slides.value.length - 1;
};

const nextImage = () => {
  currentIndex.value =
    currentIndex.value < slides.value.length - 1 ? currentIndex.value + 1 : 0;
};

// Auto slide change every 5 seconds
let slideInterval;

onMounted(() => {
  fetchSlides();

  // Set interval to automatically change the slide every 5 seconds
  slideInterval = setInterval(nextImage, 5000);
});

onUnmounted(() => {
  // Clear interval when the component is unmounted
  clearInterval(slideInterval);
});
</script>



<style scoped>
/* Slider Container */
.slider__area {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  overflow: hidden;
  position: relative;
}

/* Main Slider Container */
.slider-container {
  width: 90%;
  height: 500px;
  /* Adjust height as needed */
  overflow: hidden;
  position: relative;
}

/* Slider Wrapper */
.slider-wrapper {
  display: flex;
  transition: transform 0.5s ease-in-out;
  width: 100%;
}

/* Individual Slide */
.slider-item {
  flex: 0 0 100%;
  /* Full width for one image at a time */
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Card Styling */
.card {
  width: 100%;
  height: 100%;
  overflow: hidden;
  border-radius: 12px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  /* Ensures image covers the full card */
}

/* Navigation Buttons */
.box-button-slider-bottom {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.slider-button {
  background: #014e4e;
  color: white;
  padding: 12px 18px;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  transition: 0.3s;
  font-size: 22px;
  margin: 0 10px;
}

.slider-button:hover {
  background: #012f2f;
}
</style>