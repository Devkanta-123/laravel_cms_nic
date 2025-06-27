<template>

    <br>

    <div>
        <div class="col-xl-12 mb-30">
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title">WhosWho</h5>
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <div class="row">
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="level">Level <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" v-model="selectedLevel">
                                        <option value="" disabled selected>Select the Level</option>
                                        <option v-for="level in masterleveldata" :key="level.id" :value="level.id">
                                            {{ level.level_name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4" v-if="dynamicFields.length > 0">
                                    <div v-for="field in dynamicFields" :key="field.key">
                                        <label class="form-label">{{ field.label }}</label>
                                        <input type="text" class="form-control" v-model="formData[field.key]"
                                            :placeholder="field.placeholder" />
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="formData.name"
                                        placeholder="Enter Name" />
                                </div>
                            </div>

                            <div class="row">
                                <!-- Checkbox Switch -->
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="formData.designation"
                                        placeholder="Enter Designation" />
                                </div>
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" v-model="formData.email"
                                        placeholder="Enter Email" />
                                </div>
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label">Contact</label>
                                    <input type="number" class="form-control" v-model="formData.contact"
                                        placeholder="Enter Contact" />
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Profile</label>
                                    <input type="file" class="form-control" @change="handleImageUpload"
                                        accept="image/*" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Publisher <span class="text-danger">*</span></label>
                                    <select class="form-control" v-model="selectedPublisher" required>
                                        <option value="" disabled>Select the Publisher</option>
                                        <option v-for="publisher in publisherData" :key="publisher.id"
                                            :value="publisher.id">
                                            {{ publisher.name }}
                                        </option>
                                    </select>
                                </div>
                                <!-- Image Preview -->
                                <div class="col-md-6" v-if="imagePreview">
                                    <img :src="imagePreview" class="img-thumbnail"
                                        style="max-width: 150px; height: auto;" />
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="actions clearfix mt-3">
                            <ul role="menu" aria-label="Pagination">
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
                        <table class="table center-aligned-table mb-0" id="whoswhoTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>District Name</th>
                                    <th>Block Name</th>
                                    <th>Designation</th>
                                    <th>Added By</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(whoswho, index) in WhosWhoData" :key="index">
                                    <td>
                                        <img class="direct-chat-img" :src="whoswho.profile_image
                                            ? `/storage/${whoswho.profile_image.replace('public/', '')}`
                                            : userlogo" alt="Profile Image"
                                            @click="whoswho.profile_image && openModal(`/storage/${whoswho.profile_image.replace('public/', '')}`)"
                                            :style="{ cursor: whoswho.profile_image ? 'pointer' : 'default' }" />
                                    </td>
                                    <td>{{ whoswho.name }}</td>
                                    <td>{{ whoswho.level_name ? whoswho.level_name + ' Level ' : 'N/A' }}</td>
                                    <td>{{ whoswho.district_name ? whoswho.district_name : 'N/A' }}</td>
                                    <td>{{ whoswho.block_name ? whoswho.block_name : 'N/A' }}</td>
                                    <td>{{ whoswho.designation }}</td>
                                    <td>{{ whoswho.addedby }}</td>
                                    <td>{{ formatDate(whoswho.created_at) }}</td>
                                    <td>
                                        <label v-if="whoswho.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="whoswho.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>
                                        <div v-else-if="whoswho.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ whoswho.rejected_remarks }}
                                            </div>
                                        </div>

                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>
                                    <td>
                                        <i class="fas fa-trash-alt text-danger"
                                            @click="deleteWhosWho(whoswho.id)"></i>&nbsp;
                                        <i class="fas fa-pencil-alt text-success" data-toggle="modal"
                                            data-target="#editModal" @click="editModal(whoswho)">
                                        </i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="modal fade" id="viewImage" tabindex="-1" role="dialog" aria-hidden="true"
                            :class="{ show: showModal }" :style="{ display: showModal ? 'block' : 'none' }">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header align-items-start">
                                        <div class="modal-title">
                                            <div class="mb-30">
                                                <div class="blog-box blog-2">
                                                    <img class="img-fluid w-100" :src="modalImage" alt="Modal Image" />
                                                    <div class="blog-info pt-10"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" @click="closeModal"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit WhosWho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <!-- ✅ Centered Profile Image with Camera Icon -->
                    <div class="d-flex justify-content-center mb-4 position-relative" v-if="editImagePreview">
                        <img :src="editImagePreview" class="profile-user-img img-fluid rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Image" />

                        <!-- 📷 Camera Icon -->
                        <span class="position-absolute"
                            style="bottom: 0; right: calc(50% - 15px); background-color: white; border-radius: 50%; padding: 6px; cursor: pointer;"
                            @click="triggerEditImageUpload">
                            <i class="fas fa-camera"></i>
                        </span>

                        <!-- Hidden File Input -->
                        <input type="file" ref="editFileInput" class="d-none" @change="handleEditImageUpload"
                            accept="image/*" />
                    </div>

                    <div class="row">
                        <!-- Level (Readonly) -->
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Level <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="selectedWhosWho.level_id" disabled>
                                <option value="" disabled>Select the Level</option>
                                <option v-for="level in masterleveldata" :key="level.id" :value="level.id">
                                    {{ level.level_name }}
                                </option>
                            </select>
                        </div>

                        <!-- Name -->
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="selectedWhosWho.name"
                                placeholder="Enter Name" />
                        </div>
                    </div>

                    <div class="row">
                        <!-- Designation -->
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="selectedWhosWho.designation"
                                placeholder="Enter Designation" />
                        </div>

                        <!-- Email -->
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" v-model="selectedWhosWho.email"
                                placeholder="Enter Email" />
                        </div>
                    </div>

                    <div class="row">
                        <!-- Contact -->
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Contact</label>
                            <input type="number" class="form-control" v-model="selectedWhosWho.contact"
                                placeholder="Enter Contact" />
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateWhosWho">Save Changes</button>
                </div>

            </div>
        </div>
    </div>



</template>

<script setup>
import { onMounted, reactive, ref, watch, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const showModal = ref(false);
const modalImage = ref('');
const selectedPublisher = ref("");
const publisherData = ref([]); // Store publisher 
const selectedWhosWho = ref({});
const editImagePreview = ref(null);
const selectedEditFile = ref(null);
const editFileInput = ref(null);
import userlogo from '@/assets/images/user.jpg'
import { useRoute } from 'vue-router';
const route = useRoute();
import Swal from 'sweetalert2';
// Initialize required references
const imagePreview = ref(null); // Stores preview URL
const masterleveldata = ref([]); // Store levels data
const dynamicFields = ref([]); // Stores dynamic fields based on level
const selectedLevel = ref(''); // The selected level ID
const WhosWhoData = ref();
const formData = ref({
    name: "",
    designation: "",
    email: "",
    contact: "",
    profile: null, // For storing profile image file
    level_id: null, // Store the selected level id
    dynamicFields: {}, // Store dynamic fields data
});
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
const triggerEditImageUpload = () => {
  editFileInput.value.click();
};

const handleEditImageUpload = (event) => {
    debugger;
  const file = event.target.files[0];
  if (file) {
    selectedEditFile.value = file;
    editImagePreview.value = URL.createObjectURL(file);
  }
};

const editModal = (whoswho) => {
    selectedWhosWho.value = { ...whoswho };
    editImagePreview.value = whoswho.profile_image ? `/storage/${whoswho.profile_image.replace('public/', '')}` : userlogo;
    selectedEditFile = null;
}

const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};
// Fetch all levels (Master data)
const getAllLevelMaster = async () => {
    try {
        const response = await axios.get('/api/getAllLevelMaster');
        masterleveldata.value = response.data; // Update masterleveldata with the response
    } catch (error) {
        console.error('Error fetching levels:', error.response || error);
        toastr.error("Failed to load levels.");
    }
};

const getWhosWho = async () => {
    try {
        const response = await axios.get('/get_whoswho');
        WhosWhoData.value = response.data;
        await nextTick(); // Wait for DOM to update
        if ($.fn.dataTable.isDataTable('#whoswhoTable')) {
            $('#whoswhoTable').DataTable().destroy();
        }
        $('#whoswhoTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
        console.log(response.data);

    }
    catch (error) {
        console.error('Error fetching whoswhodata', error.response);

    }
}

// Watch for changes in selectedLevel and update dynamic fields accordingly
watch(selectedLevel, (newVal) => {
    console.log("Selected level changed to:", newVal); // Check if the watch is triggered
    if (newVal === 2) {
        dynamicFields.value = [
            { key: "district_name", label: "District Name", placeholder: "Enter District Name" },
        ];
    } else if (newVal === 3) {
        dynamicFields.value = [
            { key: "block_name", label: "Block Name", placeholder: "Enter Block Name" },
        ];
    } else {
        console.log("Clearing dynamic fields"); // Debugging message
        dynamicFields.value = [];
    }
});

// Handle Image Upload and Preview
const handleImageUpload = (event) => {
    const file = event.target.files[0]; // Get selected file
    if (file) {
        formData.value.profile = file; // Store file in formData
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result; // Generate preview
        };
        reader.readAsDataURL(file);
    } else {
        formData.value.profile = null;
        imagePreview.value = null;
    }
};

// Toastr for notifications

// Fetch all level data when component is mounted
onMounted(() => {
    getAllLevelMaster();
    getWhosWho();
    getAllPublisher();
});

// Submit the form data to the backend
const submitData = async () => {
    if (!selectedLevel.value) {
        toastr.error('Level is required.');
        return;
    }
    if (!formData.value.name || formData.value.name.trim() === '') {
        toastr.error('Name is required.');
        return;
    }
    if (!formData.value.designation || formData.value.designation.trim() === '') {
        toastr.error('Designation is required.');
        return;
    }
    if (!selectedPublisher.value) {
        toastr.error("Please select a publisher.");
        return false;
    }

    // Basic validation
    const contact = formData.value.contact;
    const email = formData.value.email;
    // Validate contact: must be exactly 10 digits
    const contactRegex = /^\d{10}$/;
    if (!contactRegex.test(contact)) {
        toastr.error('Contact number must be exactly 10 digits.');
        return;
    }
    let transformedEmail = email.replace(/\./g, '[dot]').replace(/@/g, '[at]');
    const dataToSend = new FormData();
    // Append standard form data values
    dataToSend.append('name', formData.value.name);
    dataToSend.append('designation', formData.value.designation);
    dataToSend.append('email', transformedEmail); // Append transformed email
    dataToSend.append('contact', contact);
    dataToSend.append('level_id', selectedLevel.value);
    dataToSend.append('profile_image', formData.value.profile);
    dataToSend.append("menu_id", route.params.menuId);
    dataToSend.append("page_section_master_id", route.params.page_section_id);
    dataToSend.append("publisher_id", selectedPublisher.value);
    // Append dynamic fields based on selected level
    if (dynamicFields.value.length > 0) {
        dynamicFields.value.forEach(field => {
            dataToSend.append(field.key, formData.value[field.key] || ''); // Handle dynamic field values
        });
    }
    try {
        const response = await axios.post('/api/addWhosWho', dataToSend, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        // Handle the success response
        toastr.success(response.data.message);
    } catch (error) {
        // Handle error
        console.error('Error:', error.response.data);
        toastr.error(error.response.data.message || 'Something went wrong');
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
        // ✅ Auto-select based on previously fetched paragraph data
        if (WhosWhoData.value?.publisher_id) {
            selectedPublisher.value = WhosWhoData.value.publisher_id;
        }
    } catch (error) {
        console.error('Error fetching publishers:', error.response || error);
    }
};


const updateWhosWho = async () => {
    debugger;
    if (!selectedWhosWho.value.level_id || !selectedWhosWho.value.name || !selectedWhosWho.value.designation) {
        toastr.error("Level, Name, and Designation are required");
        return;
    }

    const contact = selectedWhosWho.value.contact;
    const contactRegex = /^\d{10}$/;
    if (contact && !contactRegex.test(contact)) {
        toastr.error("Contact must be exactly 10 digits");
        return;
    }

    const email = selectedWhosWho.value.email || '';
    const transformedEmail = email.replace(/\./g, '[dot]').replace(/@/g, '[at]');
    const formData = new FormData();
    formData.append("id", selectedWhosWho.value.id);
    formData.append("name", selectedWhosWho.value.name);
    formData.append("designation", selectedWhosWho.value.designation);
    formData.append("email", transformedEmail);
    formData.append("contact", selectedWhosWho.value.contact);
    formData.append("level_id", selectedWhosWho.value.level_id);
    formData.append("menu_id", route.params.menuId);
    formData.append("page_section_master_id", route.params.page_section_id);
   if (selectedEditFile.value) {
    formData.append("profile_image", selectedEditFile.value);
}
    try {
        const res = await axios.post('/api/updateWhosWho', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        await getWhosWho();
        toastr.success(res.data.message);
        $('#editModal').modal('hide');
    } catch (err) {
        toastr.error(err.response?.data?.message || "Something went wrong");
    }
};

const deleteWhosWho = async (id) => {
    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this data? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.post('/api/deleteWhosWho', {
                id: id,
                menu_id: route.params.menuId,
                page_section_master_id: route.params.page_section_id
            });
            await getWhosWho(); // refresh the list
            Swal.fire('Deleted!', response.data.message || 'WHosWhos has been deleted.', 'success');
        } catch (error) {
            console.error('Error deleting WHosWhos:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};


</script>
<style scoped>
@import '../assets/css/style.css';
</style>
