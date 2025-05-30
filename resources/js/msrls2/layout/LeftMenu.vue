<template>
    <aside class="blog__sidebar">
        <div class="sidebar__widget">
            <h4 class="sidebar__widget-title d-flex justify-content-between align-items-center">
                Notice Board
                <router-link :to="{ name: 'Page', params: { id: 45 }, query: { page_name: 'Notice Board' } }"
                    class="btn btn-primary btn-xs">
                    See All
                </router-link>
            </h4>
            <div class="sidebar__cat-list marquee-container">
                <ul class="list-wrap marquee-content">
                    <!-- First copy -->
                    <li v-for="notice in noticeBoardData" :key="'a' + notice.id">
                        <a :href="`/storage/${notice.file}`" target="_blank" rel="noopener noreferrer">
                            <i class="flaticon-arrow-button"></i>
                            {{ notice.title }} ({{ formatDate(notice.date) }})
                        </a>
                        <span v-if="isNewNotice(notice.date)" class="new-badge">New</span>
                    </li>
                    <!-- Duplicate copy for smooth looping -->
                    <li v-for="notice in noticeBoardData" :key="'b' + notice.id">
                        <a :href="`/storage/${notice.file}`" target="_blank" rel="noopener noreferrer">
                            <i class="flaticon-arrow-button"></i>
                            {{ notice.title }} ({{ formatDate(notice.date) }})
                        </a>
                        <span v-if="isNewNotice(notice.date)" class="new-badge">New</span>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
const noticeBoardData = ref();
const loadingNoticeBoard = ref(false);
const fetchNoticeBoard = async () => {
    const now = new Date().getTime()
    const cachedData = localStorage.getItem("LeftNoticeBoard")
    const cachedTimestamp = localStorage.getItem("LeftNoticeBoardTimeStamp")

    if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
        console.log("Using cached footer content from localStorage")
        noticeBoardData.value = JSON.parse(cachedData)
        return
    }

    loadingNoticeBoard.value = true
    try {
        const response = await axios.get('/get_notifications', { params: { flag: 'A' } });
        console.log("Fetched LeftNoticeBoard Content:", response.data)
        noticeBoardData.value = response.data
        localStorage.setItem("LeftNoticeBoardter", JSON.stringify(response.data))
        localStorage.setItem("LeftNoticeBoardTimeStamp", now.toString())
    } catch (error) {
        console.error("Failed to LeftNoticeBoard  content", error)
    } finally {
        loadingNoticeBoard.value = false
    }
}

const formatDate = (dateString) => {
    if (!dateString) return "Unknown Date";
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-GB', options);
};

const isNewNotice = (noticeDate) => {
    const today = new Date();
    const noticeDateObj = new Date(noticeDate);
    const diffTime = today - noticeDateObj;
    const diffDays = diffTime / (1000 * 60 * 60 * 24); // Convert milliseconds to days
    return diffDays <= 10;
};
onMounted(() => {
    fetchNoticeBoard();
})

</script>

<style scoped></style>
