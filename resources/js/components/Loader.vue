<!-- Loader.vue -->
<template>
  <div id="preloader">
    <div id="loader" class="loader">
      <div class="loader-container">
        <div class="loader-icon"><img :src="logo" alt="Preloader"></div>
      </div>
    </div>
  </div>
</template>
<script setup>
import logo from '@/assets/images/msrlslogo.png'
</script>