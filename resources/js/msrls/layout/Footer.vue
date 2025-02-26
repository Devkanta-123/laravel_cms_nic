<template>
    <div class="row footer-wrap">
      <!-- Social Media Section -->
      <div class="col-lg-5 col-md-8" v-if="hasSocialLinks">
        <div class="footer-widget">
          <p class="mb-30">
            <div class="footer-widget" v-if="footerContent.some(item => item.type === 'text')">
              <p v-for="text in footerContent.filter(item => item.type === 'text')" :key="text.id">{{ text.content }}</p>
            </div>
          </p>
          <ul class="footer-social">
            <li v-for="social in footerContent.filter(item => item.type === 'link')" :key="social.id">
              <a :href="social.link" target="_blank" v-if="social.link">
                <i :class="getSocialIconClass(social.link)"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
  
      <!-- Links Section -->
      <div class="col-lg-3 col-md-8">
        <div class="footer-widget">
          <h3 class="widget-header">Links</h3>
          <ul class="footer-list">
            <li v-for="link in footerContent.filter(item => item.type === 'link')" :key="link.id">
              <a :href="link.link">{{ link.content || 'Link' }}</a>
            </li>
          </ul>
        </div>
      </div>
  
      <!-- Logo Section -->
      <div class="col-lg-3 col-md-6">
        <div class="footer-widget">
          <h3 class="widget-header">Logo</h3>
          <div class="sidebar-post mb-20" v-for="logo in footerContent.filter(item => item.type === 'logo')" :key="logo.id">
            <img :src="`/storage/${logo.content}`" alt="footer logo" />
          </div>
        </div>
      </div>
  
      <!-- Text Section -->
      <div class="col-lg-12"></div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue';
  import axios from 'axios';
  
  const footerContent = ref([]);  // Holds the footer content data
  const loadingFooter = ref(false);  // Loading state for footer
  const cacheDuration = 24 * 60 * 60 * 1000;  // Cache duration (1 day)
  
  // Method to fetch footer content from API
  const fetchFooterContent = async () => {
    try {
      loadingFooter.value = true;
      const response = await axios.get("/get_footer");
      footerContent.value = response.data.data;
    } catch (error) {
      console.error("Failed to fetch footer content:", error);
    } finally {
      loadingFooter.value = false;
    }
  };
  
  // Fetch footer content when the component is mounted
  onMounted(() => {
    fetchFooterContent();
  });
  
  // Computed property to check if there are social links
  const hasSocialLinks = computed(() => {
    return footerContent.value.some(item => item.type === 'link');
  });
  
  // Method to get social media icon class based on the link
  const getSocialIconClass = (link) => {
    if (link.includes('facebook')) return 'fab fa-facebook-f';
    if (link.includes('instagram')) return 'fab fa-instagram';
    if (link.includes('youtube')) return 'fab fa-youtube';
    if (link.includes('x.com')) return 'fab fa-twitter';
    return '';
  };
  </script>
  
  <style scoped>
  /* Add styles specific to footer here */
  </style>
  