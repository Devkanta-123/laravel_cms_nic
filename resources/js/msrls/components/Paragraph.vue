<template>
  <div class="tab-overview">
    <!-- Show loading message while fetching data -->
    <div v-if="loadingParagraphs" class="loading">Loading paragraphs...</div>

    <!-- Iterate over the paragraph data and display each paragraph -->
    <div v-for="(paragraph, index) in paragraphs" :key="paragraph.id" class="paragraph-item">
      <h3>{{ decodeHtmlEntities(stripHtmlTags(getParagraphTitle(paragraph))) }}</h3>
      <p>{{ decodeHtmlEntities(stripHtmlTags(getParagraphDescription(paragraph))) }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import axios from 'axios';

// Ref to hold paragraphs data
const paragraphs = ref([]);
// Loading state to track API call progress
const loadingParagraphs = ref(false);

// Language prop passed down from parent component
const props = defineProps({
  language: {
    type: String,
    required: true
  }
});

// Fetch paragraphs data from API
const fetchParagraphs = async () => {
  loadingParagraphs.value = true;  // Show loading indicator

  try {
    const response = await axios.get('/get_paragraphs');  // Replace with correct API endpoint
    console.log('Fetched paragraphs:', response.data);

    // Check if the response contains the expected data format
    if (response.data && Array.isArray(response.data.data)) {
      paragraphs.value = response.data.data;  // Populate paragraphs data
    } else {
      console.error('Invalid data format from API');
    }
  } catch (error) {
    console.error('Failed to fetch paragraphs:', error);
  } finally {
    loadingParagraphs.value = false;  // Hide loading indicator
  }
};

// Function to get the correct paragraph title based on selected language
const getParagraphTitle = (paragraph) => {
  switch (props.language) {
    case 'Hindi':
      return paragraph.hindi_title || paragraph.title;  // Fallback to default title if hindi_title is missing
    case 'Khasi':
      return paragraph.khasi_title || paragraph.title;  // Fallback to default title if khasi_title is missing
    case 'English':
    default:
      return paragraph.title;
  }
};

// Function to get the correct paragraph description based on selected language
const getParagraphDescription = (paragraph) => {
  switch (props.language) {
    case 'Hindi':
      return paragraph.hindi_description || paragraph.description;  // Fallback to default description if hindi_description is missing
    case 'Khasi':
      return paragraph.khasi_description || paragraph.description;  // Fallback to default description if khasi_description is missing
    case 'English':
    default:
      return paragraph.description;
  }
};

// Function to strip HTML tags
const stripHtmlTags = (text) => {
  if (!text) return ''; // Handle null or undefined text
  return text.replace(/<\/?[^>]+(>|$)/g, ''); // Regex to remove HTML tags
};

// Function to decode HTML entities
const decodeHtmlEntities = (text) => {
  if (!text) return ''; // Handle null or undefined text
  const textarea = document.createElement('textarea'); // Create a textarea element
  textarea.innerHTML = text; // Set the innerHTML to the text
  return textarea.value; // The browser decodes HTML entities
};

onMounted(() => {
  fetchParagraphs();  // Call API to fetch paragraphs on mount
});
</script>
