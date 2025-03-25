<template>
    <br>
    <div class="content ml-4 mr-4">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Level Master</h4>
                        <span>All Level Master</span>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="">
                        <li class="breadcrumb-item"><a href="">Level Master</a></li>
                        <li class="breadcrumb-item active "><a class="text-primary">All Level Master</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content ml-4 mr-4">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h4>Level Master</h4>
                    <div align="right">

                        <button type="button" class="btn btn-primary mb-2 p-2" @click="openLevelMasterModal">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Add Level Master
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table ref="dataTable" class="table table-responsive-lg mb-0">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Page Section Name <i class="fa fa-sort asc"></i></th>
                                <th>Action <i class="fa fa-sort asc"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in categorymasterdatas" :key="item.id">
                                <td width="10%">{{ index + 1 }}</td>
                                <td width="20%">{{ item.level_name }}</td>
                                <td width="20%">
                                    <a href="#" @click.prevent="editCategory(item)"
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
    <div class="modal fade" id="LevelModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="LevelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="LevelModalLabel">Add Level Master</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleLevelDataSubmit" v-slot="{ errors }" :initial-values="pageformValues">
                    <div class="modal-body">
                        <!-- Page Section Name -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="level_name">Level Master Name</label>
                                    <Field type="text" class="form-control" v-model="pageformValues.level_name"
                                        name="level_name" id="level_name" placeholder="Level Name" autocomplete="off"
                                        :class="{ 'is-invalid': errors.level_name }" />
                                    <span class="invalid-feedback">{{ errors.level_name }}</span>
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
    <!-- Edit category Modal -->
    <div class="modal fade" id="editLevelModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="editLevelModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLevelModalLabel">Edit Category Master</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="edithandleLevelDataSubmit" v-slot="{ errors }"
                    :initial-values="editpageformValues">
                    <div class="modal-body">
                        <!-- Page Section Name -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="level_name">Category Master Name</label>
                                    <Field type="text" class="form-control" v-model="editpageformValues.editlevel_name"
                                        name="editlevel_name" id="editlevel_name" autocomplete="off"
                                        :class="{ 'is-invalid': errors.editlevel_name }" />
                                    <span class="invalid-feedback">{{ errors.editlevel_name }}</span>
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
    <!-- Edit category Modal End -->


</template>
<script setup>
import $ from "jquery";
import "datatables.net-bs4"; // Ensure you have this installed
import { ref, onMounted, computed, reactive, nextTick } from "vue";
import axios from 'axios';
import { Form, Field } from 'vee-validate';
import { useToastr } from '../../toaster.js';
import Swal from 'sweetalert2';
const categorymasterdatas = ref([]);
const toastr = useToastr();
const form = ref(null);
const footerValues = ref([]);
const editingFooterItem = ref(false);
const dataTable = ref(null);




const pageformValues = reactive({
    level_name: "",
});
const editpageformValues = reactive({
    id: "",
    editlevel_name: "",
});

const handleLevelDataSubmit = async (values, actions) => {
    debugger;

    let formData = new FormData();
    formData.append("level_name", values.level_name || "");

    console.log("FormData before submission:" + formData);
    for (let pair of formData.entries()) {
        console.log(pair[0], pair[1]);
    }

    try {
        await axios.post("/api/addLevelMaster", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        toastr.success("Data saved successfully");
        $('#LevelModal').modal('hide');
        console.log("Fetching updated data...");
        await getAllCategoryMaster(); // Ensure the function is awaited
    } catch (error) {
        if (error.response && error.response.status === 409) {
            // Laravel response with message in case of conflict
            toastr.error(error.response.data.message || "Error saving Page Section");
        } else {
            toastr.error("Error saving Page Section");
        }
    } finally {
        actions.resetForm();
    }
};

const getAllCategoryMaster = async () => {  // Add async here
    try {

        const response = await axios.get("/api/getAllCategoryMaster");
        categorymasterdatas.value = response.data;
        await nextTick(); // Ensure DOM updates before initializing DataTable
        initializeDataTable();
    } catch (error) {
        console.error("Error:", error);
    }
};

const editCategory = (category) => {
    editpageformValues.id = category.id; // Populate the form id
    editpageformValues.editlevel_name = category.level_name; // Populate the form value
    $('#editLevelModal').modal('show'); // Show modal
};

const edithandleLevelDataSubmit = () => {
    console.log("Form submitted:", editpageformValues);
    try {
        axios.post("/api/editCategoryMaster", editpageformValues, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        toastr.success("Category name updated successfully");
        $('#LevelModal').modal('hide');
        console.log("Fetching updated data...");
    } catch (error) {
        if (error.response && error.response.status === 409) {
            // Laravel response with message in case of conflict
            toastr.error(error.response.data.message || "Error saving Page Section");
        } else {
            toastr.error("Error saving Page Section");
        }
    } finally {
        $('#editLevelModal').modal('hide'); // Show modal
    }
    // Add API call or form submission logic here
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



const openLevelMasterModal = () => {
    editingFooterItem.value = false;
    $('#LevelModal').modal('show');
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
    $('#LevelModal').modal('show');
};

const deleteSavedValue = (index) => {
    axios.post('/api/delete_component/', footerValues.value[index])
        .then((response) => {
            getAllCategoryMaster();
            toastr.success('Footer deleted successfully');
        }).catch((errors) => {
            console.log(errors);
        })
        .finally(() => {
            form.value.resetForm();
        });
};

onMounted(() => {
    getAllCategoryMaster();
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