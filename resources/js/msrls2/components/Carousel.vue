<template>
  
<section class="slider__area">
  <div class="slider-container">
    <div
      class="slider-wrapper"
      :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
    >
    
      <div v-for="(slide, index) in slides" :key="index" class="slider-item">
        <div class="card slider-card">
          <div class="overlay">
            <img :src="slide" alt="Slide Image" class="slider-image" />

            <h2
              v-if="index === 0"
              class="typewriter-text"
            >
            With MSRLS, Together for Sustainable Rural Growth
            </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


    <!-- <div class="box-button-slider-bottom">
      <button @click="prevImage" class="slider-button prev">❮</button>
      <button @click="nextImage" class="slider-button next">❯</button>
    </div> -->


  <!-- <section class="slider__area" v-if="slides.length">
    <div class="slider__wrapper">
      <div
        v-for="(slide, index) in slides"
        :key="index"
        class="slider__slide"
        :class="{ active: currentIndex === index }"
      >
        <img :src="slide" alt="Slide" class="slider__img" />
        <div class="slider__content">
          <h2 class="title text-white">Transforming Dreams into Financial Reality</h2>
          <p class="text-white">
            Apexa helps you to convert your data into a strategic asset and get business insights.
          </p>
          <a href="/page/2" class="btn">Learn More</a>
        </div>
      </div>
    </div>
  </section> -->
</template>


<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const slides = ref([]); // Holds the image data
const currentIndex = ref(0); // Tracks the index of the current slide
// const cacheDuration = 24 * 60 * 60 * 1000; // Cache duration (1 day)
const cacheDuration = 10 * 60 * 1000; // Cache duration (10 minute)


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
    debugger;
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
  slideInterval = setInterval(nextImage, 10000);
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



.slider-image {
  width: 100%;
  display: block;
  filter: brightness(60%);
  height: 800px; /* or any height you prefer */
  object-fit: cover; /* keeps image proportions and fills the container */
}

.overlay {
  position: relative;
}

/* Typewriter Title Styling */
.typewriter-text {
  position: absolute;
  top: 30%; /* A little lower than top */
  left: 75%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 2rem;
  font-family: 'Baloo 2', cursive;
  font-weight: bold;
  white-space: nowrap;
  overflow: hidden;
  border-right: 2px solid white;
  width: 0;
  animation:
    typing 4s steps(40, end) forwards,
    blink-caret 0.75s step-end infinite;
  z-index: 2;
}

/* Keyframes for typewriter animation */
@keyframes typing {
  from { width: 0 }
  to { width: 100% }
}

@keyframes blink-caret {
  from, to { border-color: transparent }
  50% { border-color: white }
}


</style>