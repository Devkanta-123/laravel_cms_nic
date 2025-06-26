<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title">Notice Board</h5>
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label my-1 me-2" for="inlineFormSelectPref">Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select my-1 me-sm-2" v-model="selectedCategory">
                                        <option value="" disabled selected>Select the category</option>
                                        <option v-for="category in categorydata" :key="category.id"
                                            :value="category.id">
                                            {{ category.category_name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div v-for="(row, index) in rows" :key="index" class="mb-3 row">
                                    <div class="col-md-4">
                                        <label v-if="index === 0" class="form-label">Title <span
                                            class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="row.title"
                                            placeholder="Enter Title" />
                                    </div>

                                    <div class="col-md-3">
                                        <label v-if="index === 0" class="form-label">Date <span
                                            class="text-danger">*</span></label>
                                        <input type="date" class="form-control" v-model="row.date" />
                                    </div>

                                    <div class="col-md-3">
                                        <label v-if="index === 0" class="form-label">Upload File <span
                                            class="text-danger">*</span></label>
                                        <input type="file" multiple accept="application/pdf" class="form-control"
                                            @change="handleFileUpload($event, index)" />
                                    </div>

                                    <div class="col-md-2 d-flex justify-content-end align-items-center gap-1">
                                        <button class="btn btn-sm py-0 px-1" @click="removeRow(index)">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                             <div class="col-6">
                                    <label class="form-label my-1 me-2" for="inlineFormSelectPref">Publisher <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select my-1 me-sm-2" v-model="selectedPublisher">
                                        <option value="" disabled>Select the Publisher</option>
                                        <option v-for="publisher in publisherData" :key="publisher.id"
                                            :value="publisher.id">
                                            {{ publisher.name }}
                                        </option>
                                    </select>
                                </div>
                        </div>

                        <!-- Save Button -->
                        <div class="actions clearfix mt-3">
                            <ul role="menu" aria-label="Pagination">
                                <li>
                                    <button class="btn btn-primary" @click="addRow">Add
                                        Row</button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-success" role="menuitem" @click="submitData">
                                        Save
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">List </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="noticaboardTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Title</th>
                                    <th>Added By</th>
                                    <th>Approver</th>
                                    <th>Added On</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(notice, index) in noticeboarddata" :key="index">
                                    <td>
                                        <a :href="`/storage/${notice.file}`" target="_blank" class="text-primary">
                                            {{ notice.title }}
                                        </a>
                                    </td>
                                    <td>{{ notice.addedby }}</td>
                                    <td>{{ notice.approver || 'N/A'}}</td>
                                    <td>{{ notice.date}}</td>
                                    <td>{{ notice.category_name }}</td>
                                    <td>
                                        <label v-if="notice.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="notice.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>

                                        <div v-else-if="notice.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ notice.rejected_remarks }}
                                            </div>
                                        </div>

                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>
                                    <td>
                                        <i class="fas fa-trash-alt text-danger"
                                            @click="deleteNotification(notice.id)"></i>&nbsp;&nbsp;
                                        <i class="fas fa-pencil-alt text-success" data-toggle="modal"
                                            data-target="#editModal" @click="editModal(notice)">
                                        </i>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                        {{ selectedNotice.title }}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- Title and Date in a row -->
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Title</label>
                                            <input type="text" class="form-control" v-model="selectedNotice.title" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Date</label>
                                            <input type="date" class="form-control" v-model="selectedNotice.date" />
                                        </div>
                                    </div>

                                    <!-- Category -->
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="form-select my-1 me-sm-2" v-model="selectedNotice.category_id">
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
                                        <input type="file" class="form-control" @change="edithandleFileUpload"
                                            ref="fileInput" />
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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


</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2';
import { useRoute } from 'vue-router';
const route = useRoute();
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const selectedCategory = ref("");
const selectedPublisher = ref("");
const rows = ref([{ title: "", date: "", files: [] }]); // Initial row
const categorydata = ref([]); // Store fetched categories
const publisherData = ref([]); // Store publisher categories
const showLinkInput = ref(false)
const file = ref(null)
const link = ref('')
const title = ref('')
const fileInput = ref(null) // this will reference the file input
const noticeboarddata = ref([]);
const addRow = () => {
    rows.value.push({ title: "", date: "", files: [] });
};
const selectedNotice = ref({}) // To store the clicked notice

const editModal = (notice) => {
    selectedNotice.value = { ...notice }
}
const clearFileInput = () => {
    file.value = null;
    if (fileInput.value) {
        fileInput.value.value = ''; // reset the actual input element
    }
}

// Remove a row
const removeRow = (index) => {
    if (rows.value.length > 1) {
        rows.value.splice(index, 1)
    } else {
        toastr.warning('At least one row data is required.')
    }
}
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
    if (!selectedPublisher.value) {
        toastr.error("Please select a publisher.");
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
    formData.append("publisher_id", selectedPublisher.value);
    formData.append("menu_id", route.params.menuId);
    formData.append("page_section_master_id", route.params.page_section_id);
    rows.value.forEach((row, index) => {
        formData.append(`title[${index}]`, row.title);
        formData.append(`date[${index}]`, row.date);

        row.files.forEach((file, fileIndex) => {
            formData.append(`file[${index}][${fileIndex}]`, file);
        });
    });

    try {
        const response = await axios.post("/api/submitNotificationsData", formData, {
            headers: { "Content-Type": "multipart/form-data" }
        });
        getAllNotifications();
        toastr.success(response.data.message);
        rows.value = [{ title: "", date: "", files: [] }]; // Reset form after success

    } catch (error) {
        console.error("Error submitting data:", error.response || error);
        toastr.error("An error occurred while submitting the data.");
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

const getAllCategoryMaster = async () => {
    try {
        const response = await axios.get('/api/getAllCategoryMaster');
        categorydata.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error.response || error);
        toastr.error("Failed to load categories.");
    }
};
const getAllPublisher = async () => {
    try {
        const response = await axios.get('/api/get_allpublisher');
        publisherData.value = response.data.data;
        // Auto-select if only one publisher exists
        if (publisherData.value.length === 1) {
            selectedPublisher.value = publisherData.value[0].id;
        }
    } catch (error) {
        console.error('Error fetching publishers:', error.response || error);
        toastr.error("Failed to load publishers.");
    }
};





const getAllNotifications = async () => {
    try {
        const response = await axios.get('/get_notifications');
        noticeboarddata.value = response.data;
        await nextTick(); // Wait for DOM to update
        // Destroy and reinitialize DataTable
        if ($.fn.dataTable.isDataTable('#noticaboardTable')) {
            $('#noticaboardTable').DataTable().destroy();
        }
        $('#noticaboardTable').DataTable({
            responsive: true,
            pageLength: 10,
        });

        console.log('Latest News data', response.data);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
const updateNotice = async () => {
    const formData = new FormData()
    formData.append('id', selectedNotice.value.id)
    formData.append('title', selectedNotice.value.title)
    formData.append('category_id', selectedNotice.value.category_id)
    formData.append('date', selectedNotice.value.date),
        formData.append("menu_id", route.params.menuId),
        formData.append("page_section_master_id", route.params.page_section_id)
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
        clearFileInput(); // ✅ clear file input
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
                page_section_master_id: route.params.page_section_id
            });

            await getAllNotifications(); // refresh the list
            Swal.fire('Deleted!', response.data.message || 'Notice has been deleted.', 'success');
        } catch (error) {
            console.error('Error deleting notice:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};


onMounted(() => {
    getAllNotifications()
    getAllCategoryMaster()
    getAllPublisher()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
