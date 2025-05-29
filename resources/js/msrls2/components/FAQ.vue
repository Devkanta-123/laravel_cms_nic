<template>
    <div class="container">
        <div class="row">
                <div class="accordion" style="opacity: 1;">
                    <div v-for="(faq, index) in processedFaqData" :key="faq.id"
                        class="px-0 card rounded-3 mb-3 collapse-custom py-2 flex-grow-1 position-relative">
                        <div class="faq-color-strip position-absolute"></div>
                        <div class="p-0 card-header border-0 rounded-3">
                            <a class="p-3 text-white fw-bold d-flex align-items-center collapsed"
                                :data-bs-toggle="'collapse'" :href="'#collapse' + index" aria-expanded="false">
                                <h5 class="m-0">{{ faq.question }}</h5>
                                <span class="ms-auto arrow me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="8" viewBox="0 0 13 8"
                                        fill="none">
                                        <path class="stroke-dark" d="M11.5 1L6.25 6.5L1 1" stroke="#111827"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                        <div :id="'collapse' + index" class="collapse" data-bs-parent=".accordion">
                            <p class="px-3 fs-6 fw-regular">{{ faq.answer }}</p>
                        </div>
                    </div>
                </div>
        </div>

    </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    language: String
});

const faqData = ref([]);
const loadingfaqData = ref(false);
const cacheDuration = 5 * 60 * 1000; // 5 minutes
const now = Date.now();

const fetchFAQs = async () => {
    debugger;
    const cachedData = sessionStorage.getItem('faq');
    const cachedTimestamp = sessionStorage.getItem('faqTimestamp');

    if (cachedData && cachedTimestamp && now - parseInt(cachedTimestamp) < cacheDuration) {
        console.log('Using cached faqData from sessionStorage');
        faqData.value = JSON.parse(cachedData);
        return;
    }

    loadingfaqData.value = true;
    try {
        const response = await axios.get('/get_faq', {
            params: {
                flag: 2
            }
        });
        console.log('Fetched faqData:', response.data);
        if (response.data && Array.isArray(response.data)) {
            faqData.value = response.data;
            sessionStorage.setItem('faq', JSON.stringify(response.data));
            sessionStorage.setItem('faqTimestamp', now.toString());
        } else {
            console.error('Invalid data format');
        }
    } catch (error) {
        console.error('Failed to fetch faqData:', error);
    } finally {
        loadingfaqData.value = false;
    }
};

//  Create a computed property to format FAQ data dynamically
const processedFaqData = computed(() => {
    return (faqData.value || []).map(faq => ({
        id: faq.id,
        question: props.language === 'Hindi' ? faq.hindi_title_question || faq.english_title_question
            : props.language === 'Khasi' ? faq.khasi_title_question || faq.english_title_question
                : faq.english_title_question,
        answer: props.language === 'Hindi' ? faq.hindi_answer || faq.english_answer
            : props.language === 'Khasi' ? faq.khasi_answer || faq.english_answer
                : faq.english_answer
    }));
});

onMounted(fetchFAQs);

// If language changes, re-fetch FAQs
watch(() => props.language, () => {
    fetchFAQs();
});
</script>

<style scoped>
.faq-color-strip {
    width: 6px;
    /* Thickness of the strip */
    height: 100%;
    /* Full height of the card */
    background-color: #014E4E;
    /* Change to your desired color */
    position: absolute;
    top: 0;
    left: 0;
    border-top-left-radius: 8px;
    /* Optional rounded corner */
    border-bottom-left-radius: 8px;
    /* Optional rounded corner */
}

.btn::before,
.btn::after {
    content: none !important;
}

.p-0.card-header.border-0.rounded-3 {
    background-color: white;
}
</style>