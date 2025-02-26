<template>

   
      <Header :language="language" @languageChanged="handleLanguageChanged"></Header>
     
      <Carousel></Carousel>
    
      <br><br><br>

      <HomeParagraph :language="language" />
      
      <Footer :language="language"
        text1="This is the first line of text." 
        text2="This is the second line of text." 
        :logos="logos"
      ></Footer>


</template>
<script setup>
import { ref  } from 'vue';
import { useRoute } from 'vue-router';
import Header from './layout/Header.vue';
import Footer from './layout/Footer.vue';
import Carousel from './components/Carousel.vue';
import Cards from './components/Cards.vue';
import HomeParagraph from  './components/HomeParagraph.vue';
import PageContent from './layout/PageContent.vue';
import Page from './layout/Page.vue';

const language = ref('English');
const route = useRoute();
const id = route.params.id;
const componentToShow = id == 2 ? PageContent: PageContent;

// const cards = ref([
//   { imageSrc: 'storage/slides/smb.jpg', title: 'Card 1' },
//   { imageSrc: 'storage/slides/smb2.jpg', title: 'Card 2' },
//   { imageSrc: 'storage/slides/smb3.jpg', title: 'Card 3' }
// ]);

const logos = [
  { src: 'storage/logo/rti.jpg', alt: 'Logo 1' },
  { src: 'storage/logo/megpgrams.jpg', alt: 'Logo 2' },
  { src: 'storage/logo/meghalayagovin.png', alt: 'Logo 3' }
];

const handleLanguageChanged = (l) => {
  language.value = l; 
};

</script>
<style scoped>
.content {
  padding-top: 70px; 
}
.card-list {
  display: flex;
  justify-content: space-around;
  gap: 2px; 
}
</style>