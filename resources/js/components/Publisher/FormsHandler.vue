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
import Paragraph from './Forms/Paragraph.vue'
import NoticeBoard from './Forms/NoticeBoard.vue'
import Cards from './Forms/Cards.vue'
import Gallery from './Forms/Gallery.vue'
import WhosWho from './Forms/WhosWho.vue'
import Faqs from './Forms/Faqs.vue'
import Map from './Forms/Map.vue'
import Logo from './Forms/Logo.vue'


// Dynamically resolve the component to load based on menuName
const currentComponent = computed(() => {
  
  switch (props.menuName?.toLowerCase()) {
    case 'carousel':
      return Carousal
    case 'latest news':
      return LatestNews
    case 'paragraph':
      return Paragraph
    case 'notice board':
      return NoticeBoard
    case 'cards':
      return Cards
    case 'gallery':
      return Gallery
    case 'whoswho':
      return WhosWho
    case 'faq':
      return Faqs
    case 'map':
      return Map
    case 'logo':
      return Logo

    // Add more cases here:
    // case 'gallery': return Gallery
    default:
      return null
  }
})
</script>
