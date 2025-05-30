<template>
    <div class="container" v-if="archive.length">
        <div class="row justify-content-center gy-4">
            <div class="about__content-inner-four">
                <div class="about__list-box">
                    <ul class="list-wrap">
                        <h5 class="m-0">{{ pageName }}</h5>
                        <br>
                        <li v-for="item in archive" :key="item.id" class="w-100 d-flex align-items-center mb-2 ms-1">
                            <i class="fa fa-arrow-right me-2"></i>
                            <a href="#" class="text-dark" @click.prevent="openfile(item)">
                                {{ item.title }} ({{ formatDate(item.created_at) }})
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { computed, defineProps } from 'vue';

const props = defineProps({
    archive: {
        type: Array,
        default: () => []
    }
});
// Debugging
const openfile = (item) => {
    if (item.type === 'file' && item.file) {
        const filePath = '/storage/' + item.file.replace('public/', '');
        window.open(filePath, '_blank');
    } else if (item.type === 'link' && item.link) {
        window.open(item.link, '_blank');
    }
};

const formatDate = (dateString) => {
    if (!dateString) return "Unknown Date";
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-GB', options);
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

.new-badge {
    background: linear-gradient(90deg, #c8ff00, #ee0979);
    color: white;
    font-size: 12px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 5px;
    margin-left: 8px;
    animation: blink 1s infinite;
}
</style>
