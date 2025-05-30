<template>
    <section class="about-feature pt-120 pb-120" style="margin-top: -10%;">
        <div class="container">
            <div class="course-top heading-space">
                <div class="section-heading mb-0">
                    <h2 class="section-title wow fade-in-bottom" data-wow-delay="400ms"
                        style="visibility: visible; animation-delay: 400ms; animation-name: fade-in-bottom;">News Letter
                    </h2>
                </div>
                <!-- Swipe Navigation Arrows -->
                <div class="swiper-arrow">
                    <div class="swiper-nav swiper-prev" tabindex="0" role="button" aria-label="Previous slide"
                        @click="prevSlide" :class="{ 'disabled': currentIndex === 0 }">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                    <div class="swiper-nav swiper-next" tabindex="0" role="button" aria-label="Next slide"
                        @click="nextSlide" :class="{ 'disabled': currentIndex === maxIndex }">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            </div>
            <!-- Dynamic Cards Container -->
            <div class="row gy-lg-0 gy-4 slider-container">
                <div class="slider-wrapper" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">

                    <div class="col-lg-3 col-md-6 slider-item" v-for="news in newsLetterData" :key="news.id">
                        <div class="promo-item-2 wow fade-in-bottom" data-wow-delay="300ms"
                            style="visibility: visible; animation-delay: 300ms; animation-name: fade-in-bottom;">
                            <div class="icon"><i class="fa-sharp fa-regular fa-newspaper fa-2x"></i></div>
                            <h3 class="title">
                                <router-link :to="`/page/${news.page_section_id}?newsid=${news.id}`"
                                    style="font-size: 20px;">
                                    {{ getNewsLetterTitle(news) }}
                                </router-link>
                            </h3>
                            <p> <i class="fa-sharp fa-regular fa-calendar"></i> {{ news.date }}</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- <div class="container">

            <div class="section-heading text-center white-content">
                <h4 class="sub-heading wow fade-in-bottom" data-wow-delay="200ms"
                    style="visibility: visible; animation-delay: 200ms; animation-name: fade-in-bottom;"><span
                        class="heading-icon"><i class="fa-sharp fa-solid fa-bolt"></i></span>News</h4>
            </div>
            <div class="row justify-content-center post-card-2-wrap">
                    <div class="col-lg-10">
                        <div class="post-card-2 post-inner wow fade-in-bottom" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fade-in-bottom;">
                            <div class="post-content-wrap">
                                <div class="post-content">
                                    <ul class="post-meta">
                                        <li><i class="fa-sharp fa-regular fa-calendar"></i>August 15, 2024</li>
                                    </ul>
                                    <h3 class="title">Weekly Newsletter </h3>
                                    <a href="#" class="ed-primary-btn">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="post-card-2 post-inner wow fade-in-bottom" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fade-in-bottom;">
                            <div class="post-content-wrap">
                                <div class="post-content">
                                    <ul class="post-meta">
                                        <li><i class="fa-sharp fa-regular fa-calendar"></i>August 15, 2024</li>
                                    </ul>
                                    <h3 class="title">Weekly Newsletter </h3>
                                    <a href="#" class="ed-primary-btn">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div> <div class="post-card-2 post-inner wow fade-in-bottom" data-wow-delay="200ms" style="visibility: visible; animation-delay: 200ms; animation-name: fade-in-bottom;">
                            <div class="post-content-wrap">
                                <div class="post-content">
                                    <ul class="post-meta">
                                        <li><i class="fa-sharp fa-regular fa-calendar"></i>August 15, 2024</li>
                                    </ul>
                                    <h3 class="title">Weekly Newsletter </h3>
                                    <a href="#" class="ed-primary-btn">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                      
                        <ul class="pagination-wrap text-center">
                            <li><a href="#">01</a></li>
                            <li><a href="#" class="active">02</a></li>
                            <li><a href="#">03</a></li>
                            <li><a href="#"><i class="fa-solid fa-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
        </div> -->

    </section>
</template>
<script setup>
import { ref, onMounted, watch, defineProps, computed } from 'vue';
import axios from 'axios';

// Reactive variables
const newsLetterData = ref([]); // Holds the newsletter data
const loadingNewsLetter = ref(false); // Tracks loading state
const cacheDuration = 1 * 60 * 1000; // Cache duration (1 minute)
// Reactive state for swipe
const currentIndex = ref(0);

// Total items and items per slide
const itemsPerSlide = 2; // Adjust based on your layout
const maxIndex = computed(() =>
    Math.max(0, Math.ceil(newsLetterData.value.length / itemsPerSlide) - 1)
);

// Handlers for navigation
const nextSlide = () => {
    
    if (currentIndex.value < maxIndex.value) {
        currentIndex.value++;
    }
};

const prevSlide = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};
// Props
const props = defineProps({
    language: {
        type: String,
        required: true
    }
});

// Fetch newsletter data from API
const fetchNewsLetter = async () => {
    const now = new Date().getTime();

    // Check if cached data exists and is valid
    const cachedData = sessionStorage.getItem('NewsletterCache');
    const cachedTimestamp = sessionStorage.getItem('NewsletterTimestamp');
    if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
        console.log('Using cached newsletter data from sessionStorage');
        newsLetterData.value = JSON.parse(cachedData); // Use cached data
        return;
    }

    // Fetch new data if cache is invalid or expired
    loadingNewsLetter.value = true;
    try {
        const response = await axios.get('/get_newsletter');
        console.log('Fetched newsletters:', response.data);

        if (response.data && Array.isArray(response.data)) {
            newsLetterData.value = response.data; // Assign new data

            // Cache the data
            sessionStorage.setItem('NewsletterCache', JSON.stringify(response.data));
            sessionStorage.setItem('NewsletterTimestamp', now.toString());
        } else {
            console.error('Invalid data format received from the API.');
        }
    } catch (error) {
        console.error('Failed to fetch newsletters:', error);
    } finally {
        loadingNewsLetter.value = false;
    }
};

// Get title based on selected language
const getNewsLetterTitle = (newsItem) => {
    
    switch (props.language) {
        case 'Hindi':
            return newsItem.hindi_title || newsItem.english_title; // Fallback to English if Hindi title is missing
        case 'Khasi':
            return newsItem.khasi_title || newsItem.english_title; // Fallback to English if Khasi title is missing
        case 'English':
        default:
            return newsItem.english_title;
    }
};


// Method to open the news item based on its type (link or file)
const openNews = (item) => {
    if (item.type === 'link' && item.link) {
        window.open(item.link, '_blank');  // Open link in a new tab
    } else if (item.type === 'file' && item.file) {
        const filePath = '/storage/' + item.file.replace('public/', '');  // Build file URL based on Laravel's storage path
        window.open(filePath, '_blank');  // Open the file in a new tab
    } else {
        console.error('Invalid news item', item);
    }
};
// Watch for changes in the `language` prop and refetch data when it changes
watch(() => props.language, (newLanguage, oldLanguage) => {
    console.log('News letter Language changed from', oldLanguage, 'to', newLanguage);
    fetchNewsLetter(); // Re-fetch the newsletter data when the language changes
});

// Fetch news data when the component is mounted
onMounted(() => {
    fetchNewsLetter();
});
</script>

<style scoped>
/* Swipe container */
.slider-container {
    overflow: hidden;
    width: 100%;
    position: relative;
    padding: 0 15px;
    /* Add padding to give space on the sides */
}

/* Slider wrapper */
.slider-wrapper {
    display: flex;
    transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.5, 1);
    /* Smooth slide animation */
    gap: 20px;
    /* Add space between cards */
}

/* Each slide */
.slider-item {
    min-width: calc(25% - 20px);
    /* Adjust width to account for the gap */
    flex-shrink: 0;
    /* Ensure items don’t shrink */
    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    /* Optional animations */
}

/* Optional hover animation for individual cards */
.slider-item:hover {
    transform: scale(1.05);
    /* Slight zoom effect */
    opacity: 0.9;
}

/* Arrow styles */
.swiper-arrow {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.swiper-nav {
    cursor: pointer;
    padding: 0.8rem;
    background-color: #ccc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
}

.swiper-nav:hover {
    background-color: #bbb;
}

.swiper-nav.disabled {
    opacity: 0.5;
    pointer-events: none;
}

.fa-solid.fa-file-pdf {
    color: #e80c2d;
    /* Change the icon color to red (adjust the color as needed) */
}
</style>
