<template>
    <div class=" container-fluid mb-4 " @click="togglePanel()">
        <div class="form-control bg-info">
            {{section.page_section_name}}
               Menu: 
       
            <i :class="isPanelActive ? 'fas fa-minus float-right' : 'fas fa-plus float-right'"></i>
    </div>
    </div>

    <div class="panel d-none p-2" :class="{'d-block':isPanelActive}">
        <div class="panel-content">
            <SlidesManager v-if="section.page_section_name=='Carousel' "></SlidesManager>
            <WebsiteDetails v-if="section.page_section_name=='WebsiteDetails' "></WebsiteDetails>
            <LatestNews v-if="section.page_section_name=='LatestNews' "></LatestNews>
            <Notifications v-if="section.page_section_name=='Notifications' "></Notifications>
            <Cards v-if="section.page_section_name=='Cards' "></Cards>
            <GovtLogos v-if="section.page_section_name=='GovtLogos' "></GovtLogos>
            <Gallery v-if="section.page_section_name=='Gallery' "></Gallery>
            <Description v-if="section.page_section_name=='Description' " :menu="menu"></Description>
            <Content v-if="section.page_section_name=='TableInfo' "> </Content>
        </div>
    </div>
</template>

<script setup>
    import { reactive, ref , defineProps } from 'vue';
    import SlidesManager from '@/components/Admin/home_components/SlidesManager.vue';
    import WebsiteDetails from '@/components/Admin/home_components/WebsiteDetails.vue';
    import LatestNews from '@/components/Admin/home_components/LatestNews.vue';
    import Notifications from '@/components/Admin/home_components/Notifications.vue';
    import Cards from '@/components/Admin/home_components/Cards.vue';
    import GovtLogos from '@/components/Admin/home_components/GovtLogos.vue';
    import Gallery from '@/components/Admin/home_components/Gallery.vue';
    import Description from '@/components/Admin/components/Description.vue';
    import Content from '@/components/Admin/components/Content.vue';

    defineProps({
        section: Object,
        menu: String
    })

    const isPanelActive = ref(false);


    const togglePanel = () => {
       
        isPanelActive.value = !isPanelActive.value;

    }
</script>
