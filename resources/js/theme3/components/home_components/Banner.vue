<template>
    <div class="banner-container">
      <img :src="bannerSrc" alt="Banner Image" class="banner-image">
    </div>
  </template>
  <script setup>
  import { ref } from 'vue';
  
  // Replace with the path to your banner image
  const bannerSrc = ref('/storage/slides/smb.jpg');
  </script>

<style scoped>
.banner-container {
  width: 100%;
  height: 150px; /* Adjust height as needed */
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f5f5f5; /* Optional: background color */
}

.banner-image {
  width: 100%;
  height: auto;
  object-fit: cover;
}
</style>
