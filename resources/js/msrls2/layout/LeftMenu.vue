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
                <ul class="list-unstyled marquee-content">
                    <li v-for="notice in noticeBoardData" :key="'a' + notice.id" class="card shadow-sm border-0 mb-3">
                        <div class="card-body p-3">
                            <a :href="`/storage/${notice.file}`" target="_blank" rel="noopener noreferrer"
                                class="fw-semibold text-decoration-none d-block">
                                <i class="flaticon-arrow-button me-2"></i> {{ notice.title }}
                            </a>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="fa fa-calendar me-1"></i> {{ formatDate(notice.date) }}
                                </small>
                                <span v-if="isNewNotice(notice.date)" class="badge bg-danger">
                                    New
                                </span>
                            </div>
                        </div>
                    </li>

                    <!-- Duplicate for smooth loop -->
                    <li v-for="notice in noticeBoardData" :key="'b' + notice.id" class="card shadow-sm border-0 mb-3">
                        <div class="card-body p-3">
                            <a :href="`/storage/${notice.file}`" target="_blank" rel="noopener noreferrer"
                                class="fw-semibold text-decoration-none d-block">
                                <i class="flaticon-arrow-button me-2"></i> {{ notice.title }}
                            </a>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="fa fa-calendar me-1"></i> {{ formatDate(notice.date) }}
                                </small>
                                <span v-if="isNewNotice(notice.date)" class="badge bg-danger">
                                    New
                                </span>
                            </div>
                        </div>
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
