<template>
    <div class="container">
        <div class="row justify-content-center gy-4">
            <div v-if="hasNotification">
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
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps, watch } from 'vue';

const props = defineProps({
    notification: {
        type: Array,
        default: () => []
    }
});

watch(() => props.notification, (newVal) => {
    console.log("Notification Updated:", newVal);
}, { deep: true });

const hasNotification = computed(() => props.notification.length > 0);
</script>
