<template>
<section class="blog-grid-area pt-120 pb-120" style="margin-top: -4%;">
  <div class="container">
    <div class="row justify-content-center gy-4">
      <div class="col-lg-4 col-md-6" v-for="gallery in galleriesData" :key="gallery.id">
        <div class="project__item-two">
          <div class="project__thumb-two">
            <a href="#" data-toggle="modal" data-target="#galleryItems" @click="selectedGallery = gallery">
              <img v-if="gallery.image" :src="gallery.image" :alt="gallery.gallery_name" />
              <img v-else :src="'/storage/' + gallery.gallery_image" :alt="gallery.gallery_name" />
            </a>
          </div>
          <div class="project__content-two">
            <h2 class="title">
              <a href="#" data-toggle="modal" data-target="#galleryItems" @click="selectedGallery = gallery">
                {{ gallery.gallery_name }}
              </a>
            </h2>
            <span>{{ gallery.meta_title || 'Gallery' }}</span>
            <div class="link-arrow link-arrow-two">
              <a href="#" data-toggle="modal" data-target="#galleryItems" @click="selectedGallery = gallery">
                <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  <!--Gallery Item Modal -->
  <div class="modal fade" id="galleryItems" tabindex="-1" role="dialog" aria-labelledby="galleryItemsTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
         <i class="far fa-window-close" data-dismiss="modal" aria-label="Close"></i>
        </div>
        <div class="modal-body">
          <div v-if="selectedGallery?.items.length > 0" class="gallery-slider">
            <div class="slide" v-for="(item, index) in selectedGallery.items" :key="index"
              v-show="currentSlide === index">
              <img :src="'/storage/' + item.image" :alt="item.name" class="img-fluid" />
              <p class="mt-2 text-center">{{ item.name }}</p>
            </div>
            <div class="d-flex justify-content-between mt-3">
              <div class="testimonial__nav-four">
                <div class="testimonial-two-button-prev button-swiper-prev" tabindex="0" role="button"
                  @click="prevSlide">
                  <i class="fa fa-chevron-right"></i>
                </div>
                <div class="testimonial-two-button-next button-swiper-next" tabindex="0" role="button"
                  @click="nextSlide">
                  <i class="fa fa-chevron-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div v-else>
            <p>No items in this gallery.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
const selectedGallery = ref(null);
const currentSlide = ref(0);
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
  const now = new Date().getTime();
  const cachedData = localStorage.getItem(CACHE_KEY);

  if (cachedData) {
    const { data, timestamp } = JSON.parse(cachedData);
    if (now - timestamp < CACHE_TIMEOUT) {
      galleriesData.value = data;
      console.log("Using cached galleries data");
      return;
    }
  }

  try {
    const response = await axios.get('/get_galleries', { params: { flag: 'A' } });

    // Group by gallery_id
    const grouped = {};
    for (const row of response.data) {
      const galleryId = row.gallery_id;

      if (!grouped[galleryId]) {
        grouped[galleryId] = {
          id: galleryId,
          gallery_name: row.gallery_name,
          gallery_description: row.gallery_description,
          gallery_image: row.gallery_image,
          image: null, // Will be filled with Base64
          items: []
        };
      }

      if (row.name && row.image) {
        grouped[galleryId].items.push({
          name: row.name,
          image: row.image
        });
      }
    }

    // Convert to array and process Base64 images
    const galleryArray = await Promise.all(
      Object.values(grouped).map(async (gallery) => {
        const filePath = gallery.gallery_image ? "/storage/" + gallery.gallery_image.replace("public/", "") : null;

        if (filePath) {
          try {
            const imageResponse = await fetch(filePath);
            const imageBlob = await imageResponse.blob();
            const imageBase64 = await new Promise((resolve, reject) => {
              const reader = new FileReader();
              reader.onloadend = () => resolve(reader.result);
              reader.onerror = reject;
              reader.readAsDataURL(imageBlob);
            });

            return { ...gallery, image: imageBase64 };
          } catch (imageError) {
            console.error(`Failed to fetch gallery image ${gallery.id}:`, imageError);
            return { ...gallery, image: null };
          }
        } else {
          return { ...gallery, image: null };
        }
      })
    );

    galleriesData.value = galleryArray;

    localStorage.setItem(CACHE_KEY, JSON.stringify({
      data: galleryArray,
      timestamp: now
    }));

    console.log("Grouped galleries with items:", galleryArray);
  } catch (error) {
    console.error("Error fetching galleries", error);
  }
};


const prevSlide = () => {
  if (!selectedGallery.value?.items.length) return;
  currentSlide.value = (currentSlide.value - 1 + selectedGallery.value.items.length) % selectedGallery.value.items.length;
};

const nextSlide = () => {
  if (!selectedGallery.value?.items.length) return;
  currentSlide.value = (currentSlide.value + 1) % selectedGallery.value.items.length;
};

// Reset to first slide on modal open
watch(selectedGallery, () => {
  currentSlide.value = 0;
});



// Fetch galleries data when component is mounted
onMounted(() => {
  getGalleries();
});
</script>