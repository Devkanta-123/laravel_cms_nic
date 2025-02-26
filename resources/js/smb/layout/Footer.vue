<template>
      <hr class="footer_hr">
       <footer class="footer" >
         <div class="footer-content">
           <p class="footer-text">{{ text1 }}</p>
           <p class="footer-text">{{ text2 }}</p>
           <div class="footer-logos">
             <img v-for="(logo, index) in logos" :key="index" :src="logo.src" :alt="logo.alt" class="footer-logo" />
           </div>
         </div>
       </footer>
     </template>
     
     <script setup>
     import { defineProps } from 'vue';
     
     const props = defineProps({
       text1: {
         type: String,
         required: true
       },
       text2: {
         type: String,
         required: true
       },
       logos: {
         type: Array,
         required: true,
         default: () => []
       }
     });
     </script>
     
   