<template>

    <div class="card">
      <div class="card-image">
        <img :src="imageSrc" :alt="title" />
      </div>
      <div class="card-title">
        <h3>{{ title }}</h3>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    imageSrc: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    }
  });
  </script>
  
  <style scoped>
  .card {
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
    width: 300px; /* Adjust as needed */
    overflow: hidden;
  }
  
  .card:hover {
    transform: scale(1.05);
  }
  
  .card-image img {
    width: 100%;
    height: 70%; /* Adjust as needed */
    object-fit: cover;
  }
  
  .card-title {
    height: 30%; /* Adjust as needed */
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
  }
  </style>
  