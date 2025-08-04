<template>
    <div class="container">
        <div class="row justify-content-center gy-4">
            <template v-if="hasNoticeboard">
                <div class="about__content-inner-four">
                    <div class="about__list-box">
                        <ul class="list-wrap">
                            <h5 class="m-0">{{ pageName }}</h5>
                            <br>
                            <li v-for="notice in paginatedNoticeboard" :key="notice.id"
                                class="w-100 d-flex align-items-center mb-2 ms-1">
                                <i class="fa fa-arrow-right"></i>
                                <a href="#" style="color: #2A3335;" @click.prevent="openfile(notice)">
                                    {{ notice.title }} ({{ formatDate(notice.date) }})
                                </a>
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
                            <li v-for="notify in paginatedNotification" :key="notify.id"
                                class="w-100 d-flex align-items-center mb-2 ms-1">
                                <i class="fa fa-arrow-right"></i>
                                <a href="#" style="color: #2A3335;" @click.prevent="openfile(notify)">
                                    {{ notify.title }} ({{ formatDate(notify.date) }})
                                </a>
                                <span v-if="isNewNotice(notify.date)" class="new-badge">New</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </template>
          
        </div>

        <nav aria-label="Page navigation example" v-if="totalPages > 1">
            <ul class="pagination list-wrap">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <a class="page-link" href="#" @click.prevent="goToPage(currentPage - 1)">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                </li>

                <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }">
                    <a class="page-link" href="#" @click.prevent="goToPage(page)">{{ page }}</a>
                </li>

                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <a class="page-link" href="#" @click.prevent="goToPage(currentPage + 1)">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>


<script setup>
import { ref, computed, defineProps } from 'vue';
import FeedBack from "../components/FeedBack.vue";
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


// Pagination states
const currentPage = ref(1);
const itemsPerPage = 10;


// Computed paginated data
const paginatedNoticeboard = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return props.noticeboard.slice(start, start + itemsPerPage);
});

const paginatedNotification = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return props.notification.slice(start, start + itemsPerPage);
});

// Total pages
const totalPages = computed(() => {
    const list = hasNoticeboard.value ? props.noticeboard : props.notification;
    return Math.ceil(list.length / itemsPerPage);
});

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

</script>

