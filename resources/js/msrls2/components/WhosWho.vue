<template>
    <section class="team__area-four">
        <div class="container" style="margin-top: -160px;">
            <div v-if="whoswhoData.length === 0">
                <p>No data available.</p>
            </div>

            <template v-else>
                <!-- State Level -->
                <div v-if="stateLevel.length > 0">
                    <h4 class="title">State Level</h4>
                    <div class="row justify-content-center">
                        <WhosWhoCard v-for="person in paginatedStateLevel" :key="person.id" :person="person" />
                    </div>
                </div>

                <!-- District Level -->
                <div v-if="districtLevel.length > 0">
                    <h4 class="title">District Level</h4>
                    <div class="row justify-content-center">
                        <WhosWhoCard v-for="person in paginatedDistrictLevel" :key="person.id" :person="person" />
                    </div>
                </div>
                <br>

                <!-- Block Level -->
                <div v-if="blockLevel.length > 0">
                    <h4 class="title">Block Level</h4>
                    <div class="row justify-content-center">
                        <WhosWhoCard v-for="person in paginatedBlockLevel" :key="person.id" :person="person" />
                    </div>
                </div>

                <!-- Pagination -->
                <nav v-if="totalPages > 1" aria-label="Page navigation example">
                    <ul class="pagination list-wrap">
                        <li class="page-item" :class="{ disabled: currentPage === 1 }">
                            <a class="page-link" href="#" @click.prevent="changePage(1)">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        </li>
                        <li v-for="page in totalPages" :key="page" class="page-item"
                            :class="{ active: page === currentPage }">
                            <a class="page-link" href="#" @click.prevent="changePage(page)">
                                {{ page }}
                            </a>
                        </li>
                        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                            <a class="page-link" href="#" @click.prevent="changePage(totalPages)">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </template>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import WhosWhoCard from "../components/WhosWhoCard.vue";

const whoswhoData = ref([]);
const stateLevel = ref([]);
const districtLevel = ref([]);
const blockLevel = ref([]);

const cacheDuration = 10 * 60 * 1000; // 10 minutes
const cacheKey = "whosWhoData";
const timestampKey = "whosWhoDataTimestamp";

const currentPage = ref(1);
const itemsPerPage = 4;

const fetchWhosWho = async () => {
    const now = Date.now();
    const cachedData = localStorage.getItem(cacheKey);
    const cachedTimestamp = localStorage.getItem(timestampKey);

    if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
        console.log("Using cached WhosWho data");
        whoswhoData.value = JSON.parse(cachedData);
        categorizeData();
        return;
    }

    try {
        const response = await axios.get("/get_whoswho");
        whoswhoData.value = response.data;

        localStorage.setItem(cacheKey, JSON.stringify(response.data));
        localStorage.setItem(timestampKey, now.toString());

        categorizeData();
    } catch (error) {
        console.error("Failed to fetch WhosWho data:", error);
    }
};

const categorizeData = () => {
    stateLevel.value = whoswhoData.value.filter(person => person.level_id === 1);
    districtLevel.value = whoswhoData.value.filter(person => person.level_id === 2);
    blockLevel.value = whoswhoData.value.filter(person => person.level_id === 3);
};

// Pagination logic
const totalPages = computed(() => {
    const statePages = Math.ceil(stateLevel.value.length / itemsPerPage);
    const districtPages = Math.ceil(districtLevel.value.length / itemsPerPage);
    const blockPages = Math.ceil(blockLevel.value.length / itemsPerPage);

    return Math.max(statePages, districtPages, blockPages); // Maximum required pages
});

const paginatedStateLevel = computed(() => {
    if ((currentPage.value - 1) * itemsPerPage >= stateLevel.value.length) return [];
    const start = (currentPage.value - 1) * itemsPerPage;
    return stateLevel.value.slice(start, start + itemsPerPage);
});

const paginatedDistrictLevel = computed(() => {
    if ((currentPage.value - 1) * itemsPerPage >= districtLevel.value.length) return [];
    const start = (currentPage.value - 1) * itemsPerPage;
    return districtLevel.value.slice(start, start + itemsPerPage);
});

const paginatedBlockLevel = computed(() => {
    if ((currentPage.value - 1) * itemsPerPage >= blockLevel.value.length) return [];
    const start = (currentPage.value - 1) * itemsPerPage;
    return blockLevel.value.slice(start, start + itemsPerPage);
});

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};


onMounted(fetchWhosWho);
</script>
