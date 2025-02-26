<template>
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- Show Notice Board if data exists, otherwise show Notifications -->
            <template v-if="hasNoticeboard">
                <div class="col-12">
                    <h2 class="section-title">Notice Board</h2>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8" v-for="notice in noticeboard" :key="notice.id">
                    <div class="notice-item">
                        <div class="notice-content">
                            <h3>{{ notice.title }}</h3>
                            <p>{{ notice.description }}</p>
                            <a :href="notice.file" target="_blank">View Notice</a>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else-if="hasNotification">
                <div class="col-12">
                    <h2 class="section-title">Notifications</h2>
                </div>
                <div class="col-12">
                    <ul class="notification-list">
                        <li v-for="notify in notification" :key="notify.id">
                            <span class="badge">New</span>
                            <strong>{{ notify.title }}</strong>
                        </li>
                    </ul>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps, watch } from 'vue';

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
watch(() => props.noticeboard, (newVal) => {
    debugger;
    console.log("Noticeboard Updated:", newVal);
}, { deep: true });

watch(() => props.notification, (newVal) => {
    debugger;
    console.log("Notification Updated:", newVal);
}, { deep: true });

const hasNoticeboard = computed(() => props.noticeboard?.length > 0);
const hasNotification = computed(() => !hasNoticeboard.value && props.notification?.length > 0);
</script>
