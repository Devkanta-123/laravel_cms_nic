<template>
    <br><br><br><br>
    <section class="breadcrumb__area breadcrumb__bg"
        :style="{ backgroundImage: `url(${bgImage})`, marginTop: '-130px' }">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb__content">
                        <h6 class="title text-white">{{ decrypt(route.query.page_name || '') }}</h6>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active text-white">
                                    <router-link to="/page/1">Home</router-link>
                                </li>
                                <li class="breadcrumb-item active text-white" aria-current="page">{{
                                    decrypt(route.query.page_name || '') }}</li>
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
                                    <!-- <div v-if="pageContent" v-html="pageContent" class="aos-init aos-animate"
                                        style="color: #2A3335;"></div> -->
                                    <div v-if="pageContent" v-html="pageContent" @click="handleEncryptedLinkClick"
                                        class="aos-init aos-animate" style="color: #2A3335;"></div>


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

                                    <div v-if="showWhosWho">
                                        <WhosWho :data="whoswhoData" />
                                    </div>

                                    <div v-if="contactus">
                                        <ContactUs :data="contactUsData" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-30">
                        <LeftMenu />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <component v-if="activeComponent" :is="activeComponent" :language="language" />
</template>

<script setup>
import { ref, onMounted, inject, watch, provide, shallowRef } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import bgImage from '../assets/images/msrls.jpg';
import Archive from '../components/Achive.vue';
import PhotoGallery from '../components/PhotoGallery.vue';
import NoticeBoard from '../components/NoticeBoard.vue';
import FAQS from '../components/FAQ.vue';
import WhosWho from '../components/WhosWho.vue';
import ContactUs from '../components/ContactUs.vue';
import LeftMenu from './LeftMenu.vue';
import { decrypt } from '../assets/js/cryptoUtil.js'
import { encrypt } from '../assets/js/cryptoUtil.js'
const route = useRoute();
const router = useRouter();
const isHome = ref(false);
const props = defineProps({
    language: String,
    id: String,
    pageName: String
});
const language = inject("language");
provide("language", language);
const pageContent = ref('');
const gallerydata = ref([]);
const noticeboarddata = ref([]);
const notificationdata = ref([]);
const archivedata = ref([]);
const contactUsData = ref(null);
const whoswhoData = ref([])
const showWhosWho = ref(false);
const contactus = ref(false);
const activeComponent = shallowRef(null);
const refreshKey = ref(0);
const isLoading = ref(true);
const pageName = ref('');
const cacheKey = 'whoswhoData'
const timestampKey = 'whoswhoTimestamp'
const cacheDuration = 1000 * 60 * 5 // 5 mins
async function fetchWhosWho() {
    const now = Date.now()
    const cachedData = localStorage.getItem(cacheKey)
    const cachedTimestamp = localStorage.getItem(timestampKey)

    if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
        console.log("Using cached WhosWho data")
        whoswhoData.value = JSON.parse(cachedData)
        return
    }

    try {
        const response = await axios.get("/get_whoswho", { params: { flag: 'A' } })
        whoswhoData.value = response.data || []
        localStorage.setItem(cacheKey, JSON.stringify(response.data))
        localStorage.setItem(timestampKey, now.toString())
    } catch (error) {
        console.error("Failed to fetch WhosWho data:", error)
    }
}
const fetchPageContent = async () => {
    try {
        debugger;
        isLoading.value = true;

        const decryptedId = decrypt(route.params.id || '');
        const pageName = decrypt(route.query.page_name || '');

        isHome.value = (pageName === 'Home');

        if (isHome.value) {
            // Redirect to default Home page with unencrypted route
            router.push('/page/1');
            return;
        }

        const cardId = route.query.cardid;

        // Reset state
        pageContent.value = '';
        gallerydata.value = [];
        noticeboarddata.value = [];
        notificationdata.value = [];
        archivedata.value = [];
        contactus.value = false;
        showWhosWho.value = false;
        contactUsData.value = null;
        activeComponent.value = undefined;

        let response;

        if (cardId) {
            response = await axios.get(`/get_page_content/${cardId}`);
            pageContent.value = response.data?.content || '';
            return;
        }

        switch (pageName) {
            case 'Gallery':
                [response] = await Promise.all([
                    axios.get('/get_galleries', { params: { flag: 'A' } })
                ]);
                gallerydata.value = response.data || [];
                break;

            case 'Notice Board':
                [response] = await Promise.all([
                    axios.get('/get_notifications', { params: { flag: 'A' } })
                ]);
                noticeboarddata.value = response.data || [];
                break;

            case 'Newsletter':
            case 'Recruitments':
            case 'Tenders':
            case 'Notification':
                [response] = await Promise.all([
                    axios.get(`/get_notificationbycategory/${pageName}`)
                ]);
                notificationdata.value = response.data || [];
                break;

            case 'FAQ':
                activeComponent.value = FAQS;
                break;

            case 'WhosWho':
                await fetchWhosWho()
                showWhosWho.value = true
                break
            case 'Contact Us':
                contactus.value = true;
                response = await axios.get(`/get_page_content/${decryptedId}`);
                pageContent.value = response.data?.content || '';
                contactUsData.value = response.data || null;
                break;

            case 'Archive Data':
                [response] = await Promise.all([
                    axios.get('/get_archivedata')
                ]);
                archivedata.value = response.data || [];
                break;

            // default:
            //     response = await axios.get(`/get_page_content/${decryptedId}`);
            //     pageContent.value = response.data?.content || '';
            //     response = await axios.get(`/get_page_content/${decryptedId}`);
            //     let rawContent = response.data?.content || '';
            //     pageContent.value = replacePageLinksWithEncrypted(rawContent);

            default:
                response = await axios.get(`/get_page_content/${decryptedId}`);
                let rawContent = response.data?.content || '';
                pageContent.value = replacePageLinksWithEncrypted(rawContent);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth' // optional for smooth animation
                });
                break;

        }

    } catch (error) {
        console.error('Error fetching page content:', error);
        pageContent.value = '';
    } finally {
        isLoading.value = false;
    }
};


function replacePageLinksWithEncrypted(content) {
    return content.replace(
        /<a\s+href="\/page\/(\d+)">([^<]+)<\/a>/g,
        (match, id, text) => {
            const encryptedId = encrypt(id)
            // Add a data attribute so we can detect it in click handler
            return `<a href="/page/${encryptedId}" data-encrypted-id="${encryptedId}">${text}</a>`
        }
    )
}

// Click handler to intercept link navigation
function handleEncryptedLinkClick(event) {
    const target = event.target.closest('a[data-encrypted-id]')
    if (target) {
        event.preventDefault()
        const encryptedId = target.getAttribute('data-encrypted-id')
        router.push({ name: 'Page', params: { id: encryptedId } })
    }
}


// Watch for route param or language change
watch(
    () => [route.params.id, route.query.page_name, props.language],
    () => {
        isLoading.value = true;
        fetchPageContent();
    },
    { immediate: true }
);
onMounted(() => {
    fetchPageContent();
});
</script>

<style scoped>
.content {
    padding: 20px;
}
</style>
