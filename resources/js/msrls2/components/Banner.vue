<template>
  <div class="container">
    <div class="banner-container">
      <!-- Banner Track -->
      <div class="banner-track" :style="{ transform: `translateX(${currentTranslate}px)` }"
        @touchstart="handleTouchStart" @touchmove="handleTouchMove" @touchend="handleTouchEnd">
        <!-- Dynamically generate banners -->
        <div v-for="(banner, index) in banners" :key="banner.id" class="banner-item">
          <img :src="banner.image" :alt="`Banner ${index + 1}`" />
        </div>
      </div>

      <!-- Navigation Buttons -->
      <button class="prev-btn" @click="prevSlide" :disabled="currentIndex === 0">
        &lt;
      </button>
      <button class="next-btn" @click="nextSlide" :disabled="currentIndex === banners.length - 1">
        &gt;
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const banners = ref([]); // Holds the banner data
const currentTranslate = ref(0); // Current translation value
const startX = ref(0); // Starting X position of touch
const currentIndex = ref(0); // Current index of the banner
const bannerWidth = 300; // Width of each banner
const loadingBanners = ref(false); // Loading state for banners
const cacheDuration = 10 * 60 * 1000; // Cache duration (10 minute)
let autoSlideInterval = null;

// Fetch banners from API
const fetchBanners = async () => {
  const now = new Date().getTime();
  const cachedData = localStorage.getItem("banners");
  const cachedTimestamp = localStorage.getItem("bannersTimestamp");

  if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    banners.value = JSON.parse(cachedData);
    return;
  }

  loadingBanners.value = true;
  try {
    const response = await axios.get("/get_banner");
    if (response.data && Array.isArray(response.data)) {
      banners.value = response.data.map((banner) => ({
        ...banner,
        image: "/storage/" + banner.image.replace("public/", ""),
      }));
      localStorage.setItem("banners", JSON.stringify(banners.value));
      localStorage.setItem("bannersTimestamp", now.toString());
    } else {
      console.error("Invalid data format");
    }
  } catch (error) {
    console.error("Failed to fetch banners:", error);
  } finally {
    loadingBanners.value = false;
  }
};

const startAutoSlide = () => {
  autoSlideInterval = setInterval(() => {
    if (currentIndex.value < banners.value.length - 1) {
      nextSlide();
    } else {
      currentIndex.value = 0; // Reset to first slide after the last one
      currentTranslate.value = 0;
    }
  }, 3000); // Change every 3 seconds
};

// Touch Handlers
const handleTouchStart = (event) => {
  startX.value = event.touches[0].clientX;
};

const handleTouchMove = (event) => {
  const deltaX = event.touches[0].clientX - startX.value;
  currentTranslate.value =
    -currentIndex.value * bannerWidth + deltaX;
};

const handleTouchEnd = () => {
  const endTranslate = -currentIndex.value * bannerWidth;
  const delta = currentTranslate.value - endTranslate;

  if (delta > bannerWidth / 3 && currentIndex.value < banners.value.length - 1) {
    currentIndex.value++;
  } else if (delta < -bannerWidth / 3 && currentIndex.value > 0) {
    currentIndex.value--;
  }
  currentTranslate.value = -currentIndex.value * bannerWidth;
};

// Button Handlers
const nextSlide = () => {
  if (currentIndex.value < banners.value.length - 1) {
    currentIndex.value++;
    currentTranslate.value = -currentIndex.value * bannerWidth;
  }
};

const prevSlide = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
    currentTranslate.value = -currentIndex.value * bannerWidth;
  }
};

// Fetch banners on mount
onMounted(fetchBanners, startAutoSlide());
onBeforeUnmount(() => {
  // Clean up the interval when the component is destroyed
  if (autoSlideInterval) {
    clearInterval(autoSlideInterval);
  }
});
</script>

<style scoped>
.container {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.banner-container {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.banner-track {
  display: flex;
  transition: transform 0.3s ease-in-out;
}

.banner-item {
  min-width: 300px;
  /* Adjust banner width */
  height: 150px;
  /* Adjust banner height */
  margin-right: 10px;
}



.prev-btn,
.next-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  padding: 10px;
  border-radius: 50%;
  cursor: pointer;
  z-index: 10;
}

.prev-btn {
  left: 10px;
}

.next-btn {
  right: 10px;
}

.prev-btn:disabled,
.next-btn:disabled {
  background-color: rgba(0, 0, 0, 0.2);
  cursor: not-allowed;
}
</style>
