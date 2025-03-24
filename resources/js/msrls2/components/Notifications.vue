<template>
    <div class="container">
        <div class="row gutter-24 justify-content-center" style="margin-top:-170px;">
            <div class="col-lg-6 col-md-12">
                <div class="features__item-two">
                    <div class="features__icon-two">
                        <i class="fa-solid fa-clipboard"></i>
                    </div>
                    <div class="features__content-two">
                        <h4 class="title"><a href="#">Recruitments</a></h4>
                        <div class="about__list-box">
                            <div class="marquee-container" @mouseover="pauseScroll" @mouseleave="resumeScroll">
                                <ul class="list-wrap">
                                    <li v-for="(item, index) in recruitmentsData" :key="item.menu_id">
                                        <a href="#" @click.prevent="openNews(item)">
                                            {{ getNoticeboardTitle(item) }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="features__item-two">
                    <div class="features__icon-two">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="features__content-two">
                        <h4 class="title"><a href="#">Notice Board</a></h4>
                        <div class="about__list-box">
                            <!-- Dynamic List: Scrolling enabled only here -->
                            <div class="marquee-container" @mouseover="pauseScroll" @mouseleave="resumeScroll">
                                <ul class="list-wrap" ref="newsList">
                                    <li v-for="(item, index) in newsData" :key="item.menu_id">
                                        <a href="#" @click.prevent="openNews(item)">
                                            {{ getNoticeboardTitle(item) }}
                                        </a>
                                    </li>
                                </ul>

                            </div>

                        </div>
                        <a href="#" class="btn btn-two custom-btn">
                            View More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script setup>
import { ref, onMounted, inject, provide } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
const router = useRouter();

const newsData = ref([]);  // Holds the newsletter data
const recruitmentsData = ref([]); // Holds the
const loadingNewsLetter = ref(false);  // Tracks if the newsletter data is loading
const loadingRecruitments = ref(false); // Tracks if the recruitment data is loading
const cacheDuration = 1 * 60 * 1000;  // Cache duration (1 minute) in milliseconds
const language = inject("language");
provide("language", language); // Provide language to all child components
const props = defineProps({
    language: {
        type: String,
        required: true
    }
});

// Fetch the latest newsletter data
const fetchNotificationsForCurrentMonth = async () => {
    const now = new Date().getTime();
    // Check if cached data exists and is valid
    const cachedData = sessionStorage.getItem('NewsLetter');
    const cachedTimestamp = sessionStorage.getItem('NewsLetterTimestamp');
    if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
        console.log('Using cached newsletter data from sessionStorage');
        newsData.value = JSON.parse(cachedData);  // Set cached data
        return;
    }

    // If cache is invalid or expired, fetch new data
    loadingNewsLetter.value = true;
    try {
        const response = await axios.get(`/get_notificationsforcurrentmonth`);
        if (response.data && Array.isArray(response.data)) {
            newsData.value = response.data;

            // Store data in sessionStorage with a timestamp
            sessionStorage.setItem('NewsLetter', JSON.stringify(response.data));
            sessionStorage.setItem('NewsLetterTimestamp', now.toString());
        } else {
            console.error('Invalid data format');
        }
    } catch (error) {
        console.error('Failed to fetch newsletter:', error);
    } finally {
        loadingNewsLetter.value = false;
    }
};

// Fetch the latest recruitments data

const fetchRecruitmentsForCurrentMonth = async () => {
    const now = new Date().getTime();
    // Check if cached data exists and is valid
    const cachedData = sessionStorage.getItem('Recruitments');
    const cachedTimestamp = sessionStorage.getItem('RecruitmentsTimestamp');
    if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
        console.log('Using cached recruitments data from sessionStorage');
        recruitmentsData.value = JSON.parse(cachedData);  // Set cached data
        return;
    }

    // If cache is invalid or expired, fetch new data
    loadingRecruitments.value = true;
    try {
        debugger;
        const response = await axios.get(`/get_recruitmentsforcurrentmonth`);
        if (response.data && Array.isArray(response.data)) {
            recruitmentsData.value = response.data;

            // Store data in sessionStorage with a timestamp
            sessionStorage.setItem('Recruitments', JSON.stringify(response.data));
            sessionStorage.setItem('RecruitmentsTimestamp', now.toString());
        } else {
            console.error('Invalid data format');
        }
    } catch (error) {
        console.error('Failed to fetch recruitments:', error);
    } finally {
        loadingRecruitments.value = false;
    }

};

// Function to get the correct title based on the selected language
const getNoticeboardTitle = (newsItem) => {
    switch (props.language) {
        case 'Hindi':
            return newsItem.hindi_title || newsItem.english_title;  // Fallback to default title if hindi_title is missing
        case 'Khasi':
            return newsItem.khasi_title || newsItem.english_title;  // Fallback to default title if khasi_title is missing
        case 'English':
        default:
            return newsItem.title;
    }
};

// Method to open the newsletter item based on its type (link or file)
const openNews = (item) => {
    debugger;
    const filePath = '/storage/' + item.file.replace('public/', '');  // Build file URL based on Laravel's storage path
    window.open(filePath, '_blank');  // Open the file in a new tab
};

// Fetch newsletter data when the component is mounted
onMounted(() => {
    fetchNotificationsForCurrentMonth();
    fetchRecruitmentsForCurrentMonth();
});

</script>
<style scoped>
/* Only apply scrolling to .marquee-container (Dynamic List) */
.marquee-container {
    overflow: hidden;
    height: 170px;
    /* Adjust based on how many items should be visible */
    position: relative;
}

.marquee-container .list-wrap {
    display: flex;
    flex-direction: column;
    gap: 10px;
    /* Spacing between items */
    animation: scrollUp 10s linear infinite;
}

@keyframes scrollUp {
    0% {
        transform: translateY(100%);
    }

    100% {
        transform: translateY(-100%);
    }
}

/* Pause scrolling on hover */
.marquee-container:hover .list-wrap {
    animation-play-state: paused;
}

.custom-btn {
    font-size: 12px;
    /* Reduce font size */
    padding: 4px 10px;
    /* Reduce padding */
    margin-left: 45%;
    display: inline-block;
    position: relative;
    text-decoration: none;
    border: 1px solid #000;
    /* Optional border */
    background: #f2f2f2;
    /* Button color */
    color: #333;
}

.custom-btn::before,
.custom-btn::after {
    display: none;
    /* Initially hidden */
}

/* Hide the button when clicked */
.custom-btn.hide {
    display: none;
}
</style>