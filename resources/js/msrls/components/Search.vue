<template>
    <div class="category-form-wrap">
        <form class="header-form" @submit.prevent="submitSearch">
            <input class="form-control" type="text" v-model="searchQuery" placeholder="Search here...">
            <button class="submit ed-primary-btn">
                <i class="fa-solid fa-magnifying-glass"></i> Search
            </button>
        </form>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex'; // Import Vuex's useStore

const store = useStore(); // Initialize the store

// Use computed to access the searchQuery from Vuex
const searchQuery = computed({
    get: () => store.state.searchQuery,
    set: (value) => store.dispatch('updateSearchQuery', value)
});

function submitSearch() {
    
    console.log('Searching for:', searchQuery.value);
    // You can also trigger other actions like filtering content or sending the query to an API
}
</script>