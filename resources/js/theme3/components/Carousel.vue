<template>
    <div
        id="carouselExample"
        class="carousel slide px-3 pt-0"
        data-bs-ride="carousel"
    >
        <div class="carousel-inner shadow-lg">
            <div
                class="carousel-item"
                v-for="(slide, index) in slides"
                :class="{ active: index === 0 }"
                :key="slide.id"
            >
                <img
                    v-lazy="`/storage/${slide.image}`"
                    class="d-block w-100"
                    :alt="`Slide ${index + 1}`"
                />
                <div class="overlay"></div>
            </div>
        </div>
        <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#carouselExample"
            data-bs-slide="prev"
        >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#carouselExample"
            data-bs-slide="next"
        >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const slides = ref([]);

const fetchSlides = async () => {
    try {
        const response = await axios.get("/get_carousel");
        slides.value = response.data;
    } catch (error) {
        console.error("Failed to fetch slides:", error);
    }
};

onMounted(() => {
    fetchSlides();
});
</script>

<style scoped>
.carousel-item .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0);
    pointer-events: none;
}

/* Fade-in effect */
.fade-in {
    opacity: 0;
    transition: opacity 1s ease-in;
}

.carousel-item.active .fade-in {
    opacity: 1;
}
</style>
