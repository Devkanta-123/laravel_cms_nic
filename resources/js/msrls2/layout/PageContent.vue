<template>
  <Loader v-if="isLoading" />

  <head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  </head>
  <div class="app-wrapper">
    <header class="transparent-header">
      <div class="tg-header__top">
        <div class="container custom-container">
          <div class="row">
            <div class="col-lg-6">
              <ul class="tg-header__top-info left-side list-wrap">
                <li>Government Of India</li>
                <li><i class="fa-solid fa-arrow-down"></i><a href="#" @click="scrollToBottom">Skip to main content</a>
                </li>
                <!-- <li><i class="fa-solid fa-eye-slash"></i><a href="#">Screen Reader Access</a></li> -->
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
                <li class="header-btn">
                  <a href="#">
                    <Accessibility></Accessibility>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div id="sticky-header" class="tg-header__area tg-header__area-five">
        <div class="row">
          <div class="col-12">
            <div class="tgmenu__wrap"></div>

            <!-- Mobile Menu -->
            <nav class="tgmenu__nav">
              <!-- Navbar Toggle Button for Mobile -->

              <!-- Hide logo when mobile view is active -->
              <!-- <div v-if="!isMobileView">
                <a href="page/1">
                  <img :src="getLogoUrl()" alt="Logo" class="logo-img" style="margin-left: auto;" /> </a>
              </div> -->

              <!-- Menu Wrapper -->
              <div
                :class="['collapse', 'navbar-collapse', 'tgmenu__navbar-wrap', 'tgmenu__main-menu', { show: isMobileMenuOpen, 'mobile-menu-left': isMobileView }]">
                <ul class="navigation">
                  <li v-for="item in sortedMenuItems" :key="item.id" :class="{
                    'menu-item': !item.submenus || !item.submenus.length,
                    'menu-item-has-children': item.submenus && item.submenus.length,
                  }">
                    <!-- <router-link :to="{ name: 'Page', params: { id: item.id } }"
                      :style="{ fontSize: menuFontSize + 'px' }">
                      {{ getMenuItemName(item) }}
                    </router-link> -->
                    <router-link :to="{ name: 'Page', params: { id: item.id }, query: { page_name: item.menu_name } }"
                      :style="{ fontSize: menuFontSize + 'px' }">
                      {{ getMenuItemName(item) }}
                    </router-link>


                    <!-- Submenus -->
                    <ul v-if="item.submenus && item.submenus.length" class="sub-menu">
                      <li v-for="subItem in sortedSubmenus(item)" :key="subItem.id">
                        <router-link
                          :to="{ name: 'Page', params: { id: subItem.id }, query: { page_name: subItem.menu_name } }"
                          :style="{ fontSize: menuFontSize + 'px' }">
                          {{ getMenuItemName(subItem) }}
                        </router-link>

                        <!-- Nested Submenus -->
                        <ul v-if="subItem.submenus && subItem.submenus.length" class="sub-menu">
                          <li v-for="subSubItem in sortedSubmenus(subItem)" :key="subSubItem.id">
                            <router-link
                              :to="{ name: 'Page', params: { id: subSubItem.id }, query: { page_name: subItem.menu_name } }"
                              :style="{ fontSize: menuFontSize + 'px' }">
                              {{ getMenuItemName(subSubItem) }}
                            </router-link>

                            <!-- Further Nested Submenus -->
                            <ul v-if="subSubItem.submenus && subSubItem.submenus.length" class="sub-menu">
                              <li v-for="subSubSubItem in sortedSubmenus(subSubItem)" :key="subSubSubItem.id">
                                <router-link
                                  :to="{ name: 'Page', params: { id: subSubSubItem.id }, query: { page_name: subItem.menu_name } }"
                                  :style="{ fontSize: menuFontSize + 'px' }">
                                  {{ getMenuItemName(subSubSubItem) }}
                                </router-link>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="mobile-nav-toggler" @click="toggleMobileMenu">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" fill="none">
                  <path
                    d="M0 2C0 0.895431 0.895431 0 2 0C3.10457 0 4 0.895431 4 2C4 3.10457 3.10457 4 2 4C0.895431 4 0 3.10457 0 2Z"
                    fill="currentcolor" />
                  <path
                    d="M0 9C0 7.89543 0.895431 7 2 7C3.10457 7 4 7.89543 4 9C4 10.1046 3.10457 11 2 11C0.895431 11 0 10.1046 0 9Z"
                    fill="currentcolor" />
                  <path
                    d="M0 16C0 14.8954 0.895431 14 2 14C3.10457 14 4 14.8954 4 16C4 17.1046 3.10457 18 2 18C0.895431 18 0 17.1046 0 16Z"
                    fill="currentcolor" />
                  <path
                    d="M7 2C7 0.895431 7.89543 0 9 0C10.1046 0 11 0.895431 11 2C11 3.10457 10.1046 4 9 4C7.89543 4 7 3.10457 7 2Z"
                    fill="currentcolor" />
                  <path
                    d="M7 9C7 7.89543 7.89543 7 9 7C10.1046 7 11 7.89543 11 9C11 10.1046 10.1046 11 9 11C7.89543 11 7 10.1046 7 9Z"
                    fill="currentcolor" />
                  <path
                    d="M7 16C7 14.8954 7.89543 14 9 14C10.1046 14 11 14.8954 11 16C11 17.1046 10.1046 18 9 18C7.89543 18 7 17.1046 7 16Z"
                    fill="currentcolor" />
                  <path
                    d="M14 2C14 0.895431 14.8954 0 16 0C17.1046 0 18 0.895431 18 2C18 3.10457 17.1046 4 16 4C14.8954 4 14 3.10457 14 2Z"
                    fill="currentcolor" />
                  <path
                    d="M14 9C14 7.89543 14.8954 7 16 7C17.1046 7 18 7.89543 18 9C18 10.1046 17.1046 11 16 11C14.8954 11 14 10.1046 14 9Z"
                    fill="currentcolor" />
                  <path
                    d="M14 16C14 14.8954 14.8954 14 16 14C17.1046 14 18 14.8954 18 16C18 17.1046 17.1046 18 16 18C14.8954 18 14 17.1046 14 16Z"
                    fill="currentcolor" />
                </svg>
              </div>
            </nav>
            <!-- End Mobile Menu -->
          </div>
        </div>
      </div>
    </header>
    <br>
    <br>
    <br>
    <div class="p_content-wrapper">
      <div v-if="currentId == '1'" class="content">
        <Carousel></Carousel>
      </div>
      <div class="brand__area-eight col-12 mb-300" v-if="currentId == '1'" style="margin-top: -60px;">
        <div class="container">
          <LatestNews :language="language" />
        </div>
      </div>


      <!-- HomeParagraph start here  -->
      <section class="about__area-five" v-if="currentId == '1'">
        <div class="container">
          <div class="row align-items-start">
            <!-- About Content -->
            <div class="col-lg-12">
              <div v-if="homepragraphdata" v-html="homepragraphdata" class="aos-init aos-animate"
                style="color: #2A3335;"></div>

              <br>
              <a href="/page/3?page_name=About+Us" class="btn btn-two custom-btn">
                Read More
              </a>
            </div>

            <!-- Notice Board -->

          </div> <!-- End .row -->
        </div> <!-- End .container -->
      </section>

      <section class="about__area-five" v-if="currentId == '1'">
        <Notifications :language="language" />
      </section>
      <!-- HomeParagraph end here  -->



      <section class="counter-area" v-if="currentId == '1'">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="counter-item">
                <div class="icon">
                  <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="content">
                  <h2 class="count"><span class="odometer odometer-auto-theme" data-count="45">
                      <div class="odometer-inside"><span class="odometer-digit"><span
                            class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                              class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                  class="odometer-value">3</span></span></span></span></span><span
                          class="odometer-digit"><span class="odometer-digit-spacer">8</span><span
                            class="odometer-digit-inner"><span class="odometer-ribbon"><span
                                class="odometer-ribbon-inner"><span
                                  class="odometer-value">5</span></span></span></span></span>
                      </div>
                    </span>+</h2>
                  <p>
                    Households Mobilised <br>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="counter-item">
                <div class="icon">
                  <i class="fa-solid fa-users"></i>
                </div>
                <div class="content">
                  <h2 class="count"><span class="odometer odometer-auto-theme" data-count="92">
                      <div class="odometer-inside"><span class="odometer-digit"><span
                            class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                              class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                  class="odometer-value">9</span></span></span></span></span><span
                          class="odometer-digit"><span class="odometer-digit-spacer">8</span><span
                            class="odometer-digit-inner"><span class="odometer-ribbon"><span
                                class="odometer-ribbon-inner"><span
                                  class="odometer-value">2</span></span></span></span></span>
                      </div>
                    </span>+</h2>
                  <p>
                    Self Help Groups
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="counter-item">
                <div class="icon">
                  <i class="fa-solid fa-user-group"></i>
                </div>
                <div class="content">
                  <h2 class="count"><span class="odometer odometer-auto-theme" data-count="19">
                      <div class="odometer-inside"><span class="odometer-digit"><span
                            class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                              class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                  class="odometer-value">1</span></span></span></span></span><span
                          class="odometer-digit"><span class="odometer-digit-spacer">8</span><span
                            class="odometer-digit-inner"><span class="odometer-ribbon"><span
                                class="odometer-ribbon-inner"><span
                                  class="odometer-value">9</span></span></span></span></span>
                      </div>
                    </span>+</h2>
                  <p>
                    Village Organisations
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="counter-item">
                <div class="icon">
                  <i class="fa-solid fa-project-diagram"></i>
                </div>
                <div class="content">
                  <h2 class="count"><span class="odometer odometer-auto-theme" data-count="25">
                      <div class="odometer-inside"><span class="odometer-digit"><span
                            class="odometer-digit-spacer">8</span><span class="odometer-digit-inner"><span
                              class="odometer-ribbon"><span class="odometer-ribbon-inner"><span
                                  class="odometer-value">2</span></span></span></span></span><span
                          class="odometer-digit"><span class="odometer-digit-spacer">8</span><span
                            class="odometer-digit-inner"><span class="odometer-ribbon"><span
                                class="odometer-ribbon-inner"><span
                                  class="odometer-value">5</span></span></span></span></span>
                      </div>
                    </span>+</h2>
                  <p>
                    Cluster Level Federations
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div v-else class="content">
        <router-view></router-view>
        <br>
      </div>

      <section class="services-area services-bg position-relative" v-if="currentId == '1'">
        <Cards :language="language" />
      </section>
      <!-- FAQ'S -->
      <!-- <section class="services__area-seven pt-120 pb-120" v-if="currentId == '1'">
        <FAQS  :language="language"/>
      </section> -->

      <div class="brand__area-two">
        <Banner />
      </div>
      <button class="scroll__top scroll-to-target open" data-target="html" @click="scrollToTop">
        <i class="fas fa-angle-up"></i>
      </button>
      <div class="footer-bottom">
        <Footer>
        </Footer>
        <div style="display: none;">
          <Page :language="language" />
        </div>
      </div>


    </div>
  </div>
</template>
<script setup>

import { ref, onMounted, defineProps, inject, provide, watchEffect, computed, onUnmounted } from 'vue';
import axios from 'axios';
import Footer from './Footer.vue';
import Carousel from '../components/Carousel.vue';
import LatestNews from '../components/LatestNews.vue';
import Loader from '../../components/Loader.vue';
import Notifications from '../components/Notifications.vue';
import Cards from '../components/Cards.vue';
import Banner from '../components/Banner.vue';
import Accessibility from '../settings/Accessibility.vue';
import Page from './Page.vue';
import { useRoute } from "vue-router";
import '../assets/css/bootstrap.min.css';
import '../assets/css/animate.min.css';
import '../assets/css/magnific-popup.css';
import '../assets/css/fontawesome-all.min.css';
import '../assets/css/flaticon.css';
import '../assets/css/odometer.css';
import '../assets/css/aos.css';
import '../assets/css/default.css';
import '../assets/css/main.css';
const menuItems = ref([]);
const header = ref([]);
const highContrastMode = ref(false);
const language = ref('English');
const isLoading = ref(true);
const route = useRoute();
const menuFontSize = ref(14); // Example font size
const homepragraphdata = ref([]);
const isMobileMenuOpen = ref(false);
const windowWidth = ref(window.innerWidth);
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
const sortedMenuItems = computed(() => {
  return menuItems.value.slice().sort((a, b) => a.order - b.order);
});

// Recursive sorting function for submenus
const sortedSubmenus = (item) => {
  console.log("Item", item);
  if (!item.submenus) return [];

  return item.submenus
    .slice()
    .sort((a, b) => a.order - b.order)
    .map((subItem) => ({
      ...subItem,
      submenus: sortedSubmenus(subItem) // Apply sorting recursively
    }));
};



// Compute sorted nested menu for mobile
// Function to toggle mobile menu visibility



const handleLanguageChanged = () => {
  console.log('Language changed to:', language.value);
  fetchMenuItems();
};
const scrollToBottom = () => {
  window.scrollTo({
    top: document.documentElement.scrollHeight,
    behavior: 'smooth', // Ensures smooth scrolling
  });
};

provide('language', language);

const currentId = ref(route.params.id || '1');
watchEffect(() => {
  currentId.value = route.params.id || '1';
  console.log("Updated Page ID:", currentId.value);
});


const props = defineProps({
  id: {
    type: String,
  }
});



// const fetchMenuItems = async () => {
//   try {
//     debugger;
//     const response = await axios.get('/get_mainmenus',);
//     menuItems.value = response.data;
//     console.log('Fetch Menu', response.data);
//   } catch (error) {
//     console.error('Failed to fetch menu items:', error);
//   }
// };

const fetchMenuItems = async () => {
  try {
    debugger;
    const cacheKey = 'menuItemsCache';
    const cacheExpiryKey = 'menuItemsCacheExpiry';

    // Retrieve cached data and expiry time
    const cachedData = localStorage.getItem(cacheKey);
    const cacheExpiry = localStorage.getItem(cacheExpiryKey);

    // Check if cache exists and is still valid
    if (cachedData && cacheExpiry && Date.now() < parseInt(cacheExpiry)) {
      menuItems.value = JSON.parse(cachedData);
      console.log('Loaded from cache:', menuItems.value);
      return;
    }


    // Fetch fresh data from API
    const response = await axios.get('/get_mainmenus');
    menuItems.value = response.data;
    console.log('Menu items:', menuItems.value);
    // Store in local storage with expiration timestamp (10 minutes)
    localStorage.setItem(cacheKey, JSON.stringify(response.data));
    localStorage.setItem(cacheExpiryKey, (Date.now() + 1 * 60 * 1000).toString());

    console.log('Fetched from API:', response.data);
  } catch (error) {
    console.error('Failed to fetch menu items:', error);
  }
};


const fetchPageContent = async () => {
  debugger;
  const response = await axios.get(`/get_page_content/${currentId.value}`);
  if (response.data) {
    debugger;
    homepragraphdata.value = response.data.content
      ? response.data.content
      : response.data.content;
  }
}


const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  });
};

const fetchHeader = async () => {
  try {
    const response = await axios.get('/get_website_description',);
    header.value = response.data;
  } catch (error) {
    console.error('Failed to fetch header:', error);
  }
};

const getMenuItemName = (item) => {

  switch (language.value) {
    case 'English':
      return item.menu_name;
    case 'Hindi':
      return item.hindi_name;
    case 'Khasi':
      return item.khasi_name;
    default:
      return item.menu_name;
  }
};

const getLogoUrl = () => {
  return `/storage/${header.value.logo}`;
};


const increaseFontSize = () => {
  menuFontSize.value += 1; // Increase font size
};

const decreaseFontSize = () => {
  if (menuFontSize.value > 10) {
    menuFontSize.value -= 1; // Decrease font size but keep it above 10px
  }
};

const resetFontSize = () => {
  menuFontSize.value = 14; // Reset to default font size
};
const toggleContrastMode = () => {
  highContrastMode.value = !highContrastMode.value;
  document.documentElement.classList.toggle(
    "high-contrast",
    highContrastMode.value
  );
};
// Function to update window width
const updateWindowWidth = () => {
  windowWidth.value = window.innerWidth;
};


onUnmounted(() => {
  window.removeEventListener('resize', updateWindowWidth);
});

// Computed property to check if it's a mobile view
const isMobileView = computed(() => windowWidth.value <= 991);

onMounted(async () => {
  try {
    await Promise.all([fetchMenuItems(), fetchHeader(), fetchPageContent(), window.addEventListener('resize', updateWindowWidth)]);
  } finally {
    isLoading.value = false;
  }
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
  gap: 1px;
  padding: 0;
  margin-bottom: 15px;
}

.font-size-controls li a {
  text-decoration: none;
  cursor: pointer;
  font-size: 1rem;
}

.tgmobile__menu {
  position: fixed;
  top: 0;
  right: 0;
  width: 250px;
  height: 100vh;
  background: #fff;
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
  transform: translateX(100%);
}

.tgmobile__menu-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
}

.mobile-menu-open .tgmobile__menu {
  transform: translateX(0);
}

/* Hide submenus by default */
.sub-menu {
  display: none;
  position: absolute;
  top: 0;
  right: 100%;
  /* Move submenu to the left of the parent */
}

/* Show submenu when hovering over parent */
.menu-item-has-children:hover>.sub-menu {
  display: block;
}

/* Align nested submenus to the left */
.sub-menu .sub-menu {
  top: 0;
  right: 100%;
  /* Ensures nested menus also align to the left */
}

/* Show deeper nested submenus on hover */
.sub-menu li.menu-item-has-children:hover>.sub-menu {
  display: block;
}

/* Hide submenus by default */
.sub-menu {
  display: none;
  position: absolute;
  top: 0;
  right: 100%;
  /* Move submenu to the left of the parent */
}

/* Default desktop and large screen styles (large screens) */
.tgmenu__nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: nowrap;
  /* Keep the menu in one row on large screens */
}

.tgmenu__navbar-wrap {
  display: flex;
  justify-content: space-between;
  flex-wrap: nowrap;
  width: 100%;
}

.navigation {
  display: flex;
  gap: 3px;
  flex-wrap: nowrap;
  /* Prevent wrapping */
  justify-content: center;
  /* Center the menu items */
}

.navigation li {
  white-space: nowrap;
  position: relative;
  white-space: normal;
  /* Allow wrapping */
  word-wrap: break-word;
  /* Break long words and wrap them to the next line */
  word-break: break-word;
  /* Break long words on boundary */

  /* Prevent text wrapping */
}

/* General Menu Styles */
.tgmenu__navbar-wrap {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0;
  margin: 0;
}



.navigation li a {
  display: inline-block;
  /* Ensure links are inline-block so they respect width */
  max-width: 100%;
  /* Ensure the link does not exceed its container's width */
  overflow: hidden;
  /* Avoid overflow */
  text-overflow: ellipsis;
  /* Optional: add ellipsis if text is too long */
  word-wrap: break-word;
}

/* Responsive Menu Styles */
@media (max-width: 991px) {
  .tgmenu__navbar-wrap {
    flex-direction: column;
    align-items: stretch;
  }


  .navigation li {
    width: 100%;
    text-align: left;
  }



  /* Accordion Style for Submenus */
  .sub-menu {
    list-style: none;
    padding: 0;
    margin: 0;
  }


  .menu-item-has-children a {
    position: relative;
  }

  /* Collapse Submenu Animation */
  .collapse:not(.show) {
    display: none;
  }
}

/* Default Logo Style */
.logo-img {
  height: 100px;
  width: 250px;
  margin-left: 30%;
}

/* Mobile Menu Alignment */
@media (max-width: 991px) {
  .navbar-collapse {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    /* Align menu items to the left */
  }

  .mobile-menu-left {
    align-items: flex-start !important;
  }

  .navigation {
    width: 100%;
  }

  .navigation li {
    width: 100%;
    text-align: left;
  }
}

@media (min-width: 992px) {
  .tgmenu__main-menu .navigation {
    display: flex;
    gap: 4px;
    /* Adjust the spacing between menu items */
  }

  .tgmenu__main-menu .navigation li {
    margin: 0 10px;
    /* Reduce left and right margin */
  }

  .tgmenu__main-menu .navigation li a {
    padding: 8px 12px;
    /* Adjust padding inside menu items */
  }
}

.btn::before,
.btn::after {
  content: none !important;
}
</style>