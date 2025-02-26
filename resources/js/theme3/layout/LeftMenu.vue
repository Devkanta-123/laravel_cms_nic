<template>
    <div class="left-menu">
        <ul class="list-group">
            <li
                class="list-group-item"
                v-for="item in menuItems"
                :key="item.id"
            >
                <router-link :to="item.link" class="list-group-item-action">
                    {{ item.name }}
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref } from "vue";

// Dummy data for the left menu items
const menuItems = ref([
    { id: 1, name: "Organisation Chart", link: "/organisation-chart" },
    { id: 2, name: "Regulations 1972", link: "/regulations-1972" },
    { id: 3, name: "RTI ACT", link: "/rti-act" },
    { id: 4, name: "Home", link: "/" },
]);
</script>

<style scoped>
.left-menu {
    width: 250px;
    background-color: #f8f9fa;
    border-right: 1px solid #ddd;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
}

.left-menu li .list-group-item-action {
    text-decoration: none;
    color: #333;
    font-size: 16px;
    display: block;
    padding: 10px 15px;
    border-radius: 0.25rem;
}

.left-menu li:hover {
    background-color: #e9ecef;
}
</style>
