<template>
    <Loader v-if="isLoading" />

    <br><br><br><br>
    <div v-if="route.params.id == 1 || route.params.id == null">
        <Carousel></Carousel>
        <br><br><br>
        <LatestNews :menu="props.id" />

    </div>

    <section class="breadcrumb__area breadcrumb__bg" data-background="assets/img/bg/breadcrumb_bg.jpg"
        style="background-image: url(&quot;assets/img/bg/breadcrumb_bg.jpg&quot;);">
        <div class="container" style="margin-top: -130px;">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb__content">
                        <h2 class="title">{{ pageName }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <router-link to="/page/1">Home</router-link>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ pageName }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <section class="about__area-five" style="margin-top: -160px;">
        <div class="container">
            <div v-if="!activeComponent">
                <div v-if="pageContent" v-html="pageContent" class="aos-init aos-animate" style="color: #2A3335;"></div>

                <!-- Show Photo Gallery only if pageContent is unavailable -->
                <div v-else-if="gallerydata.length > 0">
                    <PhotoGallery :galleries="gallerydata" />
                </div>

                <div v-if="noticeboarddata.length > 0">
                    <NoticeBoard :noticeboard="noticeboarddata" :key="refreshKey" :pageName="route.query.page_name" />
                </div>

                <div v-else-if="notificationdata.length > 0">
                    <NoticeBoard :notification="notificationdata" :key="refreshKey" :pageName="route.query.page_name" />
                </div>
                <!-- whoswhodata -->
                <div v-if="showWhosWho">
                    <WhosWho />
                </div>

            </div>
        </div>
    </section>


    <!-- Dynamically render the component only when activeComponent is set -->
    <component v-if="activeComponent" :is="activeComponent" :language="language"></component>

</template>
<script setup>
import { ref, onMounted, inject, watch, provide, shallowRef } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

import Carousel from '../components/Carousel.vue';
import LatestNews from '../components/LatestNews.vue';
import Footer from './Footer.vue';
import Loader from '../../components/Loader.vue';
import PhotoGallery from '../components/PhotoGallery.vue';
import NoticeBoard from '../components/NoticeBoard.vue';
import FAQS from '../components/FAQ.vue';
import Accessibility from '../settings/Accessibility.vue';
import WhosWho from '../components/WhosWho.vue';

const route = useRoute();
const isLoading = ref(true);

// Accept props
const props = defineProps({
    language: String,  // Language prop
    id: String,        // ID for the page
    pageName: String   // Page Name for dynamic rendering
});

// Inject and provide language for child components
const language = inject("language");
provide("language", language);

// Reactive state variables
const activeComponent = shallowRef(null); // Holds the dynamic component
const pageContent = ref('');
const gallerydata = ref([]);
const noticeboarddata = ref([]);
const notificationdata = ref([]);
const showWhosWho = ref(false);
const refreshKey = ref(0); // Used to force re-render on data change

const pageName = ref(''); // Reactive pageName reference

const fetchPageContent = async () => {
    try {
        debugger;
        isLoading.value = true; // Set loading state
        const cardId = route.query.cardid;

        // Get page_name from query params
        pageName.value = route.query.page_name || props.pageName || '';

        // Reset dynamic data before fetching new content
        pageContent.value = null;
        gallerydata.value = [];
        noticeboarddata.value = [];
        notificationdata.value = [];
        activeComponent.value = null; // Reset active component before switching

        let response; // Declare response variable

        if (cardId) {
            // If cardId is present, fetch page content using cardId
            response = await axios.get(`/get_page_content/${cardId}`);
            if (response.data) {
                debugger;
                pageContent.value = response.data.content || '';
            }
        } else {
            // Otherwise, process based on pageName
            switch (pageName.value) {
                case "Gallery":
                    response = await axios.get(`/get_galleries`);
                    gallerydata.value = response.data.length > 0 ? response.data : [];
                    break;

                case "Notice Board":
                    response = await axios.get(`/get_notifications`);
                    noticeboarddata.value = response.data;
                    console.log("Notice Board Data:", noticeboarddata.value);
                    break;

                case "Newsletter":
                case "Recruitments":
                case "Tenders":
                case "Notification":
                    response = await axios.get(`/get_notificationbycategory/${pageName.value}`);
                    notificationdata.value = response.data;
                    console.log("Notification Data:", notificationdata.value);
                    break;

                case "FAQ":
                    activeComponent.value = FAQS; // Dynamically load FAQ component
                    break;

                case "WhosWho":
                    showWhosWho.value = true;
                    break;


                default:
                    response = await axios.get(`/get_page_content/${route.params.id}`);
                    if (response.data) {
                        debugger;
                        pageContent.value = response.data.content || '';
                    }
            }
        }
    } catch (error) {
        console.error('Failed to fetch page content:', error);
        pageContent.value = null; // Set to null on API error
    } finally {
        isLoading.value = false; // Stop loading
    }
};

// Watch for route changes and re-fetch data dynamically
watch(
    () => [route.params.id, route.query.page_name, props.language],
    ([newId, newPage, newLang]) => {
        console.log('Route changed - ID:', newId, 'Page:', newPage);
        console.log('Language updated in Page:', newLang);
        isLoading.value = true;
        setTimeout(() => {
            isLoading.value = false;
        }, 400);

        fetchPageContent();
    },
    { immediate: true }
);

onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 500);
    console.log("On Mounted of Page");
    fetchPageContent();
});
</script>

<style scoped>
.content {
    padding: 20px;
}
</style>
