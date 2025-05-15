<template>
    <div class="col-lg-12">
        <div class="contact-map" v-html="iframeHtml"></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const iframeHtml = ref(""); // Store the iframe HTML string

// Fetch contact details from API
const fetchContactUs = async () => {
    try {
        const response = await axios.get("/get_contactus",{params:{'flag': 'A'}}); 
        if (response.data && response.data[0]?.iframe) {
            iframeHtml.value = response.data[0].iframe; // Extract the iframe string
        } else {
            console.error("Invalid data format");
        }
    } catch (error) {
        console.error("Failed to fetch contact details:", error);
    }
};

// Fetch contact details when the component is mounted
onMounted(fetchContactUs);
</script>

<style scoped></style>
