<template>
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- Show Notice Board if data exists, otherwise show Notifications -->
            <template v-if="hasNoticeboard">
                <div class="about__content-inner-four">
                    <div class="about__list-box">
                        <ul class="list-wrap">
                            <h5 class="m-0">{{ pageName }}</h5>
                            <br>
                            <li v-for="notice in props.noticeboard" :key="notice.id"  class="w-100 d-flex align-items-center mb-2 ms-1">
                                <i class="fa fa-arrow-right"></i>
                                <a href="#"  style="color: #2A3335;" @click.prevent="openfile(notice)">{{ notice.title }} ({{
                                    formatDate(notice.date) }})</a>
                                <span v-if="isNewNotice(notice.date)" class="new-badge">New</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </template>

            <template v-else-if="hasNotification">
                <div class="about__content-inner-four">
                    <div class="about__list-box">
                        <ul class="list-wrap">
                            <h5 class="m-0">{{ pageName }}</h5>
                            <br>
                            <li v-for="notify in notification" :key="notify.id"  class="w-100 d-flex align-items-center mb-2 ms-1" >
                                <i class="fa fa-arrow-right"></i>
                                <a href="#"  style="color: #2A3335;"  @click.prevent="openfile(notify)">{{ notify.title }} ({{
                                    formatDate(notify.date) }})</a>
                                <span v-if="isNewNotice(notify.date)" class="new-badge">New</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </template>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps } from 'vue';

const props = defineProps({
    noticeboard: {
        type: Array,
        default: () => []
    },
    notification: {
        type: Array,
        default: () => []
    }
});

// Debugging
const hasNoticeboard = computed(() => props.noticeboard?.length > 0);
const hasNotification = computed(() => !hasNoticeboard.value && props.notification?.length > 0);
const openfile = (item) => {
    const filePath = '/storage/' + item.file.replace('public/', '');  // Build file URL based on Laravel's storage path
    window.open(filePath, '_blank');  // Open the file in a new tab

};
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


</script>
<style scoped>
@keyframes blink {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.2;
    }

    100% {
        opacity: 1;
    }
}
.list-wrap li {
  display: block;
  margin-bottom: 10px;
}


</style>
