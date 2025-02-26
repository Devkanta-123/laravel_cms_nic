<template>
    <footer class="footer">
        FOOTER
        <div class="footer-content">
            <div
                v-if="currentLanguage === 'English'"
                v-html="footerContent.content"
            ></div>
            <div
                v-else-if="currentLanguage === 'Hindi'"
                v-html="footerContent.hindi_content"
            ></div>
            <div
                v-else-if="currentLanguage === 'Khasi'"
                v-html="footerContent.khasi_content"
            ></div>
        </div>
    </footer>
</template>

<script setup>
import { ref, onMounted, inject } from "vue";
import axios from "axios";

const currentLanguage = inject("language");

const footerContent = ref({});

const fetchFooterContent = async () => {
    try {
        const response = await axios.get("/get_footer");
        footerContent.value = response.data[0];
    } catch (error) {
        console.error("Failed to fetch footer content:", error);
    }
};

onMounted(() => {
    fetchFooterContent();
});
</script>
