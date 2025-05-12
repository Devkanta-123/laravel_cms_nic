<template>
  <section class="blog-grid-area pt-120 pb-120" style="margin-top: -4%;">
    <div class="container">
      <div class="row justify-content-center gy-4">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8" v-for="gallery in galleriesData" :key="gallery.id">
          <div class="services-item shine-animate-item">
            <div class="services-thumb">
              <a href="#" class="shine-animate">
                <img v-if="gallery.image" :src="gallery.image" :alt="gallery.gallery_name">
                <img v-else :src="'/storage/' + gallery.gallery_image" :alt="gallery.gallery_name">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Cache key and timeout
const CACHE_KEY = 'ce387d5e0a2972dea9e5129a52ac3b8d58a4d180fc9eece5946d926643a3d2c0'; //SHA-256 gallery
const CACHE_TIMEOUT = 10 * 60 * 1000; // 5 minutes in milliseconds

// Reactive data for galleries
const galleriesData = ref(null);

// Function to get galleries data with caching
// const getGalleries = async () => {
//   // Try to load the cached data from localStorage
//   const cachedData = localStorage.getItem(CACHE_KEY);

//   if (cachedData) {
//       // Parse the cached data
//       const { data, timestamp } = JSON.parse(cachedData);
//       const currentTime = new Date().getTime();

//       // Check if the cache is still valid (within the timeout)
//       if (currentTime - timestamp < CACHE_TIMEOUT) {
//           galleriesData.value = data;
//           console.log('Using cached galleries data');
//           return;
//       }
//   }

//   // Cache is either not found or expired, so fetch from API
//   try {
//       const response = await axios.get("/get_galleries");
//       galleriesData.value = response.data;

//       // Store the fetched data in localStorage with a timestamp
//       const cacheData = {
//           data: response.data,
//           timestamp: new Date().getTime(),
//       };
//       localStorage.setItem(CACHE_KEY, JSON.stringify(cacheData));

//       console.log('Galleries fetched successfully', response.data);
//   } catch (error) {
//       console.log('Error fetching galleries', error);
//   }
// }
const getGalleries = async () => {
  debugger;
  const now = new Date().getTime();

  // Try to load the cached data from localStorage
  const cachedData = localStorage.getItem(CACHE_KEY);

  if (cachedData) {
    // Parse the cached data
    const { data, timestamp } = JSON.parse(cachedData);

    // Check if the cache is still valid (within the timeout period)
    if (now - timestamp < CACHE_TIMEOUT) {
      galleriesData.value = data;  // Set the cached data
      console.log("Using cached galleries data");
      return;
    }
  }

  // Cache is either not found or expired, so fetch from API
  try {
    const response = await axios.get('/get_galleries', {
      params: { flag: 'A' }
    });


    // Process images and convert them to Base64
    const galleryDataWithBase64Images = await Promise.all(
      response.data.map(async (gallery) => {
        // Safely handle the image field
        const filePath = gallery.image ? "/storage/" + gallery.image.replace("public/", "") : null;

        if (filePath) {
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
              ...gallery,
              image: imageBase64,  // Store Base64 encoded image
            };
          } catch (imageError) {
            console.error(`Failed to fetch image for gallery ${gallery.id}:`, imageError);
            return {
              ...gallery,
              image: null,  // Store null if Base64 conversion fails
            };
          }
        } else {
          return {
            ...gallery,
            image: null,  // If image is not defined, store null
          };
        }
      })
    );

    // Set the gallery data with Base64 images
    galleriesData.value = galleryDataWithBase64Images;

    // Store the fetched data in localStorage with a timestamp
    const cacheData = {
      data: galleriesData.value,
      timestamp: now,  // Store the current timestamp
    };
    localStorage.setItem(CACHE_KEY, JSON.stringify(cacheData));

    console.log("Galleries fetched successfully with Base64 images", galleryDataWithBase64Images);
  } catch (error) {
    console.log("Error fetching galleries", error);
  }
};


// Fetch galleries data when component is mounted
onMounted(() => {
  getGalleries();
});
</script>