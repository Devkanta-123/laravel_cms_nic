<template>
     <header class="transparent-header" >
    <div class="tg-header__top">
        <div class="container custom-container">
            <div class="row">
                <div class="col-lg-6">
                    <ul class="tg-header__top-info left-side list-wrap">
                        <li>Government Of India</li>
                        <li><i class="fa-solid fa-arrow-down"></i><a href="#" @click="scrollToBottom">Skip to main
                                content</a></li>
                        <li><i class="fa-solid fa-eye-slash"></i><a href="#">Screen ReaderAccess</a></li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <ul class="tg-header__top-right list-wrap">
                        <li>
                            <select v-model="language" @change="handleLanguageChanged" id="language-selector">
                                <option value="English">English</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Khasi">Khasi</option>
                            </select>
                        </li>

                        <li><a href="#" @click="increaseFontSize">A+</a></li>
                        <li><a href="#" @click="decreaseFontSize">A-</a></li>
                        <li><a href="#" @click="resetFontSize">A</a></li>
                        <li><a href="#" @click="toggleContrastMode">Contrast</a></li>
                        <!-- <li class="header-btn">
                                <div
                                    class="dark-light-switcher d-flex justify-content-center align-items-center shadow rounded-3">
                                    <i class="theme-icon fas fa-sun"></i>
                                    <i class="theme-icon fas fa-moon"></i>
                                </div>
                            </li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div id="sticky-header" class="tg-header__area tg-header__area-five">
        <div class="row">
            <div class="col-12">
                <div class="tgmenu__wrap">
                    <nav class="tgmenu__nav">
                        <div>
                            <a href="page/1">
                                <img :src="getLogoUrl()" alt="Logo" style="height: 100px; width: 250px; margin-left: 30%;" />
                            </a>
                        </div>
                        <!-- Dynamic Menu -->
                        <div class="tgmenu__navbar-wrap tgmenu__main-menu d-none d-lg-flex">
                            <ul class="navigation">
                                <li v-for="item in sortedMenuItems" :key="item.id" :class="{
                                    'menu-item': !item.submenus || !item.submenus.length,
                                    'menu-item-has-children': item.submenus && item.submenus.length,
                                }">
                                    <!-- Primary menu link -->
                                    <router-link v-if="!item.submenus || !item.submenus.length"
                                        :to="{ name: 'Page', params: { id: item.id } }"
                                        :style="{ fontSize: menuFontSize + 'px' }">
                                        {{ getMenuItemName(item) }}
                                    </router-link>

                                    <!-- Parent menu link -->
                                    <a v-else href="#" @click.prevent :style="{ fontSize: menuFontSize + 'px' }">
                                        {{ getMenuItemName(item) }}
                                    </a>

                                    <!-- Submenus -->
                                    <ul v-if="item.submenus && item.submenus.length" class="sub-menu">
                                        <li v-for="subItem in sortedSubmenus(item)" :key="subItem.id">
                                            <router-link v-if="!subItem.submenus || !subItem.submenus.length"
                                                :to="{ name: 'Page', params: { id: subItem.id } }"
                                                :style="{ fontSize: menuFontSize + 'px' }">
                                                {{ getMenuItemName(subItem) }}
                                            </router-link>

                                            <a v-else href="#" @click.prevent
                                                :style="{ fontSize: menuFontSize + 'px' }">
                                                {{ getMenuItemName(subItem) }}
                                            </a>

                                            <!-- Nested submenus -->
                                            <ul v-if="subItem.submenus && subItem.submenus.length" class="sub-menu">
                                                <li v-for="subSubItem in sortedSubmenus(subItem)" :key="subSubItem.id">
                                                    <router-link :to="{ name: 'Page', params: { id: subSubItem.id } }"
                                                        :style="{ fontSize: menuFontSize + 'px' }">
                                                        {{ getMenuItemName(subSubItem) }}
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

</template>
<script setup>
import { ref, onMounted, defineProps, provide, computed, watch, inject } from "vue";
import axios from "axios";
const menuItems = ref([]);
const header = ref([]);
const isLoading = ref(true);
const language = inject("language");
provide("language", language); // Provide language to all child components
const menuFontSize = ref(17); // Example font size

const props = defineProps({
    id: {
        type: String,
    },
    language: String, // Receive language as a prop

});
console.log("PropsID", props.id);
//alert(props.id);
const handleLanguageChanged = () => {
    console.log('Language changed to:', language.value);
    fetchMenuItems();
};

const fetchMenuItems = async () => {
    try {
        const response = await axios.get("/get_mainmenus");
        menuItems.value = response.data;
    } catch (error) {
        console.error("Failed to fetch menu items:", error);
    }
};

// Watch for changes in language and update menu
watch(language, (newLanguage) => {
    console.log("Language changed to:", newLanguage);
    fetchMenuItems();
});
// Sorting function for menuItems
const sortedMenuItems = computed(() => {
    return menuItems.value.slice().sort((a, b) => a.order - b.order);
});
// Sorting function for submenus
const sortedSubmenus = (item) => {
    return item.submenus ? item.submenus.slice().sort((a, b) => a.order - b.order) : [];
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



const checkScrollPosition = () => {
    showScrollButton.value = window.scrollY > 100;
};

onMounted(async () => {
    try {
        await Promise.all([fetchMenuItems(), fetchHeader(), window.addEventListener('scroll', checkScrollPosition)]);
    } finally {
        isLoading.value = false;
    }
});
const increaseFontSize = () => {
    debugger;
    menuFontSize.value += 1; // Increase font size
};

const decreaseFontSize = () => {
    if (menuFontSize.value > 10) {
        menuFontSize.value -= 1; // Decrease font size but keep it above 10px
    }
};

const resetFontSize = () => {
    menuFontSize.value = 16; // Reset to default font size
};
const toggleContrastMode = () => {
    highContrastMode.value = !highContrastMode.value;
    document.documentElement.classList.toggle(
        "high-contrast",
        highContrastMode.value
    );
};
const scrollToBottom = () => {
    window.scrollTo({
        top: document.documentElement.scrollHeight,
        behavior: 'smooth', // Ensures smooth scrolling
    });
};
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

/* Style for the sub-submenu */
.sub-menu .sub-menu {
    margin-left: 13px;
    /* Adjust the value as needed to move it further right */
    top: 0;
    /* Keep it aligned vertically */
}

.brand__area-eight {
    max-height: 150px;
    /* Adjust this value as needed to reduce the width */
    margin: 0 auto;
    /* Center the div within its parent container */
}


.font-size-controls {
    list-style: none;
    display: flex;
    gap: 10px;
    padding: 0;
    margin-bottom: 15px;
}

.font-size-controls li a {
    text-decoration: none;
    cursor: pointer;
    font-size: 1rem;
}
</style>
