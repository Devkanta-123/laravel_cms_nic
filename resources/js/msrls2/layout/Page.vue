<template>
    <Loader v-if="isLoading" />

    <br><br><br><br>
    <div v-if="route.params.id == 1 || route.params.id == null">
        <Carousel></Carousel>
        <br><br><br>
        <LatestNews :menu="props.id" />
    </div>

    <div v-else class="container">
        <!-- Render Page Content if available -->
        <h5 class="m-0">{{ pageName }}</h5>
        <p v-if="pageContent" v-html="pageContent" class="aos-init aos-animate" style="color: #f7a400;"></p>

        <!-- Show Photo Gallery only if pageContent is unavailable -->
        <div v-else-if="gallerydata.length > 0">
            <PhotoGallery :galleries="gallerydata" />
        </div>

        <div v-if="noticeboarddata.length > 0">
            <!-- <NoticeBoard :noticeboard="noticeboarddata" :key="refreshKey" /> -->
            <NoticeBoard :noticeboard="noticeboarddata" :key="refreshKey" :pageName="route.query.page_name" />
        </div>

        <div v-else-if="notificationdata.length > 0">
            <NoticeBoard :notification="notificationdata" :key="refreshKey" :pageName="route.query.page_name" />
        </div>


        <!-- Ensure NoticeBoard gets the correct props -->
        <!-- <NoticeBoard v-else :noticeboard="noticeboarddata" :notification="notificationdata" /> -->

        <!-- If nothing is available, show a message -->
        <!-- <p v-else style="color: red;">Data not available</p> -->
    </div>

</template>
<script setup>
import { ref, onMounted, inject, watch, computed } from 'vue';
import { useRoute } from 'vue-router';
import Carousel from '../components/Carousel.vue';
import LatestNews from '../components/LatestNews.vue';
import Footer from './Footer.vue';
import Loader from '../../components/Loader.vue';
import PhotoGallery from '../components/PhotoGallery.vue';
import NoticeBoard from '../components/NoticeBoard.vue';
const route = useRoute();
const isLoading = ref(true);
const props = defineProps({

    id: {
        type: String,
        required: true
    }
});


const currentLanguage = inject('language');
const pageTitle = ref('');
const pageContent = ref('');
const gallerydata = ref('');
const noticeboarddata = ref('');
const notificationdata = ref('');
const refreshKey = ref(0); // This will force Vue to re-render the component
let pageName = ref('');
const fetchPageContent = async () => {
    try {
        debugger;
         pageName = route.query.page_name; // Get page_name from query params
        console.log("Page Name:", pageName);

        // Reset all data before fetching new content
        pageContent.value = null;
        gallerydata.value = [];
        noticeboarddata.value = [];
        notificationdata.value = [];

        let response; // Declare response variable

        switch (pageName) {
            case "Gallery":
                response = await axios.get(`/get_galleries`);
                gallerydata.value = response.data.length > 0 ? response.data : [];
                break;

            case "Notice Board":
                response = await axios.get(`/api/getallnotifications`);
                noticeboarddata.value = response.data;
                console.log("Notice Board Data:", noticeboarddata.value);
                break;

            case "Newsletter":
            case "Recruitments":
            case "Tender":
            case "Notification":
                response = await axios.get(`/api/getnotificationsbycategory/${pageName}`);
                notificationdata.value = response.data;
                console.log("Notification Data:", notificationdata.value);
                break;

            default:
                response = await axios.get(`/get_page_content/${route.params.id}`);

                if (response.data) {
                    debugger;
                    pageContent.value = response.data.title
                        ? response.data.title
                        : response.data.title;
                }
        }
    } catch (error) {
        console.error('Failed to fetch page content:', error);
        pageContent.value = null; // Set to null on API error
    }
};



onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 500);
    console.log("On Mounted of Page");
    fetchPageContent();
});

watch(
    () => [route.params.id, route.query.page_name], // Watch both ID and page_name
    () => {
        isLoading.value = true; // Show loader
        setTimeout(() => {
            isLoading.value = false;
        }, 500);

        fetchPageContent();
    }
);

</script>

<style scoped>
.content {
    padding: 20px;
}

</style>
