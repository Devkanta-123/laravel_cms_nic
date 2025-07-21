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
                    <table  class="table table-responsive-lg mb-0" id="levelMasterTable">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Page Section Name <i class="fa fa-sort asc"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in levelmasterdatas" :key="item.id">
                                <td width="10%">{{ index + 1 }}</td>
                                <td width="20%">{{ item.level_name }}</td>
                                <td width="20%">
                                    <a href="#" @click.prevent="editLevel(item)"
                                        class="btn btn-primary shadow btn-xs  me-1"><i
                                            class="fas fa-pencil-alt"></i></a>
                                    <a href="#" @click.prevent="confirmLevelMasterDeletion(item)"
                                        class="btn btn-danger shadow btn-xs sharp">
                                        <i class="fa fa-trash"></i>
                                    </a>
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
                    <h5 class="modal-title" id="editLevelModalLabel">Edit Level Master</h5>
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
const levelmasterdatas = ref([]);
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
        await gellLevelMaster(); // Ensure the function is awaited
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

const gellLevelMaster = async () => {  // Add async here
    try {
        const response = await axios.get("/api/getAllLevelMaster");
        levelmasterdatas.value = response.data;
        await nextTick(); // Ensure DOM updates before initializing DataTable
    } catch (error) {
        console.error("Error:", error);
    }
};

const editLevel = (level) => {
    editpageformValues.id = level.id; // Populate the form id
    editpageformValues.editlevel_name = level.level_name; // Populate the form value
    $('#editLevelModal').modal('show'); // Show modal
};

const edithandleLevelDataSubmit = async () => {
  console.log("Form submitted:", editpageformValues);

  try {
    await axios.post("/api/editLevelMaster", editpageformValues, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    toastr.success("Level name updated successfully");
    $('#editLevelModal').modal('hide');

    console.log("Fetching updated data...");
    await gellLevelMaster(); // This requires the function to be async
  } catch (error) {
    if (error.response && error.response.status === 409) {
      toastr.error(error.response.data.message || "Error updating Level name");
    } else {
      toastr.error("Error updating Level name");
    }
  } finally {
    $('#editLevelModal').modal('hide');
  }
};


const confirmLevelMasterDeletion = async (level) => {
    debugger;
    const result = await Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    });

    if (result.isConfirmed) {
        try {
            await axios.post('/api/delete_levelmaster', {
                id: level.id,
            });

            await gellLevelMaster(); // Refresh table with latest data
            Swal.fire({
                title: "Deleted!",
                text: "Level Master has been deleted.",
                icon: "success"
            });
        } catch (error) {
            console.error("Deletion error:", error);
            Swal.fire({
                title: "Error!",
                text: "Something went wrong while deleting.",
                icon: "error"
            });
        }
    }
};





const openLevelMasterModal = () => {
    editingFooterItem.value = false;
    $('#LevelModal').modal('show');
};




onMounted(() => {
    gellLevelMaster();
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