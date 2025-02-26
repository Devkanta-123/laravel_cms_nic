<template>
    <Loader v-if="isLoading" />
    <br>
    <br>
    <br>
    <div>
        <!-- Dynamic component rendering -->
        <component v-if="currentComponent && currentComponent.name !== 'default'" :is="currentComponent.component"
            v-bind="currentComponent.props" />

        <!-- Fallback block for unmatched IDs -->
        <div v-else class="container">
            <div class="content" v-html="pageContent"></div>
        </div>

    </div>
</template>
<script setup>
import { mapState } from 'vuex';
import { ref, onMounted, inject, watch, computed } from "vue";
import { useRoute } from "vue-router";
import Carousel from "../components/Carousel.vue";
import PhotoGallery from "../components/PhotoGallery.vue";
import AboutUs from "../components/AboutUs.vue";
import ContactUs from "../components/ContactUs.vue";
import News from "../components/News.vue";
import NewsDetails from "../components/NewsDetails.vue";
import Vissions from "../components/Vissions.vue";
import Services from "../components/Services.vue";
import Scope from "../components/Scope.vue";
import ProgramsAndSchemes from "../components/ProgramsAndSchemes.vue";
import Cards from "../components/Cards.vue";
import Whos from "../components/Whos.vue";
import HomeParagraph from "../components/HomeParagraph.vue";
import Footer from "./Footer.vue";
import LeftMenu from "./LeftMenu.vue";
import Loader from "../components/Loader.vue";


// Determine the current component
const currentComponent = computed(() => {
    debugger;
    const id = route.params.id;

    switch (id) {
        case '1':
            return { component: Carousel };
        case '2':
            return { component: PhotoGallery };
        case '3':
            return { component: AboutUs };
        case '5':
            return { component: Services };
        case '7':
            return { component: ContactUs };
        case '8':
            return { component: Whos };
        case '9':
            return { component: Vissions };
        case '10':
            return { component: Scope };
        case '11':
            return { component: ProgramsAndSchemes };
        case '13':
            return { component: News };
        case '14':
            return {
                component: NewsDetails,
                props: { newsId: props.newsId }, // Pass the newsId prop dynamically
            };
        default:
            return { component: 'default' }; // Fallback identifier
    }
});

const route = useRoute();
const isLoading = ref(true);
const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    newsId: {
        type: String,
        required: false,
        default: null
    }
});
// alert(" Page PropsID : " + props.id);
console.log("News ID : " + props.newsId);
const currentLanguage = inject("language");
const pageTitle = ref("");
const pageContent = ref("");
const logos = [
    { src: "storage/logo/rti.jpg", alt: "Logo 1" },
    { src: "storage/logo/megpgrams.jpg", alt: "Logo 2" },
    { src: "storage/logo/meghalayagovin.png", alt: "Logo 3" },
];

const fetchPageContent = async () => {
    try {
        debugger;
        const response = await axios.get(
            `/get_page_content/${route.params.id}`
        );
        pageTitle.value = response.data.title;
        pageContent.value = response.data.content;
        console.log("Page Content Data", pageContent.value);

    } catch (error) {
        console.error("Failed to fetch page content:", error);
    }
};


onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 300);
    console.log("On Mounted of Page", props.id);
    fetchPageContent();
});

watch(
    () => route.params.id,
    () => {
        setTimeout(() => {
            isLoading.value = false;
        }, 500);
        // fetchPageContent();
    }
);

</script>

<style scoped>
.content {
    padding: 20px;
    margin-left: 250px;
}
</style>
