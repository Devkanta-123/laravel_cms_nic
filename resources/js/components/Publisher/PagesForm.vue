<template>
    <Loader v-if="isLoading" />
    <br>
    <div class="content ml-4 mr-4">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Pages / {{ props.menuName }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="">
                        <li class="breadcrumb-item"><a href="">Pages</a></li>
                        <li class="breadcrumb-item active "><a class="text-primary">{{ props.menuName }} </a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content ml-4 mr-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="in-box MagicEditorBox ">
                                <div class="card">
                                    <div class="card-header row">
                                        <div class="col-10">
                                            <h4 class=" mt-1">{{ props.menuName }} </h4> Components

                                        </div>
                                        <div class="row align-items-center">

                                            <div class="col-auto">
                                                <a href="#" @click="onBack()"
                                                    class="btn btn-primary btn-sm pl-3 pr-3 pt-2 pb-2">
                                                    <i class="fa fa-arrow-left"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <div class="form-group ui-sortable" id="pageComponentContainer">

                                            <!-- Show message if no components are found -->
                                            <p v-if="pageSections.length === 0" class="text-center text-muted">
                                                No components found under this menu.
                                            </p>
                                            <template v-if="groupedSections.length">
                                                <div v-for="(group, rowIndex) in groupedSections" :key="rowIndex"
                                                    class="row mb-4">
                                                    <div v-for="section in group" :key="section.id"
                                                        class="col-xl-3 col-lg-6 col-md-6">
                                                        <div class="card card-statistics h-100 mb-4">
                                                            <div class="card-body">
                                                                <div class="clearfix">
                                                                    <div>
                                                                        <img :src="`/storage/${section.page_section_master.icon}`"
                                                                            alt="section icon"
                                                                            style="width: 60px; height: 50px;" />
                                                                    </div>
                                                                    <div class="float-end text-end">
                                                                        <p class="card-text text-dark">{{
                                                                            section.page_section_name }}</p><a
                                                                            @click="openPageSection(section)"
                                                                            class="btn btn-primary shadow btn-xs me-1"><i
                                                                                class="fas fa-pencil-alt"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>

</template>

<script setup>

import { ref, onMounted, computed } from "vue";
import axios from 'axios';
import { useRouter, createRouter, createWebHistory } from 'vue-router'
const router = useRouter()
import LatestNews from '@/components/page_components/LatestNews.vue';
import NoticeBoard from '@/components/page_components/NoticeBoard.vue';
import Cards from '@/components/page_components/Cards.vue';
import Banner from '../../components/page_components/Banner.vue';
import Gallery from '@/components/page_components/Gallery.vue';
import Paragraph from '../../components/page_components/Paragraph.vue';
import Content from '@/components/page_components/Content.vue';
import FAQ from "../../components/page_components/FAQ.vue";
import Map from "../../components/page_components/Map.vue";
import Logo from "../../components/page_components/Logo.vue";
import WhosWho from "../../components/page_components/WhosWho.vue";
import Loader from '../../components/Loader.vue';
import { useToastr } from '../../toaster.js';

const props = defineProps(['menuId', 'menuName']);
const allComponents = ref({});
const pageSections = ref([]);
const isModalOpen = ref(false);
const modalTitle = ref('');
const menu_id = ref('');
const selectedSection = ref(null);
const isLoading = ref(true);
const toastr = useToastr();

const getPageDetails = () => {
    debugger;
    axios.post(`/api/get_page_details/${props.menuId}`)
        .then((response) => {
            pageSections.value = response.data
            menu_id.value = props.menuId
        })
        .catch((error) => {
            console.error('Error:', error)
        })
}

// Group the items into rows of 4
const groupedSections = computed(() => {
    const chunkSize = 4
    const result = []
    for (let i = 0; i < pageSections.value.length; i += chunkSize) {
        result.push(pageSections.value.slice(i, i + chunkSize))
    }
    return result
})

const getAllComponents = () => {
    axios.get('/api/getAllComponents')
        .then(response => {
            allComponents.value = response.data;
            console.log(response.data);
        })
        .catch(error => {
            console.error('Error fetching menu data:', error);
        });
};


const onBack = () => {
    router.push('/publisher/pages')
}
const openPageSection = (section) => {
    router.push({
        name: 'PublisherFormsHandler',
        params: { menuId: section.menu_id, menuName: section.page_section_name }
    });
}
const addComponent = (component) => {
    const payload = {
        menu_id: menu_id.value,
        component_id: component.id,
        component_name: component.page_section_name
    };

    axios.post('/api/save_component', payload)
        .then(response => {

            toastr.success('Component added successfully');
            closeModal();
            setTimeout(() => {
                location.reload();
            }, 1000);

        })
        .catch(error => {
            if (error.response.status == 409) {
                toastr.error('Component already exist');
            }
            else
                console.error('Error saving component:', error);
            closeModal();
            setTimeout(() => {
                location.reload();
            }, 500);
        });
};

const deleteComponent = (component) => {
    const payload = {
        menu_id: menu_id.value,
        component_id: component.id,
        component_name: component.page_section_name
    };

    axios.post('/api/delete_component', payload)
        .then(response => {

            toastr.success('Component deleted successfully');
            setTimeout(() => {
                location.reload();
            }, 1000);

        })
        .catch(error => {
            console.error('Error saving component:', error);
        });
};

onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 500);
    getPageDetails();
    getAllComponents();
});

</script>

<style scoped>
/* Add margin after every 4 cards (visually simulates row gap) */
</style>