<template>

  <!-- <section class="slider__area">
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
</section> -->
  <section class="slider__area mt-n2">
    <div class="swiper-container slider_baner__active">
      <div class="swiper-wrapper" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
        <div v-for="(slide, index) in slides" :key="index" class="swiper-slide">
          <div class="banner__area-three banner__bg-five" :style="`background-image: url('${slide}')`"
            style="opacity: 0.85;">
            <div class="container">
              <div class="row">
                <div class="col-xl-7 col-lg-6">
                  <div class="banner__content-three home-9">
                    <h2 class="title" data-aos="fade-up" data-aos-delay="300">
                      With MSRLS, Together for
                      <span id="typewriter">{{ displayText }}</span>
                    </h2>
                    <router-link :to="{ name: 'Page', params: { id: 3 }, query: { page_name: 'About us ' } }"
                      class="btn"
                      data-aos="fade-up" data-aos-delay="600">
                      Learn More
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="box-button-slider-bottom home-9 d-none d-lg-block">
      <div class="container">
        <div class="testimonial__nav-four">
          <div class="testimonial-two-button-prev button-swiper-prev" tabindex="0" role="button" @click="prevImage">
            <i class="fa fa-chevron-right"></i>
          </div>
          <div class="testimonial-two-button-next button-swiper-next" tabindex="0" role="button" @click="nextImage">
            <i class="fa fa-chevron-right"></i>
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
import { ref, onMounted, onUnmounted, defineEmits } from 'vue';
import axios from 'axios';
const emit = defineEmits(['loaded']);

const fullText = "Sustainable Rural Growth.";
const displayText = ref("");
let index = 0;

const slides = ref([]);        // Array of image URLs for carousel
const currentIndex = ref(0);   // Current slide index
let slideInterval;

const CACHE_EXPIRY_MS = 10 * 60 * 20000; // 20 minutes
const CACHE_KEY_TIMESTAMP = 'carouselCacheTimestamp';

// Typewriter effect for the text
const typeWriter = () => {
  if (index < fullText.length) {
    displayText.value += fullText.charAt(index);
    index++;
    setTimeout(typeWriter, 100);
  }
};

// IndexedDB helpers for caching carousel slides

function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('CarouselDB', 1);
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
    request.onupgradeneeded = () => {
      const db = request.result;
      if (!db.objectStoreNames.contains('slides')) {
        db.createObjectStore('slides', { keyPath: 'id', autoIncrement: true });
      }
    };
  });
}

async function saveSlides(base64Slides) {
  const db = await openDB();
  const tx = db.transaction('slides', 'readwrite');
  const store = tx.objectStore('slides');

  await store.clear(); // Clear old cache before saving new

  base64Slides.forEach(slide => {
    store.add({ base64: slide });
  });

  return new Promise((resolve, reject) => {
    tx.oncomplete = () => resolve();
    tx.onerror = () => reject(tx.error);
  });
}

async function getSlides() {
  const db = await openDB();
  const tx = db.transaction('slides', 'readonly');
  const store = tx.objectStore('slides');

  return new Promise((resolve, reject) => {
    const slides = [];
    const cursorRequest = store.openCursor();
    cursorRequest.onerror = () => reject(cursorRequest.error);
    cursorRequest.onsuccess = () => {
      const cursor = cursorRequest.result;
      if (cursor) {
        slides.push(cursor.value.base64);
        cursor.continue();
      } else {
        resolve(slides);
      }
    };
  });
}

function isCacheValid() {
  const timestamp = localStorage.getItem(CACHE_KEY_TIMESTAMP);
  if (!timestamp) return false;
  const age = Date.now() - Number(timestamp);
  return age < CACHE_EXPIRY_MS;
}

// Fetch slides from backend and cache in IndexedDB
async function fetchSlides() {
  try {
    const response = await axios.get('/get_carousel', { params: { flag: 'A' } });

    const base64Slides = response.data; // array of base64 strings

    // Save to IndexedDB cache
    await saveSlides(base64Slides);

    // Update cache timestamp
    localStorage.setItem(CACHE_KEY_TIMESTAMP, Date.now().toString());

    // Decode base64 JSON, extract image URLs
    const slideData = base64Slides
      .map(base64Str => {
        const decoded = JSON.parse(atob(base64Str));
        return decoded.data.map(item => '/storage/' + item.image.replace('public/', ''));
      })
      .flat();

    slides.value = slideData;
  } catch (error) {
    console.error('Fetch failed, loading cached slides:', error);

    // Load from IndexedDB cache if fetch fails
    const cachedBase64Slides = await getSlides();

    if (cachedBase64Slides.length) {
      const slideData = cachedBase64Slides
        .map(base64Str => {
          const decoded = JSON.parse(atob(base64Str));
          return decoded.data.map(item => '/storage/' + item.image.replace('public/', ''));
        })
        .flat();
      slides.value = slideData;
    }
  }
}

async function loadSlides() {
  if (isCacheValid()) {
    // Load from IndexedDB cache
    const cachedBase64Slides = await getSlides();

    if (cachedBase64Slides.length) {
      const slideData = cachedBase64Slides
        .map(base64Str => {
          const decoded = JSON.parse(atob(base64Str));
          return decoded.data.map(item => '/storage/' + item.image.replace('public/', ''));
        })
        .flat();
      slides.value = slideData;
      return;
    }
  }

  // Cache invalid or empty, fetch fresh slides
  await fetchSlides();
}

// Carousel navigation
const prevImage = () => {
  currentIndex.value =
    currentIndex.value > 0 ? currentIndex.value - 1 : slides.value.length - 1;
};

const nextImage = () => {
  currentIndex.value =
    currentIndex.value < slides.value.length - 1 ? currentIndex.value + 1 : 0;
};

// Setup on component mount
onMounted(async () => {
  await loadSlides();
  typeWriter();
  emit('loaded');     // Notify parent that loading is done
  console.log("Carousel loaded successfully, ready to call other APIs");
  slideInterval = setInterval(nextImage, 5000);
});

// Cleanup on unmount
onUnmounted(() => {
  clearInterval(slideInterval);
});
</script>




<style scoped>
#typewriter::after {
  content: '|';
  animation: blink 1s step-end infinite;
}

@keyframes blink {

  from,
  to {
    opacity: 1
  }

  50% {
    opacity: 0
  }
}

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
  height: 800px;
  /* or any height you prefer */
  object-fit: cover;
  /* keeps image proportions and fills the container */
}

.overlay {
  position: relative;
}
</style>