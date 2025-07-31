<template>
    <!-- <Loader v-if="isLoading" /> -->
    <br>
    <div class="content ml-4 mr-4">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Pages</h4>
                        <span>All Pages</span>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="">
                        <li class="breadcrumb-item"><a href="">Pages</a></li>
                        <li class="breadcrumb-item active "><a class="text-primary">All Pages</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content ml-4 mr-4">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4 class="mt-2">Pages</h4>

                </div>
                <div class="card-body">
                    <table class="table table-responsive-lg mb-0">
                        <thead>
                            <tr>
                                <th> <strong> S.NO. </strong> </th>
                                <th> <strong> MENU NAME <i class="fa fa-sort asc"></i> </strong> </th>
                                <th> <strong> MENU TYPE <i class="fa fa-sort asc"></i> </strong> </th>
                                <th> <strong> ORDER <i class="fa fa-sort asc"></i> </strong> </th>
                                <th class=""> <strong> ACTIONS </strong> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(menu, index) in menus" :key="menu.id">
                                <td>
                                    {{ index + 1 }}
                                </td>
                                <td>
                                    {{ menu.menu_name }}
                                </td>
                                <td>
                                    {{ menu.menu_master.menu_name }}
                                    <span v-if="menu.parent_name">->
                                        {{ menu.parent_name }}
                                    </span>
                                </td>
                                <td>
                                    {{ menu.order }}
                                </td>

                                <td class="">
                                    <a href="#" @click.prevent="editMenu(menu)"
                                        class="btn btn-primary shadow btn-xs me-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>

    <!-- <div class="content ml-4 mr-4">
        <div class="container-fluid">
            <div class="row table-agile-info">
                    <div class=" col-sm-12">
                       
                        <div class="card-body table-responsive">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group p-card-body">
                                        <Field as="select" class="form-control"  name="menu" id="menu" @change="getPageDetails( $event.target.value)">
                                            <option value="" disabled>Select a Menu</option>
                                            <template v-for="menu in sub_menus" :key="menu.id">
                                                <option :value="menu.id">{{ menu.menu_name }}</option>
                                                <template v-if="menu.submenus && menu.submenus.length > 0">
                                                    <template v-for="subMenu in menu.submenus" :key="subMenu.id">
                                                        <option :value="subMenu.id">{{ '&nbsp;&nbsp;&nbsp;&nbsp;> ' + subMenu.menu_name }}</option>
                                                        <template v-if="subMenu.submenus && subMenu.submenus.length > 0">
                                                            <template v-for="thirdMenu in subMenu.submenus" :key="thirdMenu.id">
                                                                <option :value="thirdMenu.id">{{ '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;> ' + thirdMenu.menu_name }}</option>
                                                            </template>
</template>
</template>
</template>
</template>
</Field>
</div>
</div>
</div>
</div>
</div>

<div class="col-12 mr-5">
    <div class="page-sections">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead class="">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Section Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(section, index) in pageSections" :key="section.id">
                                    <td>
                                        {{ ++index }}
                                    </td>
                                    <td>
                                        {{section.page_section_name}}
                                    </td>

                                    <td>
                                        <a href="#" @click="openModal(section)"><i class="fa fa-edit"></i></a>
                                        <a href=""><i class="fa fa-trash text-danger ml-2"></i></a>
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan=4 align="center">
                                        <button type="button" class="btn btn-primary mb-2">
                                            <i class="fa fa-plus-circle mr-1"></i>
                                            Add New Section
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div> -->

    <ModalComponent :isOpen="isModalOpen" :title="modalTitle" :component="modalComponent" :section="selectedSection"
        :menu="menu_id" @close="isModalOpen = false" />
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from 'axios';
import { useRouter } from 'vue-router';
import * as yup from 'yup';
import { Form, Field, useSetFieldError } from 'vee-validate';
import { useToastr } from '../../toaster.js';
import SlidesManager from '../../components/page_components/SlidesManager.vue';
import WebsiteDetails from '../../components/page_components/WebsiteDetails.vue';
import LatestNews from '../../components/page_components/LatestNews.vue';
import Notification from '../../components/page_components/NoticeBoard.vue';
import Cards from '../../components/page_components/Cards.vue';
import Banner from '../../components/page_components/Banner.vue';
import Gallery from '../../components/page_components/Gallery.vue';
import Paragraph from '../../components/page_components/Paragraph.vue';
import Content from '../../components/page_components/Content.vue';
import ModalComponent from './ModalComponent.vue';
import Loader from '../../components/Loader.vue';

const pageSections = ref([]);
const menus = ref([]);
const sub_menus = ref([]);
const menu_id = ref();

const isModalOpen = ref(false);
const modalTitle = ref('');
const modalComponent = ref(null);
const selectedSection = ref(null);
const currentSerial = 1;
const router = useRouter();
const isLoading = ref(true);

const getPageSection = () => {
    
    menus.value = '';
    sub_menus.value = '';
    axios.get('/api/get_menus')
        .then((response) => {
            menus.value = response.data[0];
            sub_menus.value = response.data[1];
            console.log(menus.value);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

// Compute structured menus
const editMenu = (menu) => {
    router.push({
        name: 'PagesForm',
        params: { menuId: menu.id, menuName: menu.menu_name,parentID:menu.parent }
    });
};

onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 500);
    getPageSection();

});

</script>

<style scoped>
.table th:last-child,
.table td:last-child {
    width: 150px;
    text-align: center;
}
</style>