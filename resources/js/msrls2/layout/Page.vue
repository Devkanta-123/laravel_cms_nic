<template>
    <br><br><br><br>
    <section class="breadcrumb__area breadcrumb__bg"
        :style="{ backgroundImage: `url(${bgImage})`, marginTop: '-130px' }">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb__content">
                        <h6 class="title text-white">{{ pageName }}</h6>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active text-white">
                                    <router-link to="/page/1">Home</router-link>
                                </li>
                                <li class="breadcrumb-item active text-white" aria-current="page">{{ pageName }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <section class="blog__details-area mt-n100">
        <div class="container">
            <div class="blog__inner-wrap">
                <div class="row">
                    <div class="col-70">
                        <div class="blog__details-wrap">
                            <div class="blog__details-thumb" v-if="!activeComponent">
                                <div class="blog-post-meta">
                                    <div v-if="pageContent" v-html="pageContent" class="aos-init aos-animate"
                                        style="color: #2A3335;"></div>
                                    <div v-else-if="gallerydata.length > 0">
                                        <PhotoGallery :galleries="gallerydata" />
                                    </div>
                                    <div v-if="noticeboarddata.length > 0">
                                        <NoticeBoard :noticeboard="noticeboarddata" :key="refreshKey"
                                            :pageName="route.query.page_name" style="color: #2A3335;" />
                                    </div>

                                    <div v-else-if="notificationdata.length > 0">
                                        <NoticeBoard :notification="notificationdata" :key="refreshKey"
                                            :pageName="route.query.page_name" style="color: #2A3335;" />
                                    </div>

                                    <div v-else-if="archivedata.length > 0">
                                        <Archive :archive="archivedata" :key="refreshKey"
                                            :pageName="route.query.page_name" style="color: #2A3335;" />
                                    </div>
                                    
                                    <!-- whoswhodata -->
                                    <div v-if="showWhosWho">
                                        <WhosWho />
                                    </div>
                                    <!-- contact us  -->
                                    <div v-if="contactus">
                                        <ContactUs :data="contactUsData" />
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                    </div>
                    <div class="col-30">
                        <!-- LeftSide Menu Data Content -->
                        <LeftMenu></LeftMenu>
                    </div>
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
import bgImage from '../assets/images/msrls.jpg';
import Archive from '../components/Achive.vue'
import PhotoGallery from '../components/PhotoGallery.vue';
import NoticeBoard from '../components/NoticeBoard.vue';
import FAQS from '../components/FAQ.vue';
import WhosWho from '../components/WhosWho.vue';
import ContactUs from '../components/ContactUs.vue';
import LeftMenu from './LeftMenu.vue';
const route = useRoute();
const isLoading = ref(true);
const contactUsData = ref(null);
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
const archivedata = ref([]);
const showWhosWho = ref(false);
const refreshKey = ref(0); // Used to force re-render on data change
const contactus = ref(false);
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
        showWhosWho.value = null; // Set showWhosWho
        contactus.value = null;

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
                    response = await axios.get('/get_galleries', {
                        params: { flag: 'A' }
                    });
                    gallerydata.value = response.data.length > 0 ? response.data : [];
                    break;

                case "Notice Board":
                    // response = await axios.get(`/get_notifications`);
                    response = await axios.get('/get_notifications', { params: { flag: 'A' } });
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

                case "Contact Us":
                    contactus.value = true;
                    response = await axios.get(`/get_page_content/${route.params.id}`);
                    //  contactUsData.value = response.data|| null;
                    if (response.data) {
                        pageContent.value = response.data.content || '';
                    }
                    break;

                case "Archive Data":
                    response = await axios.get(`/get_archivedata`);
                    if (response.data) {
                        archivedata.value = response.data;
                    }
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
});
</script>

<style scoped>
.content {
    padding: 20px;
}
</style>
