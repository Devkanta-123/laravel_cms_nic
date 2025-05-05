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
  <section class="slider__area">
   <div class="swiper-container slider_baner__active">
  <div class="swiper-wrapper" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
    <div v-for="(slide, index) in slides" :key="index" class="swiper-slide">
      <div class="banner__area-three banner__bg-five" 
           :style="`background-image: url('${slide}')`" 
           style="opacity: 0.85;">
        <div class="container">
          <div class="row">
            <div class="col-xl-7 col-lg-6">
              <div class="banner__content-three home-9">
                <h2 class="title" data-aos="fade-up" data-aos-delay="300" >
                  With MSRLS, Together for 
                  <span id="typewriter">{{ displayText }}</span>
                </h2>
                <a href="about" class="btn" data-aos="fade-up" data-aos-delay="600">
                  Learn More
                </a>
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
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';
import { loadOrCacheImage } from '../services/carousalCache.js';
const fullText = "Sustainable Rural Growth."
const displayText = ref("")
let index = 0
let slideInterval;
const slides = ref([]); // Holds the image data
const currentIndex = ref(0); // Tracks the index of the current slide
// const cacheDuration = 24 * 60 * 60 * 1000; // Cache duration (1 day)
const cacheDuration = 10 * 60 * 1000; // Cache duration (10 minute)

const typeWriter = () => {
  if (index < fullText.length) {
    displayText.value += fullText.charAt(index)
    index++
    setTimeout(typeWriter, 100)
  }
}
// Fetch the carousel slides
const fetchSlides = async () => {
  try {
    const response = await axios.get('/get_carousel', {
      params: { flag: 'A' }
    });
    console.log('Fetched slides:', response.data);

    if (response.data && Array.isArray(response.data)) {
      const slideData = await Promise.all(response.data.map(async (slide) => {
        const filePath = '/storage/' + slide.image.replace('public/', '');
        const imageBase64 = await loadOrCacheImage(filePath);
        return { filePath, imageBase64 };
      }));

      slides.value = slideData.map(slide => slide.imageBase64);
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

onMounted(async () => {
  await fetchSlides();
  typeWriter()
  slideInterval = setInterval(nextImage, 5000);

});
onUnmounted(() => {
  // Clear interval when the component is unmounted
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