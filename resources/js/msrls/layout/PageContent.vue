<template>
    <Loader v-if="isLoading" />

    <head>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    </head>
    <header class="header header-1 header-3 sticky-active" v-if="props.id != 1">
        <div class="top-bar">
            <div class="container">
                <div class="top-bar-inner">
                    <div class="top-bar-left">
                        <ul class="top-bar-list">
                            <li><i class="fa-solid fa-arrow-down"></i><a href="#" @click="scrollToBottom">Skip to main
                                    content</a></li>
                            <li><i class="fa-solid fa-eye-slash"></i><a href="#">Screen ReaderAccess</a></li>
                            <li>
                                <select v-model="language" @change="handleLanguageChanged" id="language-selector">
                                    <option value="English">English</option>
                                    <option value="Hindi">Hindi</option>
                                    <option value="Khasi">Khasi</option>
                                </select>
                            </li>
                            <!-- <li><i class="fa-regular fa-clock"></i><span>Mon - Sat: 8:00 - 15:00</span></li> -->
                        </ul>
                    </div>
                    <div class="top-bar-right">
                        <!-- <div class="register-box">
                            <div class="icon"><i class="fa-regular fa-user"></i></div>
                            <a href="contact.html">Login / Register</a>
                        </div> -->
                        <div class="top-social-wrap">
                            <p style="color: #fff;margin-top:5%;">Text Resize</p>
                            <ul class="social-list">
                                <!-- <button class="btn btn-sm border" @click="increaseFontSize">A++</button>
              <button class="btn btn-sm border" @click="decreaseFontSize">A-</button>
              <button class="btn btn-sm border" @click="resetFontSize">Reset</button> -->
                                <li><a href="#" @click="decreaseFontSize">A-</a></li>
                                <li><a href="#" @click="increaseFontSize">A+</a></li>
                                <li><a href="#" @click="resetFontSizeFunc">Reset</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mid-header">
            <div class="container">
                <div class="mid-header-inner">
                    <div class="header-logo d-lg-block">
                        <a href="#">
                            <img style="width:500px;" :src="getLogoUrl()" alt="Logo">
                        </a>
                    </div>
                    <div class="category-form-wrap">
                        <form class="header-form" action="#">
                            <input class="form-control" type="text" name="search" placeholder="Search here...">
                            <button class="submit ed-primary-btn"><i
                                    class="fa-solid fa-magnifying-glass"></i>Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navbar Start -->

        <div class="primary-header">
            <div class="container">
                <div class="primary-header-inner">
                    <div class="header-logo d-lg-none">
                        <a href="index.html">
                            <img src="assets/img/logo/logo-1.png" alt="Logo">
                        </a>
                    </div>
                    <div class="header-left-inner">
                        <div class="header-menu-wrap">
                            <div class="mobile-menu-items">
                                <!-- Display Menu Items -->
                                <ul class="sub-menu">
                                    <li v-for="item in sortedMenuItems" :key="item.order" :class="{
                                        'menu-item': true,
                                        'active': item.active,
                                        'menu-item-has-children': item.submenus && item.submenus.length
                                    }">
                                        <router-link v-if="item.submenus && item.submenus.length"
                                            class="menu-link dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-expanded="false" :to="{ name: 'Page', params: { id: item.id } }">
                                            {{ getMenuItemName(item) }}
                                        </router-link>

                                        <router-link v-else class="menu-link"
                                            :to="{ name: 'Page', params: { id: item.order } }">
                                            {{ getMenuItemName(item) }}
                                        </router-link>

                                        <!-- Submenu -->
                                        <ul v-if="item.submenus && item.submenus.length">
                                            <li v-for="subItem in sortedSubmenus(item)" :key="subItem.id">
                                                <router-link class="dropdown-item"
                                                    :to="{ name: 'Page', params: { id: subItem.order } }">
                                                    {{ getMenuItemName(subItem) }}
                                                </router-link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>

                            </div>
                        </div>
                        <!-- /.header-menu-wrap -->
                    </div>

                </div>
                <!-- /.primary-header-inner -->
            </div>
        </div>

        <!-- Navbar End -->
    </header>

    <!-- scrool button  -->
    <div v-if="showScrollButton" id="scrollup" class="show">
        <button id="scroll-top" class="scroll-to-top" @click="scrollToTop">
            <i class="fa-solid fa-arrow-up-long"></i>
        </button>
    </div>

    <div v-if="props.id != 1" class="content" id="main-content">
        <router-view></router-view>
        <Carousel />
    </div>
    <br>
    <!-- Paragraph News -->
    <div class="container-fluid ml-20" v-if="props.id != 1" id="main-content">
        <div class="row">
            <div class="col-xl-9 col-lg-12">
                <div class="course-details-content">
                    <div class="course-details-tab mb-1">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="tab-overview">
                                    <Paragraph :language="language" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <br>
            <!-- Paragraph News End -->
            <!-- Latest News  -->
            <div class="col-xl-3 col-lg-12" v-if="props.id != 1">
                <LatestNews :language="language" />
            </div>
            <!-- Latest New End         -->

            <!-- Documentations -->
            <section class="about-feature pt-120 pb-120 mt-40">
                <div class="container">
                    <Documentations></Documentations>
                </div>
            </section>

            <!-- Documentations End -->
            <!-- Cards -->
            <section class="features-event pt-150 pb-120">
                <!-- Pull the card section upwards by adjusting margins or padding -->
                <div class="container mt-n2"> <!-- mt-n5 will pull it upwards -->
                    <!-- Pass the cards data as a prop to the Cards component -->
                    <Cards />
                </div>
            </section>

            <!-- Cards End -->
            <!-- Footer Start -->
            <footer class="footer-section pt-120" style="background-color: black;">
                <div class="footer-top-wrap">
                    <div class="container">
                        <!-- Pass the footer content to the Footer component -->
                        <Footer />
                    </div>
                </div>
                <div class="copyright-area">
                    <div class="container">
                    </div>
                </div>
            </footer>

            <!-- Footer End -->
            <div v-if="showScrollButton" id="scrollup" class="show">
                <button id="scroll-top" class="scroll-to-top" @click="scrollToTop">
                    <i class="fa-solid fa-arrow-up-long"></i>
                </button>
            </div>
        </div>
    </div>


    <!-- Cards End -->

    <!-- Topbar End -->


    <!-- Brand Start -->
    <!-- <div class="container-fluid bg-primary text-white pt-4 pb-5 d-none d-lg-flex">
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
    </div> -->
    <!-- Brand End -->




</template>

<script setup>
import '../assets/css/bootstrap.min.css';
import '../assets/css/animate.min.css';
import '../assets/css/swiper.min.css';
import '../assets/css/main.css';
import '../assets/lib/owlcarousel/assets/owl.carousel.min.css';


import { ref, onMounted, defineProps, defineEmits, inject, provide, computed, watch, onBeforeUnmount } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import Footer from "./Footer.vue";
import Carousel from "../components/Carousel.vue";
import LatestNews from "../components/LatestNews.vue";
import Paragraph from '../components/Paragraph.vue';
import Cards from "../components/Cards.vue";
import Documentations from "../components/Documentations.vue";
import HomeParagraph from "../components/HomeParagraph.vue";
import Loader from "../components/Loader.vue";
const menuItems = ref([]);
const header = ref({ logo: null });
const footerContent = ref([]);
const highContrastMode = ref(false);
const language = ref('English');  // Default language is English
const isLoading = ref(true);
provide("language", language);
const props = defineProps({
    id: {
        type: String
    },
});
const localId = ref(props.id);

console.log("PropsID", props.id);
//alert("PropsID : " + props.id);

// Reactive variable to store header data
//scrolling to top 
// Reactive state to control the visibility of the scroll-to-top button
const showScrollButton = ref(false);

// Function to handle scrolling to the top of the page
const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};
const checkScrollPosition = () => {
    showScrollButton.value = window.scrollY > 100;
};
const handleLanguageChanged = () => {
    console.log('Language changed to:', language.value);
    fetchMenuItems();  // Optionally, refetch the menu items when language changes
};
const scrollToBottom = () => {
    window.scrollTo({
        top: document.documentElement.scrollHeight,
        behavior: 'smooth', // Ensures smooth scrolling
    });
};


// Fetch the menu items from the API
const fetchMenuItems = async () => {
    try {
        const response = await axios.get("/get_mainmenus");
        menuItems.value = response.data;  // This will automatically update sortedMenuItems because it's a computed property
    } catch (error) {
        console.error("Failed to fetch menu items:", error);
    }
};


const fetchHeader = async () => {
    try {
        const response = await axios.get("/get_website_description");
        // Store the header data in the reactive variable
        header.value = response.data;

        // Cache the logo URL in localStorage
        const logoUrl = `/storage/${header.value.logo}`;
        localStorage.setItem('logoUrl', logoUrl);
    } catch (error) {
        console.error("Failed to fetch header:", error);
    }
};

const getMenuItemName = (item) => {
    switch (language.value) {
        case 'Hindi':
            return item.hindi_name || item.menu_name;  //  For Main Menu and Sub Menu ,Fall back to default menu_name if hindi_name is null
        case 'Khasi':
            return item.khasi_name || item.menu_name;  // Fall back to default menu_name if khasi_name is null
        case 'English':
        default:
            return item.menu_name;
    }
};

// Get logo URL (either from localStorage or API)
const getLogoUrl = () => {
    // First, check if the logo URL is already cached in localStorage
    const cachedLogoUrl = localStorage.getItem('logoUrl');

    if (cachedLogoUrl) {
        // If cached, return the cached URL
        return cachedLogoUrl;
    }

    // Otherwise, fetch the header data and return the logo URL
    if (header.value.logo) {
        return `/storage/${header.value.logo}`;
    }

    return ''; // Return a fallback value if the logo is not available
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



// Methods to navigate to the next and previous images


// Sorting function for menuItems
const sortedMenuItems = computed(() => {
    return menuItems.value.slice().sort((a, b) => a.order - b.order);
});
// Sorting function for submenus
const sortedSubmenus = (item) => {
    return item.submenus ? item.submenus.slice().sort((a, b) => a.order - b.order) : [];
};
const cacheLogo = async () => {
    const cachedLogoUrl = localStorage.getItem('logoUrl');
    if (!cachedLogoUrl) {
        // Only fetch the logo if it's not cached
        await fetchHeader(); // This will cache the logo
    }
};
onMounted(async () => {
    try {
        await cacheLogo();
        await Promise.all([fetchMenuItems(), fetchHeader(), window.addEventListener('scroll', checkScrollPosition)]);
    } finally {
        setTimeout(() => {
            isLoading.value = false;
        }, 300);
    }
});
// Watch for language changes and update accordingly
watch(language, (newLang) => {
    console.log('Language changed:', newLang);
    fetchMenuItems();  // Optionally, you can refetch the data or update based on language
});
// Watch for changes in the `props.id` to update `localId` if necessary
watch(() => props.id, (newId) => {
    
    localId.value = newId;
});
</script>
<style scoped>
/* Remove default bullets and list styles */
ul {
    list-style: none;
    /* No bullets */
    margin: 0;
    /* Reset margin */
    padding: 0;
    /* Reset padding */
}

/* Prevent any content added by ::before or ::after pseudo-elements */
ul::before,
ul::after {
    content: none;
    /* No content */
}

#language-selector {
    font-size: 14px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 50px;
    background-color: white;
    width: 150px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}
</style>
