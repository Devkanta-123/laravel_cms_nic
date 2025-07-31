<template>
    <div class="container mt-3">
        <div class="tabs">
            <div class="tab-item" :class="{ active: selectedTab === 'add' }" @click="selectedTab = 'add'">
                Add Notice Board
            </div>
            <div class="tab-item" :class="{ active: selectedTab === 'manage' }" @click="selectedTab = 'manage'">
                Manage Notice Board
            </div>

        </div>

        <div class="tab-content">
            <div v-show="selectedTab === 'add'">
                <div class="container mt-4">
                    <!-- Category Dropdown -->
                    <div class="mb-3">
                        <label for="categorySelect" class="form-label">Category</label>
                        <select class="form-control" v-model="selectedCategory">
                            <option value="" disabled selected>Select the category</option>
                            <option v-for="category in categorydata" :key="category.id" :value="category.id">
                                {{ category.category_name }}
                            </option>
                        </select>
                    </div>

                    <div class="col-6">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="customSwitch3" v-model="isPinned">
                            <label class="custom-control-label" for="customSwitch3"> <span></span> <i
                                    class="fas fa-thumbtack text-danger"></i> Is high priority?</label>
                        </div>
                    </div>

                    <!-- Dynamic Rows -->
                    <div v-for="(row, index) in rows" :key="index" class="mb-3 row">
                        <div class="col-md-4">
                            <label v-if="index === 0" class="form-label">Title</label>
                            <input type="text" class="form-control" v-model="row.title" placeholder="Enter Title" />
                        </div>

                        <div class="col-md-3">
                            <label v-if="index === 0" class="form-label">Date</label>
                            <input type="date" class="form-control" v-model="row.date" />
                        </div>

                        <div class="col-md-3">
                            <label v-if="index === 0" class="form-label">Upload File</label>
                            <input type="file" multiple accept="application/pdf"
                                @change="handleFileUpload($event, index)" />
                        </div>

                        <div class="col-md-2 d-flex justify-content-end">
                            <button class="btn btn-sm py-0 px-1" @click="removeRow(index)">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center gap-2">
                        <!-- Add Row Button -->
                        <button class="btn btn-primary" @click="addRow"> Add Row</button>

                        <!-- Submit Button -->
                        <button class="btn btn-success" @click="submitData"> Submit</button>
                    </div>


                </div> <!-- add  form part -->
            </div>
            <div v-show="selectedTab === 'manage'">
                <div class="col-xl-12 mb-30">
                    <div class="card card-statistics h-100">
                        <div class="card-body">
                            <!-- action group -->
                            <div class="table-responsive">
                                <table class="table center-aligned-table mb-0" id="noticaboardTable">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>Title</th>
                                            <th>Added By</th>
                                            <th>Added On</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(notice, index) in noticeboarddata" :key="index">
                                            <td>
                                                <a :href="`/storage/${notice.file}`" target="_blank"
                                                    class="text-primary">
                                                    {{ notice.title }} <span v-if="notice.ispinned === 1"> <i
                                                            class="fas fa-thumbtack text-danger"></i></span>
                                                </a>
                                            </td>
                                            <td>{{ notice.addedby }}</td>
                                            <td>{{ formatDate(notice.created_at) }}</td>
                                            <td>{{ notice.category_name }}</td>
                                            <td>
                                                <label
                                                    :class="notice.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                                    {{ notice.flag === 'A' ? 'Approved' : 'Pending' }}
                                                </label>
                                            </td>
                                            <td>
                                                <i class="fas fa-trash-alt text-danger"
                                                    @click="deleteNotification(notice.id)"></i>&nbsp;
                                                <i class="fas fa-pencil-alt text-success" data-toggle="modal"
                                                    data-target="#editModal" @click="editModal(notice)">
                                                </i>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                                    aria-labelledby="editModalTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">
                                                    {{ selectedNotice.title }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <!-- Title and Date in a row -->
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label>Title</label>
                                                        <input type="text" class="form-control"
                                                            v-model="selectedNotice.title" />
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Date</label>
                                                        <input type="date" class="form-control"
                                                            v-model="selectedNotice.date" />
                                                    </div>
                                                </div>

                                                <!-- Category -->
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select class="form-control" v-model="selectedNotice.category_id">
                                                        <option value="" disabled>Select the category</option>
                                                        <option v-for="category in categorydata" :key="category.id"
                                                            :value="category.id">
                                                            {{ category.category_name }}
                                                        </option>
                                                    </select>
                                                </div>

                                                <!-- File Upload -->
                                                <div class="form-group">
                                                    <label>Upload File</label>
                                                    <input type="file" class="form-control"
                                                        @change="edithandleFileUpload" ref="fileInput" />
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" @click="updateNotice">Save
                                                    changes</button>
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


</template>

<script setup>
import { ref, onMounted, nextTick, defineProps } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
const selectedTab = ref('add') // Default to 'add' tab
const toastr = useToastr();
import Swal from 'sweetalert2';
const noticeboarddata = ref();
const selectedCategory = ref("");
const rows = ref([{ title: "", date: "", files: [] }]); // Initial row
const props = defineProps({
    section: Object,
    menu: Number,
    menuName: String,

})
const isPinned = ref(false);
const selectedNotice = ref({}) // To store the clicked notice
const editModal = (notice) => {
    selectedNotice.value = { ...notice }
}
const file = ref(null)
const categorydata = ref([]); // Store fetched categories
import { useRoute } from 'vue-router';
const route = useRoute();
// Fetch categories from API
const getAllCategoryMaster = async () => {
    try {
        const response = await axios.get('/api/getAllCategoryMaster');
        categorydata.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error.response || error);
        toastr.error("Failed to load categories.");
    }
};
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};



// Add a new row dynamically
const addRow = () => {
    rows.value.push({ title: "", date: "", files: [] });
};

// Remove a row
const removeRow = (index) => {
    if (rows.value.length > 1) {
        rows.value.splice(index, 1);
    } else {
        toastr.warning("At least one row data is required.");
    }
};

// Handle file uploads for a specific row
const handleFileUpload = (event, index) => {
    const files = Array.from(event.target.files);
    rows.value[index].files = files;
};


const edithandleFileUpload = (e) => {
    file.value = e.target.files[0]
}
// Validate input fields before submission
const validateFields = () => {
    if (!selectedCategory.value) {
        toastr.error("Please select a category.");
        return false;
    }

    for (let i = 0; i < rows.value.length; i++) {
        if (!rows.value[i].title.trim()) {
            toastr.error(`Title is required in row ${i + 1}.`);
            return false;
        }
        if (!rows.value[i].date) {
            toastr.error(`Date is required in row ${i + 1}.`);
            return false;
        }
        if (rows.value[i].files.length === 0) {
            toastr.error(`File upload is required in row ${i + 1}.`);
            return false;
        }
    }

    return true;
};

// Submit data
const submitData = async () => {
    if (!validateFields()) return;
    const formData = new FormData();
    formData.append("category_id", selectedCategory.value);
    formData.append("menu_id", route.params.menuId);
    formData.append("page_section_master_id", props.section.page_section_id);
    formData.append("ispinned", isPinned.value ? 1 : 0);
    rows.value.forEach((row, index) => {
        formData.append(`title[${index}]`, row.title);
        formData.append(`date[${index}]`, row.date);

        row.files.forEach((file, fileIndex) => {
            formData.append(`file[${index}][${fileIndex}]`, file);
        });
    });

    console.log("Prepared FormData:", Object.fromEntries(formData.entries()));

    try {
        const response = await axios.post("/api/submitNotificationsData", formData, {
            headers: { "Content-Type": "multipart/form-data" }
        });

        toastr.success(response.data.message);
        getAllNotifications();
        rows.value = [{ title: "", date: "", files: [] }]; // Reset form after success

    } catch (error) {
        console.error("Error submitting data:", error.response || error);
        toastr.error("An error occurred while submitting the data.");
    }
};

const clearFileInput = () => {
    file.value = null;
    if (fileInput.value) {
        fileInput.value.value = ''; // reset the actual input element
    }
}

const updateNotice = async () => {
    const formData = new FormData()
    formData.append('id', selectedNotice.value.id)
    formData.append('title', selectedNotice.value.title)
    formData.append('category_id', selectedNotice.value.category_id)
    formData.append('date', selectedNotice.value.date),
        formData.append("menu_id", route.params.menuId),
        formData.append("page_section_master_id", props.section.page_section_id)
    if (file.value) {
        formData.append('file', file.value)
    }
    console.log('FormData to send:');
    for (let pair of formData.entries()) {
        console.log(`${pair[0]}:`, pair[1]);
    }

    try {
        await axios.post('/api/updateNotice', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        getAllNotifications();
        toastr.success("Successfully Updated");
        $('#editModal').modal('hide')
    } catch (error) {
        console.error('Error updating notice:', error)
        toastr.error("Failed to update data");

    }
}

const deleteNotification = async (id) => {
    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this notice? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.post('/api/deleteNotification', {
                id: id,
                menu_id: route.params.menuId,
                page_section_master_id: props.section.page_section_id
            });

            await getAllNotifications(); // refresh the list
            Swal.fire('Deleted!', response.data.message || 'Notice has been deleted.', 'success');
        } catch (error) {
            console.error('Error deleting notice:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};

const getAllNotifications = async () => {
    try {
        console.log('Props:', props);
        const response = await axios.get('/get_notifications');

        let filteredData = response.data;

        // Apply filtering only if menu > 1
        if (props.menu > 1) {
            filteredData = filteredData.filter(
                item => item.category_name === props.menuName
            );
        }

        noticeboarddata.value = filteredData;

        await nextTick(); // Wait for DOM update

        // Reinitialize DataTable
        if ($.fn.dataTable.isDataTable('#noticaboardTable')) {
            $('#noticaboardTable').DataTable().destroy();
        }

        $('#noticaboardTable').DataTable({
            responsive: true,
            pageLength: 5,
            scrollY: '300px',
            scrollCollapse: true,
        });

    } catch (error) {
        console.error('Error fetching data:', error);
    }
};


onMounted(() => {
    getAllNotifications();
    getAllCategoryMaster();
});
</script>


<style scoped></style>