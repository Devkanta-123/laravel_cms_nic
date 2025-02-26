<template>
    <div v-if="loadingNewsDetail" class="loading">Loading...</div>
    <div v-else-if="errorMessage" class="error">{{ errorMessage }}</div>
    <div v-else>
        <h1>News Details</h1>

        <!-- Loop through the array of news items -->
    </div>
    <div class="course-sidebar sticky-widget" style="margin-left: 3%;">
        <div v-for="(news, index) in newsDetailData" :key="index">
            <p class="mt-20 wow fade-in-bottom">
                {{ news.english_title }} - &nbsp; Attachment &nbsp;
                <span>
                    <!-- Anchor tag wrapping the icon, making it clickable -->
                    <a :href="'/storage/' + news.filepath" target="_blank" rel="noopener noreferrer"
                        style="cursor: pointer;">
                        <i class="fa-solid fa-file-pdf"></i>
                    </a>
                </span>
            </p>
        </div>
    </div>
    <br>
</template>
<script setup>
import { ref, onMounted, defineProps } from 'vue';
import axios from 'axios';

// Props
const props = defineProps({
    newsId: {
        type: String,
        required: true,
    },
});

// Reactive variables
const newsDetailData = ref(null); // Holds the news detail data
const loadingNewsDetail = ref(false); // Tracks loading state
const errorMessage = ref(''); // Holds any error message

const fetchNewsDetail = async () => {
    loadingNewsDetail.value = true;
    errorMessage.value = ''; // Reset error message

    try {
        debugger;
        const response = await axios.get(`/get_newsletter_detail/${props.newsId}`);
        if (response.data.length > 0) {
            newsDetailData.value = response.data;
            console.log('Fetched news detail:', newsDetailData.value);
        } else {
            alert('No data found for this news.'); // Alert when no data is found
        }
    } catch (error) {
        console.error('Failed to fetch news detail:', error);
        errorMessage.value = 'Failed to fetch news detail. Please try again later.';
        alert('Failed to fetch news detail. Please try again later.'); // Alert in case of error
    } finally {
        loadingNewsDetail.value = false;
    }
};

// Use the newsId
console.log("News ID in NewsDetails:", props.newsId);
// Call the API when the component is mounted
onMounted(() => {
    fetchNewsDetail();
});
</script>
<style scoped>
.fa-solid.fa-file-pdf {
    color: #e80c2d;
    /* Change the icon color to red (adjust the color as needed) */
}
</style>
