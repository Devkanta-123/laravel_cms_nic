<template>
    <div class="container">
        <!-- <div class="col-lg-6 col-md-12">
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
                                        <a href="#" @click.prevent="openNews(item)" style="color: #3c3932;">
                                            {{ getNoticeboardTitle(item) }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        <!-- <div class="col-lg-6 col-md-12">
                <div class="features__item-two">
                    <div class="features__icon-two">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="features__content-two">
                        <h4 class="title"><a href="#">Notice Board</a></h4>
                        <div class="about__list-box">
                            <div class="marquee-container" @mouseover="pauseScroll" @mouseleave="resumeScroll">
                                <ul class="list-wrap" ref="newsList">
                                    <li v-for="(item, index) in newsData" :key="item.menu_id" >
                                        <a href="#" @click.prevent="openNews(item)" style="color: #3c3932;">
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
            </div> -->

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-title text-center mb-50 tg-heading-subheading animation-style3">
                        <span class="sub-title">
                            NOTICE BOARD <i class="fa-solid fa-bullhorn"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row position-relative">
                <div class="d-flex justify-content-between align-items-center w-100 mb-3">

                    <div class="testimonial__nav-four">
                        <div class="testimonial-two-button-prev" tabindex="0" role="button" @click="prevSlide"
                            aria-label="Previous slide" aria-controls="swiper-wrapper-109a395953f46aece"> <i
                                class="fa fa-chevron-right"></i>
                        </div>
                        <div class="testimonial-two-button-next" tabindex="0" role="button" @click="nextSlide"
                            aria-label="Next slide" aria-controls="swiper-wrapper-109a395953f46aece"><i
                                class="fa fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
                <template v-for="(item, index) in visibleNews" :key="item.id">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="blog__post-two shine-animate-item">
                            <div class="blog__post-thumb-two">
                                <a class="shine-animate" target="_blank">
                                    <img :src="event" alt="Apexa" />
                                </a>
                            </div>
                            <div class="blog__post-content-two">
                                <div class="blog-post-meta">
                                    <ul class="list-wrap">
                                        <li>
                                            <a href="#" class="blog__post-tag-two ">
                                                {{ categoryNames[item.category_id] || 'Unknown' }}
                                            </a>
                                        </li>
                                        <li>
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ new Date(item.date).toLocaleDateString('en-GB') }}
                                        </li>
                                    </ul>
                                </div>
                                <h2 class="title contactus">
                                    <a href="#" @click.prevent="openNews(item)">{{ item.title }}</a>
                                </h2>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <router-link :to="{ name: 'Page', params: { id: 45 }, query: { page_name: 'Notice Board' } }" class="btn"
                :style="{ position: 'relative', '--after-display': 'none', '--before-display': 'none' }"
                data-aos="fade-up" data-aos-delay="600">
                See All  Notification
            </router-link>
        </div>
        <!-- <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="section-title text-center mb-50 tg-heading-subheading animation-style3">
                        <span class="sub-title">RECRUITMENTS</span>
                    </div>
                </div>
                <div class="row gutter-24 justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="blog__post-four shine-animate-item">
                            <div class="blog__post-content-four">
                                <h2 class="title"><a href="blog-details.html">Marketing your area business downturn now
                                        a
                                        days</a></h2>
                                <div class="blog-post-meta blog-post-meta-two">
                                    <ul class="list-wrap">
                                        <li><i class="fas fa-calendar-alt"></i>Oct 21, 2024</li>
                                        <li><i class="far fa-comment-alt"></i><a href="blog-details.html">02
                                                Comments</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div> -->
    </div>



</template>
<script setup>
import { ref, onMounted, inject, provide, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import event from '@/assets/images/event.png'
const router = useRouter();
const currentIndex = ref(0);
const itemsPerPage = 3; // S
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
const categoryNames = {
    1: 'Newsletter',
    2: 'Tenders',
    3: 'Recruitments',
    4: 'Notifications',
    5: 'Absorption'
};
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
        const response = await axios.get(`/get_notificationsforcurrentmonth`, { params: { flag: 'A' } });
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
        
        const response = await axios.get(`/get_recruitmentsforcurrentmonth`, { param: { flag: 'A' } });
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


const visibleNews = computed(() => {
    return newsData.value.slice(currentIndex.value, currentIndex.value + itemsPerPage);
});

const nextSlide = () => {
    if (currentIndex.value + itemsPerPage < newsData.value.length) {
        currentIndex.value += itemsPerPage;
    }
};

const prevSlide = () => {
    if (currentIndex.value - itemsPerPage >= 0) {
        currentIndex.value -= itemsPerPage;
    }
};
// Method to open the newsletter item based on its type (link or file)

const openNews = (item) => {
    const filePath = '/storage/' + item.file;  // No need to replace 'public/' if file already starts without it
    window.open(filePath, '_blank');  // Open the file in a new tab
};

// Fetch newsletter data when the component is mounted
onMounted(() => {
    fetchNotificationsForCurrentMonth();
    fetchRecruitmentsForCurrentMonth();
});

</script>
<style scoped></style>