<template>
    <Loader v-if="isLoading" />
    <div class="app-wrapper">
        <nav
            class="main-header navbar navbar-expand-lg navbar-light bg-light pt-0"
        >
            <!-- Controls Row -->

            <div
                class="navbar-controls-wrapper bg-light d-flex justify-content-between py-2 container"
            >
                <div class="navbar-controls d-flex">
                    <a href="#main-content" class="me-1">
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-down-square-fill"></i>
                            Skip to main content
                        </button>
                    </a>
                    <!-- Language Switcher -->
                    <select
                        v-model="language"
                        @change="handleLanguageChanged"
                        class="form-select form-select-sm w-auto"
                    >
                        <option value="English">English</option>
                        <option value="Hindi">Hindi</option>
                        <option value="Khasi">Khasi</option>
                    </select>
                </div>
                <!-- Font Controls -->
                <div class="d-flex justify-content-end">
                    <button
                        class="btn btn-outline-secondary btn-sm"
                        @click="increaseFontSize"
                    >
                        A+
                    </button>
                    <button
                        class="btn btn-outline-secondary btn-sm"
                        @click="decreaseFontSize"
                    >
                        A-
                    </button>
                    <button
                        class="btn btn-outline-secondary btn-sm"
                        @click="resetFontSize"
                    >
                        Reset
                    </button>
                    <!-- High Contrast Button -->
                    <button
                        class="btn btn-outline-secondary btn-sm"
                        @click="toggleContrastMode"
                    >
                        High Contrast
                    </button>
                </div>
            </div>
            <!-- Logo and Website Name Row -->
            <div class="w-100 bg-white">
                <div
                    class="logo-and-name-wrapper container d-flex align-items-center justify-content-between py-2 px-3"
                >
                    <img :src="getLogoUrl()" alt="Logo" class="navbar-logo logo" />
                    <span class="website-name text-secondary fw-bold">{{
                        header.website_short
                    }}</span>
                </div>
            </div>
            <!-- Menu Items Row -->
            <div class="menu-item-row p-0 m-0 w-100">
                <div class="container w-100">
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarNav"
                        aria-controls="navbarNav"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li
                                v-for="item in menuItems"
                                :key="item.id"
                                :class="{
                                    'nav-item': true,
                                    dropdown:
                                        item.submenus && item.submenus.length,
                                }"
                            >
                                <router-link
                                    v-if="item.submenus && item.submenus.length"
                                    class="nav-link dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    :to="{
                                        name: 'Page',
                                        params: { id: item.id },
                                    }"
                                >
                                    {{ getMenuItemName(item) }}
                                </router-link>
                                <router-link
                                    v-else
                                    class="nav-link text-light"
                                    :to="{
                                        name: 'Page',
                                        params: { id: item.id },
                                    }"
                                >
                                    {{ getMenuItemName(item) }}
                                </router-link>
                                <ul
                                    v-if="item.submenus && item.submenus.length"
                                    class="dropdown-menu"
                                >
                                    <li
                                        v-for="subItem in item.submenus"
                                        :key="subItem.id"
                                    >
                                        <router-link
                                            class="dropdown-item"
                                            :to="{
                                                name: 'Page',
                                                params: { id: subItem.id },
                                            }"
                                        >
                                            {{ getMenuItemName(subItem) }}
                                        </router-link>
                                    </li>
                                    <li v-if="item.submenus.length > 1">
                                        <hr class="dropdown-divider" />
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="p_content-wrapper">
            <div v-if="props.id == 1" class="content pt-5" id="main-content">
                <Carousel></Carousel>
                <HomeParagraph />
                <br /><br /><br />
            </div>

            <div v-if="props.id != 1" class="content" id="main-content">
                <router-view></router-view>
                <br />
                <Footer></Footer>
            </div>
        </div>
    </div>
</template>

<script setup>
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

const logos = [
    { src: "/storage/logo/rti.jpg", alt: "Logo 1" },
    { src: "/storage/logo/megpgrams.jpg", alt: "Logo 2" },
    { src: "/storage/logo/meghalayagovin.png", alt: "Logo 3" },
];

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

<style scoped>
.app-wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.main-header {
    display: flex;
    flex-direction: column;
    padding: 0;
}

.navbar-controls-wrapper {
    width: 100vw;
    padding: 0.5rem 1rem;
    box-sizing: border-box;
    padding-right: 0;
}

.navbar-logo {
    width: 40%;
}

.navbar-nav .nav-item {
    position: relative;
    margin-right: 15px;
}

.navbar-nav .nav-item:not(:last-child)::after {
    content: "";
    position: absolute;
    right: -7px;
    top: 0;
    height: 100%;
    width: 1px;
    background-color: rgba(255, 255, 255, 0.5);
}

.navbar-nav .nav-item:hover {
    background-color: rgb(0, 0, 0);
}

.navbar-nav .nav-item .nav-link:hover {
    text-decoration: none;
}

.menu-item-row {
    background-color: #00446d;
}

.website-name {
    font-family: Roboto;
    font-size: xx-large;
}
</style>
