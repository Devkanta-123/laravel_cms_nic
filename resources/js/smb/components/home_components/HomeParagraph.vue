<template>
    <div class=" container hp_container">
      <div class="content hp_content">
        <div class="left-content">
          <h2>About {{ heading }}</h2>
         <hr>
          <p v-if="currentLanguage === 'English'">{{paragraph}}</p>
          <p v-else-if="currentLanguage === 'Hindi'">{{hindi_paragraph}}</p>
          <div class="logo-container">
            <a v-for="(logo, index) in logos" :key="index" :href="logo.link" target="_blank">
              <img :src="logo.src" :alt="logo.src" class="logo img-fluid" />
            </a>
          </div>
        </div>
        <div class="vertical-line"> &nbsp;</div>

        <div class="right-menu" v-if="menuItems">
          <h5 v-if='currentLanguage == "English"'>Other Menu</h5>
          <h5 v-if='currentLanguage == "Hindi"'>अन्य मेनू</h5>
          <ul>
            <li v-for="item in menuItems" :key="item.id">
                <a href="#">
        
                  {{ getMenuItemName(item) }} 
                </a>
                <ul v-if="item.submenus && item.submenus.length">
                  <li v-for="subItem in item.submenus" :key="subItem.id">
                      <a href="#">
                       

                        {{ getMenuItemName(subItem) }} 
                      </a>
                      
                      <!-- <router-link v-if="item.submenus && item.submenus.length" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" :to="{ name: 'Page', params: { id: item.id,  } }">
                          {{ getMenuItemName(item) }} 
                      </router-link> -->

                  </li>
                </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, defineProps, inject } from 'vue';
  import axios from 'axios';

  const currentLanguage = inject('language');
  const heading = ref(''); 
  const paragraph = ref(''); 
  const hindi_paragraph = ref(''); 
  const menuItems = ref([]);
  
  const logos = ref([
    { src: '/storage/logo/rti.jpg', alt: 'Logo 1', link: 'https://www.megrti.gov.in/' },
    { src: '/storage/logo/megpgrams.jpg', alt: 'Logo 2', link: 'https://www.megpgrams.gov.in/' },
    { src: '/storage/logo/meghalayagovin.png', alt: 'Logo 2', link: 'https://www.meghalaya.gov.in/' },
  ]);
  
  const fetchPara = async () => {
    try {
      const response = await axios.get('/get_website_description');
      heading.value = response.data.website_short;
      paragraph.value= response.data.description; 
      hindi_paragraph.value= response.data.hindi_description;

    } catch (error) {
      console.error('Failed to fetch website description:', error);
    }
  };
  
  const fetchMenuItems = async () => {
    try {
      const response = await axios.get('/get_rightmenus');
      menuItems.value = response.data;
    } catch (error) {
      console.error('Failed to fetch menu items:', error);
    }
  };
  
  const getMenuItemName = (item) => {
    console.log("Current langiage: "+currentLanguage.value);
switch (currentLanguage.value) {
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


  onMounted(() => {
    fetchPara();
    fetchMenuItems();
  });
  </script>
  
  <style scoped>

  .left-content {
    flex: 1;
  }
  
  .logo-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  
 @media screen and (min-width:900px) {
  .logo {
    width: 200px;
    height: auto;
    margin: 0 50px; 
  }

   
 }
   
 @media screen and (min-width:1000px) {
  .hp_container {
    display: flex;
    align-items: stretch;
  } 
  
  .hp_content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }
 }

@media screen and (max-width: 999px) {
  .hp_container {
    display: block;
  }

  .hp_content {
    display: block;
  }

  .vertical-line {
    display: none;
  }

  .right-menu {
    margin-top: 20px;
    float:left ;
  }
}
  </style>
  