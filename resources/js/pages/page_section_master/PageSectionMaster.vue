<template>
    <br>
    <div class="content ml-4 mr-4">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Page Section </h4>
                        <span>All Component Master</span>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="">
                        <li class="breadcrumb-item"><a href="">Master Component Section</a></li>
                        <li class="breadcrumb-item active "><a class="text-primary">All Component Master</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content ml-4 mr-4">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h4>Master Component</h4>
                    <div align="right">

                        <button type="button" class="btn btn-primary mb-2 p-2" @click="openFooterModal">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Add Components
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table ref="dataTable" class="table table-responsive-lg mb-0">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Page Section Name <i class="fa fa-sort asc"></i></th>
                                <th>Icon <i class="fa fa-sort asc"></i></th>
                                <th>Action<i class="fa fa-sort asc"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in pagemasterdata" :key="item.id">
                                <td width="10%">{{ index + 1 }}</td>
                                <td width="20%">{{ item.page_section_name }}</td>
                                <td width="20%">
                                    <img :src="'/storage/' + item.icon" alt="message user image" width="50"
                                        height="50" />


                                </td>
                                <td width="20%">
                                    <a href="#" @click.prevent="editComponent(item)"
                                        class="btn btn-primary shadow btn-xs  me-1"><i
                                            class="fas fa-pencil-alt"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>

    <!-- Page Section Modal -->
    <div class="modal fade" id="pagesectionModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="pagesectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pagesectionModalLabel">Add Component Master</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handlePageSectionSubmit" v-slot="{ errors }" :initial-values="pageformValues">
                    <div class="modal-body">
                        <!-- Page Section Name -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pagesectionname">Page Section Name</label>
                                    <Field type="text" class="form-control" v-model="pageformValues.pagesectionname"
                                        name="pagesectionname" id="pagesectionname" placeholder="Page Section Name"
                                        autocomplete="off" :class="{ 'is-invalid': errors.pagesectionname }" />
                                    <span class="invalid-feedback">{{ errors.pagesectionname }}</span>
                                </div>
                            </div>

                            <!-- File Upload -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fileInput" class="form-label"> Icon (Upload Image Only)</label>
                                    <input type="file" id="fileInput" ref="fileInput" @change="handleFileChange"
                                        accept=".png, .jpg, .jpeg, .svg" :disabled="isFileDisabled" />
                                    <div v-if="errors.file" class="text-danger">{{ errors.file }}</div>
                                </div>
                            </div>
                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </Form>

            </div>
        </div>
    </div>

    <!-- Page Section Modal End -->
</template>
<script setup>
import $ from "jquery";
import "datatables.net-bs4"; // Ensure you have this installed
import { ref, onMounted, computed, reactive, nextTick } from "vue";
import axios from 'axios';
import { Form, Field } from 'vee-validate';
import { useToastr } from '../../toaster.js';
import Swal from 'sweetalert2';
const pagemasterdata = ref([]);
const toastr = useToastr();
const form = ref(null);
const header = ref([]);
const formValues = ref({ title: '', short_form: '', logo: '', id: '', logoUrl: null, });
const editing = ref(false);
const footerValues = ref([]);
const showFooterContent = ref(false);
const editingFooterItem = ref(false);
const menus = ref([]);
const isFileDisabled = ref(false); // Define isFileDisabled
const dataTable = ref(null);




const pageformValues = reactive({
    id: null,
    pagesectionname: "",
    file: null,
    fileUrl: null, // For displaying the existing file preview
});

// const handlePageSectionSubmit = async (values, actions) => {
//     let formData = new FormData();
//     formData.append("pagesectionname", values.pagesectionname || "");

//     if (pageformValues.file) {
//         formData.append("file", pageformValues.file);
//     }

//     console.log("FormData before submission:");
//     for (let pair of formData.entries()) {
//         console.log(pair[0], pair[1]);
//     }

//     try {
//         debugger;
//         await axios.post("/api/save_page_section_master", formData, {
//             headers: { "Content-Type": "multipart/form-data" },
//         });
//         toastr.success("Page Section saved successfully");
//         $('#pagesectionModal').modal('hide');
//         console.log("Fetching updated data...");
//         await getPageSectionMaster(); // Ensure the function is awaited
//     } catch (error) {
//         if (error.response && error.response.status === 409) {
//             // Laravel response with message in case of conflict
//             toastr.error(error.response.data.message || "Error saving Page Section");
//         } else {
//             toastr.error("Error saving Page Section");
//         }
//     } finally {
//         actions.resetForm();
//     }
// };


// Handle Add/Edit Submit
const handlePageSectionSubmit = async (values, actions) => {
    let formData = new FormData();
    formData.append("pagesectionname", values.pagesectionname || "");

    if (pageformValues.file) {
        formData.append("logo", pageformValues.file);
    }

    try {
        if (editing.value) {
            formData.append("id", pageformValues.id);
            await axios.post(`/api/update_page_section_master/${pageformValues.id}`, formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });
            toastr.success("Page Section updated successfully");
        } else {
            await axios.post("/api/save_page_section_master", formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });
            toastr.success("Page Section saved successfully");
        }

        $('#pagesectionModal').modal('hide');
        await getPageSectionMaster();
    } catch (error) {
        toastr.error("Error saving Page Section");
    } finally {
        actions.resetForm();
        editing.value = false;
    }
};

const getPageSectionMaster = async () => {  // Add async here
    try {
        const response = await axios.get("/api/getPageSectionMaster");
        pagemasterdata.value = response.data;
        await nextTick(); // Ensure DOM updates before initializing DataTable
        initializeDataTable();
    } catch (error) {
        console.error("Error:", error);
    }
};


const initializeDataTable = () => {
    if ($.fn.DataTable.isDataTable(dataTable.value)) {
        $(dataTable.value).DataTable().destroy();
    }
    $(dataTable.value).DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search here...",
        },
        dom:
            "<'row'<'col-md-6'l><'col-md-6'f>>" + // Align search to right
            "<'row'<'col-md-12'tr>>" +
            "<'row'<'col-md-6'i><'col-md-6'p>>", // Align pagination to right
    });
};

const editComponent = (component) => {
    
    editing.value = true;

    // Fill form values with existing data
    pageformValues.id = component.id;
    pageformValues.pagesectionname = component.page_section_name;
    pageformValues.fileUrl = component.icon ? `/storage/${component.icon}` : null;

    $('#pagesectionModal').modal('show');
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        pageformValues.file = file;
        console.log("Selected File:", file);
    } else {
        pageformValues.file = null;
    }
};



const handleSubmit = (values, actions) => {

    axios.post('/api/update_component/' + 1, formValues.value, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then((response) => {

            console.log(response.data);
            getPageSectionMaster();
            $('#headerFormModal').modal('hide');
            toastr.success('Header saved successfully');
        }).catch((errors) => {
            console.log(errors);
        })
        .finally(() => {
            form.value.resetForm();
        });

};




const openFooterModal = () => {
    editingFooterItem.value = false;
    pageformValues.value = { id: '', type: '', content: '', link: '', logoUrl: null, order: '' };
    $('#pagesectionModal').modal('show');
};

const editSavedValue = (index) => {
    editingFooterItem.value = true;
    pageformValues.value = {
        id: footerValues.value[index].id,
        type: footerValues.value[index].type,
        content: footerValues.value[index].content,
        link: footerValues.value[index].link,
        order: footerValues.value[index].order,
        logoUrl: footerValues.value[index].link ? footerValues.value[index].link : null,
    };
    $('#pagesectionModal').modal('show');
};

const deleteSavedValue = (index) => {
    axios.post('/api/delete_component/', footerValues.value[index])
        .then((response) => {
            getPageSectionMaster();
            toastr.success('Footer deleted successfully');
        }).catch((errors) => {
            console.log(errors);
        })
        .finally(() => {
            form.value.resetForm();
        });
};

onMounted(() => {
    getPageSectionMaster();
});

</script>
<style scoped>
.dataTables_wrapper {
    display: flex;
    flex-direction: column;
}

.dataTables_filter {
    text-align: right !important;
    margin-bottom: 10px;
}

.dataTables_filter input {
    width: 250px;
    /* Adjust width if needed */
    margin-left: 5px;
}

.dataTables_paginate {
    text-align: right !important;
    margin-top: 10px;
}
</style>