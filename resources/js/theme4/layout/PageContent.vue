<template>
    <!-- <Loader v-if="isLoading" /> -->
      <!-- Spinner Start -->
      <!-- <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div> -->
    <!-- Spinner End -->

     <!-- Topbar Start -->
    <div class="container-fluid py-2 d-none d-lg-flex">
        <div class="container">
            <div class="d-flex justify-content-between">
                <!-- Controls Row -->

            <div class="navbar-controls-wrapper bg-light d-flex justify-content-between py-2 container">
                <div class="navbar-controls d-flex">
                    <a href="#main-content" class="me-1">
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-down-square-fill"></i>
                            Skip to main content
                        </button>
                    </a>
                    <!-- Language Switcher -->
                    <select v-model="language" @change="handleLanguageChanged"
                        class="form-select form-select-sm w-auto">
                        <option value="English">English</option>
                        <option value="Hindi">Hindi</option>
                        <option value="Khasi">Khasi</option>
                    </select>
                </div>
                <!-- Font Controls -->
                <div class="d-flex justify-content-end">
                    <button class="btn btn-outline-secondary btn-sm" @click="increaseFontSize">
                        A+
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" @click="decreaseFontSize">
                        A-
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" @click="resetFontSize">
                        Reset
                    </button>
                    <!-- High Contrast Button -->
                    <button class="btn btn-outline-secondary btn-sm" @click="toggleContrastMode">
                        High Contrast
                    </button>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    
    <!-- Brand Start --> 
    <div class="container-fluid bg-primary text-white pt-4 pb-5 d-none d-lg-flex">
        <div class="container pb-2">
            <div class="d-flex align-items-center justify-content-between">

                 <div
                    class="logo-and-name-wrapper container d-flex align-items-center justify-content-between py-2 px-3">
                    <img :src="getLogoUrl()" alt="Logo" class="navbar-logo logo" />
                    <span class="website-name text-secondary fw-bold">{{
                        header.website_short
                        }}</span>
                </div>
                 <a href="index.html" class="h1 text-white mb-0">Lab<span class="text-dark">sky</span></a>
                <div class="d-flex">
                    <i class="bi bi-envelope fs-2"></i>
                    <div class="ms-3">
                        <h5 class="text-white mb-0">Mail Now</h5>
                        <span>info@example.com</span>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <!-- Brand End -->


    <!-- Navbar Start -->
    <div class="container-fluid sticky-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-lg-0 px-lg-3">
                <a href="index.html" class="navbar-brand d-lg-none">
                    <h1 class="text-primary m-0">Lab<span class="text-dark">sky</span></h1>
                </a>
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav">
                        <a href="index.html" class="nav-item nav-link active">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="service.html" class="nav-item nav-link">Services</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light m-0">
                                <a href="feature.html" class="dropdown-item">Features</a>
                                <a href="team.html" class="dropdown-item">Our Team</a>
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                                <a href="appoinment.html" class="dropdown-item">Appoinment</a>
                                <a href="404.html" class="dropdown-item">404 Page</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contact</a>
                        
                    </div>
                    <div class="ms-auto d-none d-lg-flex">
                        <a class="btn btn-sm-square btn-primary ms-2" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-sm-square btn-primary ms-2" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-sm-square btn-primary ms-2" href=""><i class="fab fa-linkedin-in"></i></a>
                        <a class="btn btn-sm-square btn-primary ms-2" href=""><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

     
</template>

<script setup>
import '../assets/css/style.css';
import '../assets/css/bootstrap.min.css';
import '../assets/lib/animate/animate.min.css';
import '../assets/lib/owlcarousel/assets/owl.carousel.min.css';

import { ref, onMounted, defineProps, defineEmits, inject, provide } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import Footer from "./Footer.vue";
import Carousel from "../components/Carousel.vue";
import Cards from "../components/Cards.vue";
import HomeParagraph from "../components/HomeParagraph.vue";
import Loader from "../../components/Loader.vue";

const menuItems = ref([]);
const header = ref([]);
const highContrastMode = ref(false);
const language = ref("English");
const isLoading = ref(true);

provide("language", language);

const props = defineProps({
    id: {
        type: String,
    },
});


const fetchMenuItems = async () => {
    try {
        const response = await axios.get("/get_mainmenus");
        menuItems.value = response.data;
    } catch (error) {
        console.error("Failed to fetch menu items:", error);
    }
};

const fetchHeader = async () => {
    try {
        const response = await axios.get("/get_website_description");
        header.value = response.data;
    } catch (error) {
        console.error("Failed to fetch header:", error);
    }
};

const getMenuItemName = (item) => {
    switch (language.value) {
        case "English":
            return item.menu_name;
        case "Hindi":
            return item.hindi_name;
        case "Khasi":
            return item.khasi_name;
        default:
            return item.menu_name;
    }
};

const getLogoUrl = () => {
    return `/storage/${header.value.logo}`;
};

const adjustFontSize = inject("adjustFontSize");
const resetFontSize = inject("resetFontSize");

const increaseFontSize = () => {
    adjustFontSize(1);
};

const decreaseFontSize = () => {
    adjustFontSize(-1);
};

const resetFontSizeFunc = () => {
    resetFontSize();
};

const toggleContrastMode = () => {
    highContrastMode.value = !highContrastMode.value;
    document.documentElement.classList.toggle(
        "high-contrast",
        highContrastMode.value
    );
};

onMounted(async () => {
    try {
        await Promise.all([fetchMenuItems(), fetchHeader()]);
    } finally {
        isLoading.value = false;
    }
});
</script>
