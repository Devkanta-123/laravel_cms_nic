<template>
    <!-- <Loader v-if="isLoading" /> -->
    <br>
    <br>
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
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="in-box MagicEditorBox ">
                                <div class="card">
                                    <div class="card-header row">
                                        <div class="col-10">
                                            <h4 class=" mt-1">{{ props.menuName }} </h4> Components

                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-auto" style="margin-left: -60px;">
                                                <a @click="onBack" class="btn btn-danger btn-sm pt-2 pb-2"
                                                    style="height: 40px; cursor: pointer;">
                                                    Back
                                                </a>
                                            </div>
                                            <div class="col-auto">
                                                <a href="#" @click="openComponentsModal()" data-bs-toggle="modal"
                                                    data-bs-target="#AddElement"
                                                    class="btn btn-primary btn-sm pl-3 pr-3 pt-2 pb-2">
                                                    <i class="fa fa-plus"></i>
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
                                            <!-- Loop through sections if available -->
                                            <template v-else v-for="(section) in pageSections" :key="section.id">
                                                <div class="col-lg-12 me-element-item mb-2">
                                                    <div class="icon-bx-wraper left style-1 m-b30">
                                                        <div class="d-flex align-items-center">
                                                            <div class="icon-lg me-2">
                                                                <!-- <img :src="`/storage/${section.icon}`" alt="Image"> -->
                                                            </div>
                                                            <p class="m-b15"><strong>{{ section.page_section_name
                                                                    }}</strong></p>
                                                        </div>
                                                        <div class="icon-content">
                                                            <a href="#" @click="openModal(section)"
                                                                class="Me-EditElement btn btn-primary shadow btn-xs sharp me-1">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                            <a href="javascript:void(0);"
                                                                @click="deleteComponent(section)"
                                                                class="ME-DeleteElement btn btn-danger shadow btn-xs sharp me-1">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
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
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card accordion accordion-rounded-stylish accordion-bordered"
                                id="accordion-publish">
                                <div class="card-header justify-content-start accordion-header"
                                    data-bs-toggle="collapse" data-bs-target="#with-publish" aria-expanded="true">
                                    <h4 class="card-title">PAGE TITLE</h4>
                                    <span class="accordion-header-indicator"></span>
                                </div>
                                <div class="accordion__body collapse show" id="with-publish"
                                    data-bs-parent="#accordion-publish" style="">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <div class="form-group col-md-12">
                                                    <input type="text" name="data[Page][title]"
                                                        class="form-control MakeSlug" id="ContentTitle"
                                                        placeholder="Title" :value="props.menuName" rel="slug" readonly>
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

        </div>
    </div>


    <div class="modal fade" id="AddElement" aria-modal="true" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header d-block">
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="modal-title">Add Components</h4>
                        <button type="button" @click.prevent="closeModal()" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="modal-body">
                    <ul class="ME-ElementList row">
                        <template v-for="(component) in allComponents" :key="component.id">

                            <li class="col-lg-2 col-sm-4 p-0 shadow-effect mt-3 ml-3 hover-pop">
                                <a href="javascript:void(0);" class="ME-AddElement" @click="addComponent(component)"
                                    data-element="" data-element-image="{{component.icon}}"
                                    data-element-name="{{component.page_section_name}}">
                                    <div class="icon-bx-wraper text-center style-2 m-b30 ">
                                        <div class="icon-lg position-relative ">
                                            <img class=" border hover-pop " :src="`/storage/${component.icon}`"
                                                alt="Image">
                                            <div class="rounded" style="top: 77.6875px; left: 58.0156px;"></div>
                                        </div>
                                        <div class="icon-content">
                                            <h4 class="dz-title m-b15">
                                                {{ component.page_section_name }}
                                            </h4>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        <ModalComponent :isOpen="isModalOpen" :title="modalTitle" :component="modalComponent" :section="selectedSection"  :menuName="menuName"
            :menu="menu_id" @close="isModalOpen = false" />
</template>

<script setup>

import { ref, onMounted, } from "vue";
import axios from 'axios';
import { useRouter } from 'vue-router'
const router = useRouter()
import ModalComponent from './ModalComponent.vue';
import SlidesManager from '../../components/page_components/SlidesManager.vue';
import WebsiteDetails from '../../components/page_components/WebsiteDetails.vue';
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
import Feedback from "../../components/page_components/Feedback.vue";
import Swal from 'sweetalert2';
import WhosWho from "../../components/page_components/WhosWho.vue";
import Loader from '../../components/Loader.vue';
import { useToastr } from '../../toaster.js';

const props = defineProps(['menuId','parentID','menuName']);
const allComponents = ref({});
const pageSections = ref([]);
const isModalOpen = ref(false);
const modalTitle = ref('');
const menu_id = ref('');
const menuName = ref('');
const modalComponent = ref(null);
const selectedSection = ref(null);
const isLoading = ref(true);
const toastr = useToastr();
const getPageDetails = () => {
    debugger;
    if (props.parentID > 0) {
        axios.post('/api/get_page_details/' + props.parentID)
            .then((response) => {
                pageSections.value = response.data;
                menu_id.value = props.parentID;
                menuName.value = props.menuName;
                console.log("get_page_details data (using parentID)", response.data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    } else if (props.parentID !== 0) {
        axios.post('/api/get_page_details/' + props.menuId)
            .then((response) => {
                pageSections.value = response.data;
                menu_id.value = props.menuId;
                menuName.value = props.menuName;
                console.log("get_page_details data (using menuId)", response.data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
}

const getAllComponents = () => {
    axios.get('/api/getAllComponents')
        .then(response => {
            allComponents.value = response.data;
            console.log("allComponents data ", response.data);

        })
        .catch(error => {
            console.error('Error fetching menu data:', error);
        });
};

const closeModal = () => {
    $('#AddElement').modal('hide');
}
const openComponentsModal = () => {
    $('#AddElement').modal('show');
}

const onBack = () => {
    router.push('/admin/pages')
}
const openModal = (section) => {
    debugger;
    // selectedSection.value = section;
  selectedSection.value = section;
    modalTitle.value = section.page_section_name;
    switch (section.page_section_name) {
        case 'Carousel':
            modalComponent.value = SlidesManager;
            break;
        case 'Home Page Paragraph':
            modalComponent.value = WebsiteDetails;
            break;
        case 'Latest News':
            modalComponent.value = LatestNews;
            break;
        case 'Notice Board':
            modalComponent.value = NoticeBoard;
            break;
        case 'Cards':
            modalComponent.value = Cards;
            break;
        case 'Banner':
            modalComponent.value = Banner;
            break;
        case 'Gallery':
            modalComponent.value = Gallery;
            break;
        case 'Paragraph':
            modalComponent.value = Paragraph;
            break;
        case 'Banner':
            modalComponent.value = Paragraph;
            break;
        case 'FAQ':
            modalComponent.value = FAQ;
            break;
        case 'WhosWho':
            modalComponent.value = WhosWho;
            break;
        case 'Map':
            modalComponent.value = Map;
            break;

        case 'Logo':
            modalComponent.value = Logo;
            break;

     case 'Feedback':
            modalComponent.value = Feedback;
            break;


        default:
            modalComponent.value = null;
    }
    isModalOpen.value = true;
};

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

// const deleteComponent = (component) => {
//     const payload = {
//         menu_id: menu_id.value,
//         component_id: component.id,
//         component_name: component.page_section_name
//     };  

//     axios.post('/api/delete_component', payload)
//         .then(response => {

//             toastr.success('Component deleted successfully');
//             setTimeout(() => {
//                 location.reload();
//             }, 1000);

//         })
//         .catch(error => {
//             console.error('Error saving component:', error);
//         });
// };

// 🔥 DELETE COMPONENT LOGIC
const deleteComponent = async (component) => {
    debugger;
    const usedInDetails = pageSections.value.some(section =>
        section.menu_id === component.menu_id
    );

    const message = usedInDetails
        ? "This component is used in page details. Are you sure you want to delete it? This action cannot be undone."
        : "Are you sure you want to delete this component? This action cannot be undone.";

    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            await axios.post('/api/delete_component', {
                component_id: component.id,
                menu_id: props.menuId,
                page_section_master_id: component.page_section_master.id,
                has_dependency: usedInDetails
            });

            await getPageDetails();
            await getAllComponents();

            Swal.fire('Deleted!', 'Component has been deleted.', 'success');
        } catch (error) {
            console.error('Error deleting component:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
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
.hover-pop {
    transition: transform 0.3s ease-in-out;
}

.hover-pop:hover {
    transform: scale(1.1);
}

.shadow-effect {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease-in-out;
}

.shadow-effect:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2), 0 12px 40px rgba(0, 0, 0, 0.1);
}

.shadow-effect.selected,
.shadow-effect.selected:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2), 0 12px 40px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease-in-out;
}

.back-button {
    display: inline-block;
    margin-left: -60px;
    height: 40px;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    background-color: #28a745;
    /* Bootstrap 'btn-success' */
    color: white;
    font-size: 0.875rem;
    /* btn-sm */
    border-radius: 0.2rem;
    text-align: center;
    line-height: 1.5;
    text-decoration: none;
    cursor: pointer;
    /* 👈 Fix the cursor */
}

.back-button:hover {
    background-color: #218838;
    /* Darker green on hover */
}
</style>