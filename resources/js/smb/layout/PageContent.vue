<template>
  <Loader v-if="isLoading" />
  <div class="app-wrapper">
    <nav class="main-header navbar navbar-expand-lg navbar-light" :class="{ 'high-contrast': highContrastMode }">
      <div class="navbar-logo-text">
        <img :src="getLogoUrl()" alt="Logo" class="navbar-logo" />
        <span class="navbar-text navbar-brand">{{ header.website_short }} </span>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-menu navbar-nav m-auto">

          <li v-for="item in menuItems" :key="item.id"
            :class="{ 'nav-item': true, 'nav-item dropdown': item.submenus && item.submenus.length }">

            <router-link v-if="item.submenus && item.submenus.length" class="nav-link dropdown-toggle"
              data-toggle="dropdown" aria-expanded="false" :to="{ name: 'Page', params: { id: item.id, } }">
              {{ getMenuItemName(item) }}
            </router-link>

            <router-link v-else class="nav-link" :to="{ name: 'Page', params: { id: item.id, } }">
              {{ getMenuItemName(item) }}
            </router-link>

            <div v-if="item.submenus && item.submenus.length" class="dropdown-menu" aria-labelledby="navbarDropdown">

              <router-link v-for="subItem in item.submenus" :key="subItem.id" class="dropdown-item"
                :to="{ name: 'Page', params: { id: subItem.id, } }">
                {{ getMenuItemName(subItem) }}
              </router-link>
            </div>
          </li>

        </ul>
      </div>
      <div>
        <select v-model="language" @change="handleLanguageChanged" class="form-control custom-select">
          <option value="English">English</option>
          <option value="Hindi">Hindi</option>
          <option value="Khasi">Khasi</option>
        </select> <br>
        <div class="font-size-controls ">
          <button class="btn btn-sm border" @click="increaseFontSize">A++</button>
          <button class="btn btn-sm border" @click="decreaseFontSize">A-</button>
          <button class="btn btn-sm border" @click="resetFontSize">Reset</button>
        </div>

        <button class="btn btn-sm border" @click="toggleContrastMode">High Contrast</button>
      </div>
    </nav>

    <div class="p_content-wrapper">
      <div v-if="props.id == 1" class="content">
        <Carousel></Carousel>
        <br><br><br>

        <Paragraph :menu=props.id />
      </div>

      <div v-if="props.id != 1" class="content">
        <router-view></router-view>
        <br>
        <Footer text1="This is the first line of text." text2="This is the second line of text." :logos="logos">
        </Footer>
      </div>
    </div>
  </div>
</template>
<script setup>

import { ref, onMounted, defineProps, defineEmits, inject, provide } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Footer from './Footer.vue';
import Page from './Page.vue';
import Carousel from '../components/Carousel.vue';
import Paragraph from '../components/Paragraph.vue';
import HomeParagraph from '../components/HomeParagraph.vue';
import Loader from '../../components/Loader.vue';
import SMBNavbar from './SMBNavbar.vue';


const menuItems = ref([]);
const header = ref([]);
const highContrastMode = ref(false);
const language = ref('English');
const isLoading = ref(true);

provide('language', language);

const props = defineProps({
  id: {
    type: String,
  },
});
alert("PropsID : " + props.id);

const logos = [
  { src: '/storage/logo/rti.jpg', alt: 'Logo 1' },
  { src: '/storage/logo/megpgrams.jpg', alt: 'Logo 2' },
  { src: '/storage/logo/meghalayagovin.png', alt: 'Logo 3' }
];


const fetchMenuItems = async () => {
  try {
    const response = await axios.get('/get_mainmenus',);
    menuItems.value = response.data;

  } catch (error) {
    console.error('Failed to fetch menu items:', error);
  }
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

const adjustFontSize = inject('adjustFontSize');
const resetFontSize = inject('resetFontSize');

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
  document.documentElement.classList.toggle('high-contrast', highContrastMode.value);
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
.navbar-collapse {
  flex-grow: 1 !important;
}
</style>
