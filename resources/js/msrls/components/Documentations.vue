<template>

    <div class="row gy-lg-0 gy-5">
        <div class="col-lg-4 col-md-6">
            <div class="about-feature-card">
                <div class="icon">
                    <i class="fa-solid fa-bullhorn fa-2x"></i>
                </div>
                <div class="content">
                    <h3 class="title">Notice Board</h3>
                    <!-- Marquee container for the list -->
                    <div class="marquee-container">
                        <ul class="content-list wow fade-in-bottom" data-wow-delay="600ms"
                            v-for="news in newsLetterData" :key="news.id">
                            <li> &nbsp;
                                <router-link :to="`/page/${news.page_section_id}?newsid=${news.id}`"
                                    style="font-size: 20px;">
                                    {{ getNewsLetterTitle(news) }}
                                </router-link>
                            </li><br>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="about-feature-card">
                <div class="icon"><i class="fa-solid fa-file-pdf fa-2x"></i></div>
                <div class="content">
                    <h3 class="title">Documentations</h3>
                    <p>Incorporating features like quizzes, <br> simulations, and multimedia content that <br>
                        actively engage students.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="about-feature-card">
                <div class="icon"> <i class="fas fa-project-diagram fa-3x"></i></div>
                <div class="content">
                    <h3 class="title">Projects</h3>
                    <p>Incorporating features like quizzes, <br> simulations, and multimedia content that <br>
                        actively engage students.</p>
                </div>
            </div>
        </div>
    </div>

</template>
<script setup>
import { ref, onMounted, watch, defineProps, computed } from 'vue';
import axios from 'axios';

// Reactive variables
const newsLetterData = ref([]); // Holds the newsletter data
const loadingNewsLetter = ref(false); // Tracks loading state
const cacheDuration = 1 * 60 * 1000; // Cache duration (1 minute)
// Fetch newsletter data from API
const fetchNewsLetter = async () => {
    debugger;
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

// Props
const props = defineProps({
    language: {
        type: String,
        required: true
    }
});

// Get title based on selected language
const getNewsLetterTitle = (newsItem) => {
    debugger;
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



// Fetch news data when the component is mounted
onMounted(() => {
    fetchNewsLetter();
});
</script>
<style scoped>
.marquee-container {
    height: 150px;
    /* Set the height of the marquee container */
    overflow: hidden;
    /* Hide overflowing content */
}

.content-list {
    list-style-type: none;
    /* Remove default bullet points */
    margin: 0;
    padding: 0;
    animation: move-up 10s linear infinite;
    /* Apply the bottom-to-top animation */
    animation-play-state: running;
    /* Ensure the animation is running by default */
}

.content-list li {
    font-size: 16px;
    padding: 10px 0;
}

/* Define the keyframes for the animation */
@keyframes move-up {
    0% {
        transform: translateY(100%);
        /* Start from the bottom */
    }

    100% {
        transform: translateY(-100%);
        /* Move to the top */
    }
}

/* Pause the animation on hover */
.marquee-container:hover .content-list {
    animation-play-state: paused;
    /* Pause the animation when the marquee container is hovered */
}
</style>