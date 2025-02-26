<template>
    <h2 class="section-title wow fade-in-bottom" data-wow-delay="400ms"
        style="visibility: visible; animation-delay: 400ms; animation-name: fade-in-bottom; margin-left: 40%;">Photo
        Gallery</h2>
    <section class="blog-grid-area pt-120 pb-120" style="margin-top: -4%;">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-4" v-for="gallery in galleriesData" :key="gallery.id">
                    <div class="post-card-2 post-card-3 inner-post-2 wow fade-in-bottom" data-wow-delay="300ms"
                        style="visibility: visible; animation-delay: 300ms; animation-name: fade-in-bottom;">
                        <div class="post-thumb">
                            <!-- Dynamically load the gallery image -->
                            <img :src="'/storage/' + gallery.gallery_image" alt="Gallery Image">
                        </div>
                        <div class="post-content-wrap">
                            <div class="post-content">
                                <!-- Dynamically load the gallery name -->
                                <h3 class="title">{{ gallery.gallery_name }}</h3>
                            </div>
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
const CACHE_TIMEOUT = 1 * 60 * 1000; // 5 minutes in milliseconds

// Reactive data for galleries
const galleriesData = ref(null);

// Function to get galleries data with caching
const getGalleries = async () => {
    // Try to load the cached data from localStorage
    const cachedData = localStorage.getItem(CACHE_KEY);

    if (cachedData) {
        // Parse the cached data
        const { data, timestamp } = JSON.parse(cachedData);
        const currentTime = new Date().getTime();

        // Check if the cache is still valid (within the timeout)
        if (currentTime - timestamp < CACHE_TIMEOUT) {
            galleriesData.value = data;
            console.log('Using cached galleries data');
            return;
        }
    }

    // Cache is either not found or expired, so fetch from API
    try {
        const response = await axios.get("/get_galleries");
        galleriesData.value = response.data;

        // Store the fetched data in localStorage with a timestamp
        const cacheData = {
            data: response.data,
            timestamp: new Date().getTime(),
        };
        localStorage.setItem(CACHE_KEY, JSON.stringify(cacheData));

        console.log('Galleries fetched successfully', response.data);
    } catch (error) {
        console.log('Error fetching galleries', error);
    }
}

// Fetch galleries data when component is mounted
onMounted(() => {
    getGalleries();
});
</script>