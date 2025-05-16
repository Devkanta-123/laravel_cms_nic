<template>
    <div class="container">
        <div class="banner-container">
            <!-- Banner Track -->
            <div class="banner-track" :style="{ transform: `translateX(${currentTranslate}px)` }"
                @touchstart="handleTouchStart" @touchmove="handleTouchMove" @touchend="handleTouchEnd">
                <!-- Dynamically generate logos -->
                <div v-for="(banner, index) in logos" :key="banner.id" class="banner-item">
                    <img :src="banner.image" :alt="`Banner ${index + 1}`" />
                </div>
            </div>

            <!-- Navigation Buttons -->
            <button class="prev-btn" @click="prevSlide" :disabled="currentIndex === 0">
                &lt;
            </button>
            <button class="next-btn" @click="nextSlide" :disabled="currentIndex === logos.length - 1">
                &gt;
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const logos = ref([]); // Holds the banner data
const currentTranslate = ref(0); // Current translation value
const startX = ref(0); // Starting X position of touch
const currentIndex = ref(0); // Current index of the banner
const bannerWidth = 300; // Width of each banner
const loadinglogos = ref(false); // Loading state for logos
const cacheDuration = 10 * 60 * 1000; // Cache duration (10 minute)
let autoSlideInterval = null;

// Fetch logos from API

const fetchlogos = async () => {
    const now = new Date().getTime();

    // Check if cached data exists and is valid
    const cachedData = localStorage.getItem("logos");
    const cachedTimestamp = localStorage.getItem("logosTimestamp");

    if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
        console.log('Using cached logos from localStorage');
        logos.value = JSON.parse(cachedData); // Use cached logos data
        return;
    }

    loadinglogos.value = true;

    try {
        const response = await axios.get("/get_logo",{params:{flag:'A'}});

        if (response.data && Array.isArray(response.data)) {
            // Fetch images as base64 encoded strings
            const bannerData = await Promise.all(response.data.map(async (banner) => {
                const imageFilePath = "/storage/" + banner.image.replace("public/", ""); // Build the image URL

                // Fetch image as a base64 encoded string
                const imageResponse = await fetch(imageFilePath);
                const imageBlob = await imageResponse.blob();
                const imageBase64 = await new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.onerror = reject;
                    reader.readAsDataURL(imageBlob);
                });

                return {
                    ...banner,
                    image: imageBase64 // Store the image as base64
                };
            }));

            logos.value = bannerData;

            // Cache the logos data and timestamp in localStorage
            localStorage.setItem("logos", JSON.stringify(logos.value));
            localStorage.setItem("logosTimestamp", now.toString());
        } else {
            console.error("Invalid data format");
        }
    } catch (error) {
        console.error("Failed to fetch logos:", error);
    } finally {
        loadinglogos.value = false;
    }
};

const startAutoSlide = () => {
    autoSlideInterval = setInterval(() => {
        if (currentIndex.value < logos.value.length - 1) {
            nextSlide();
        } else {
            currentIndex.value = 0; // Reset to first slide after the last one
            currentTranslate.value = 0;
        }
    }, 3000); // Change every 3 seconds
};

// Touch Handlers
const handleTouchStart = (event) => {
    startX.value = event.touches[0].clientX;
};

const handleTouchMove = (event) => {
    const deltaX = event.touches[0].clientX - startX.value;
    currentTranslate.value =
        -currentIndex.value * bannerWidth + deltaX;
};

const handleTouchEnd = () => {
    const endTranslate = -currentIndex.value * bannerWidth;
    const delta = currentTranslate.value - endTranslate;

    if (delta > bannerWidth / 3 && currentIndex.value < logos.value.length - 1) {
        currentIndex.value++;
    } else if (delta < -bannerWidth / 3 && currentIndex.value > 0) {
        currentIndex.value--;
    }
    currentTranslate.value = -currentIndex.value * bannerWidth;
};

// Button Handlers
const nextSlide = () => {
    if (currentIndex.value < logos.value.length - 1) {
        currentIndex.value++;
        currentTranslate.value = -currentIndex.value * bannerWidth;
    }
};

const prevSlide = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
        currentTranslate.value = -currentIndex.value * bannerWidth;
    }
};

// Fetch logos on mount
onMounted(fetchlogos, startAutoSlide());
onBeforeUnmount(() => {
    // Clean up the interval when the component is destroyed
    if (autoSlideInterval) {
        clearInterval(autoSlideInterval);
    }
});
</script>

<style scoped>
.container {
    width: 100%;
    overflow: hidden;
    position: relative;
}

.banner-container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.banner-track {
    display: flex;
    transition: transform 0.3s ease-in-out;
}

.banner-item {
    min-width: 300px;
    /* Adjust banner width */
    height: 150px;
    /* Adjust banner height */
    margin-right: 10px;
}



.prev-btn,
.next-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
}

.prev-btn {
    left: 10px;
}

.next-btn {
    right: 10px;
}

.prev-btn:disabled,
.next-btn:disabled {
    background-color: rgba(0, 0, 0, 0.2);
    cursor: not-allowed;
}
</style>