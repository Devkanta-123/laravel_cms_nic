<template>
    <Loader v-if="isLoading" />
    <div class="app-wrapper">
        <nav class="main-header navbar navbar-expand-lg navbar-light pt-0 bg-custom-2"
            :class="{ 'high-contrast': highContrastMode }">
            <!-- Controls Row -->
            <div class="navbar-controls-wrapper d-flex justify-content-end">
                <div class="navbar-controls d-flex align-items-center">
                    <a href="#main-content"><button class="btn bg-white btn-sm border">
                            Skip to main content
                        </button>
                    </a>

                    <!-- Language Selector -->
                    <select v-model="language" @change="handleLanguageChanged"
                        class="form-control custom-select w-auto me-3">
                        <option value="English">English</option>
                        <option value="Hindi">Hindi</option>
                        <option value="Khasi">Khasi</option>
                    </select>

                    <!-- Font Size Controls -->
                    <div class="font-size-controls d-flex align-items-center me-1">
                        <button class="btn bg-white btn-sm border me-1" @click="increaseFontSize">
                            A+
                        </button>
                        <button class="btn bg-white btn-sm border me-1" @click="decreaseFontSize">
                            A-
                        </button>
                        <button class="btn bg-white btn-sm border" @click="resetFontSize">
                            Reset
                        </button>
                    </div>

                    <!-- High Contrast Button -->
                    <button class="btn bg-white btn-sm border" @click="toggleContrastMode">
                        High Contrast
                    </button>
                </div>
            </div>

            <div class="container-fluid">
                <img :src="getLogoUrl()" alt="Logo" class="navbar-logo pt-3" />
                <span class="navbar-text navbar-brand text-white">
                    {{ header.website_short }}
                </span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-md-auto gap-2">
                        <li v-for="item in menuItems" :key="item.id" :class="{
                            'nav-item rounded fw-bold': true,
                            dropdown: item.submenus && item.submenus.length,
                        }">
                            <!-- Menu Item with Submenu -->
                            <router-link v-if="item.submenus && item.submenus.length" class="nav-link dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                :to="{ name: 'Page', params: { id: item.id } }">
                                {{ getMenuItemName(item) }}
                            </router-link>

                            <!-- Regular Menu Item -->
                            <router-link v-else class="nav-link" :to="{ name: 'Page', params: { id: item.id } }">
                                {{ getMenuItemName(item) }}
                            </router-link>

                            <!-- Dropdown Menu for Submenu Items -->
                            <ul v-if="item.submenus && item.submenus.length" class="dropdown-menu dropdown-menu-end"
                                aria-labelledby="navbarDropdown">
                                <li v-for="subItem in item.submenus" :key="subItem.id">
                                    <router-link class="dropdown-item" :to="{
                                        name: 'Page',
                                        params: { id: subItem.id },
                                    }">
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
        </nav>

        <div class="p_content-wrapper">
            <div v-if="props.id == 1" class="content" id="main-content">
                <Carousel></Carousel>
                <Cards />
                <br /><br /><br />
                <!-- <HomeParagraph /> -->
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
console.log("PropsID", props.id);
//alert(props.id);

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
    padding: 0.5rem 1rem;
}

.navbar-controls-wrapper {
    width: 100vw;
    background-color: #d3d3d3;
    padding: 0.5rem 1rem;
}

.navbar-controls {
    display: flex;
    align-items: center;
    max-width: 1200px;
}

.navbar-controls select {
    height: 1.9rem;
    font-size: 0.8rem;
}

.high-contrast {
    background-color: #000;
    color: #fff;
}

.navbar-nav .nav-item:hover {
    background-color: rgb(211, 211, 211);
}

.navbar-nav .nav-item .nav-link:hover {
    text-decoration: none;
}

.navbar-logo {
    width: 7%;
}

.navbar-text {
    font-family: "Times New Roman", Times, serif;
}

.bg-custom-2 {
    background-image: linear-gradient(15deg, #13547a 0%, #80d0c7 100%);
}
</style>
