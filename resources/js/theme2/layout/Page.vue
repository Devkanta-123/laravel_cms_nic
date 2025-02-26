<template>
    <Loader v-if="isLoading" />

    <br /><br /><br /><br />
    <div v-if="route.params.id == 1 || route.params.id == null">
        <Carousel></Carousel>
        <Cards />
        <br /><br /><br />
        <!-- <HomeParagraph /> -->
    </div>
    <div v-else class="container">
        <LeftMenu />
        <div class="content" v-html="pageContent"></div>
    </div>
</template>
<script setup>
import { ref, onMounted, inject, watch } from "vue";
import { useRoute } from "vue-router";
import Carousel from "../components/Carousel.vue";
import Cards from "../components/Cards.vue";
import HomeParagraph from "../components/HomeParagraph.vue";
import Footer from "./Footer.vue";
import LeftMenu from "./LeftMenu.vue";
import Loader from "../../components/Loader.vue";

const route = useRoute();
const isLoading = ref(true);
const props = defineProps({
    id: {
        type: String,
        required: true,
    },
});

const footer_logos = [
    { src: "/storage/logo/rti.jpg", alt: "Logo 1" },
    { src: "/storage/logo/megpgrams.jpg", alt: "Logo 2" },
    { src: "/storage/logo/meghalayagovin.png", alt: "Logo 3" },
];

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
        const response = await axios.get(
            `/get_page_content/${route.params.id}`
        );
        pageTitle.value = response.data.title;
        pageContent.value = response.data.content;
    } catch (error) {
        console.error("Failed to fetch page content:", error);
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
    () => route.params.id,
    () => {
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
    margin-left: 250px;
}
</style>
