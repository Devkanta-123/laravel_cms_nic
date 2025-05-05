<template>
  <div>
    <!-- Conditionally render the dynamic component -->
    <component :is="currentComponent" v-if="currentComponent" :menu-id="props.menuId" />
    <div v-else>
      <p>No component found for menu name "{{ props.menuName }}"</p>
    </div>
  </div>
</template>


<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
const router = useRouter()

const props = defineProps(['menuId', 'menuName'])

import Carousal from './Forms/Carousal.vue'
import LatestNews from './Forms/LatestNews.vue'
// Add more page imports here

// Dynamically resolve the component to load based on menuName
const currentComponent = computed(() => {
  debugger;
  switch (props.menuName?.toLowerCase()) {
    case 'carousel':
      return Carousal
    case 'latest news':
      return LatestNews

    // Add more cases here:
    // case 'gallery': return Gallery
    default:
      return null
  }
})
</script>
