<template>
  <br>
  <br>
  <br>
 <div class="content ml-6 mr-6">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Pages / {{ route.params.menuName }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" @click="onBack()" class="btn btn-primary btn-sm pl-3 pr-3 pt-2 pb-2">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="col-xl-12 mb-30">
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title">Logo</h5>
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <div class="row">
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="hindiTitle">Image</label>
                                    <input type="file" name="file" class="form-control" @change="onFileSelect"
                                        ref="fileInput" multiple>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Publisher <span class="text-danger">*</span></label>
                                    <select class="form-control" v-model="selectedPublisher" required>
                                        <option value="" disabled>Select the Publisher</option>
                                        <option v-for="publisher in publisherData" :key="publisher.id"
                                            :value="publisher.id">
                                            {{ publisher.name }}
                                        </option>
                                    </select>
                                </div>
                                <div v-if="images.length" class="mt-3 d-flex flex-wrap gap-2">
                                    <div v-for="(image, index) in images" :key="index" class="position-relative me-2">
                                        <button type="button" @click="removeImage(index)"
                                            class="btn-close btn-sm position-absolute top-0 end-0"
                                            style="z-index: 2; background-color: white; border-radius: 50%;">
                                        </button>
                                        <img :src="image.url" alt="Preview" width="100" height="100"
                                            class="img-thumbnail">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="actions clearfix mt-3">
                            <ul role="menu" aria-label="Pagination">
                                <li>
                                    <button type="button" class="btn btn-success" role="menuitem" @click="uploadImages">
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
                    <h5 class="card-title pb-0 border-0">Logo</h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <div class="fc-toolbar fc-header-toolbar">
                            <div class="fc-right mb-3">
                                <div class="fc-button-group">
                                    <button type="button" :class="[
                                        'fc-month-button fc-button fc-state-default fc-corner-left',
                                        activeFlag === 'ALL' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('ALL')">All</button>

                                    <button type="button" :class="[
                                        'fc-month-button fc-button fc-state-default fc-corner-left',
                                        activeFlag === 'A' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('A')">Approved</button>

                                    <button type="button" :class="[
                                        'fc-agendaWeek-button fc-button fc-state-default',
                                        activeFlag === 'R' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('R')">Rejected</button>

                                    <button type="button" :class="[
                                        'fc-agendaDay-button fc-button fc-state-default fc-corner-right',
                                        activeFlag === 'PENDING' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('PENDING')">Pending</button>

                                </div>
                            </div>
                        </div>
                        <table class="table center-aligned-table mb-0" id="logoTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>Image</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Added By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(logo, index) in filteredLogoData" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        <img class="img-fluid avatar-small" :src="`/storage/${logo.image}`"
                                            @click="openModal(`/storage/${logo.image}`)" style="cursor: pointer;"
                                            alt="logo">
                                    </td>
                                    <td>{{ formatDate(logo.created_at) }}</td>
                                    <td>
                                        <label v-if="logo.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="logo.flag === 'U'" class="badge bg-warning">
                                            Pending
                                        </label>
                                        <div v-else-if="logo.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ logo.rejected_remarks }}
                                            </div>
                                        </div>
                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>
                                    <td>
                                        {{ logo.addedby }}
                                    </td>
                                    <td>
                                        <i class="fas fa-trash-alt text-danger"
                                            @click="deleteLogo(logo.id)"></i>&nbsp;&nbsp;
                                        <i class="fas fa-pencil-alt text-success" @click="editLogo(logo)"></i>
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
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ isEditMode ? 'Edit Logo' : 'Add Logo' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content clearfix">
                        <div class="row">
                            <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                <label class="form-label">Image</label>
                                <input type="file" name="file" class="form-control" @change="onFileSelect"
                                    ref="fileInput" multiple />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Publisher <span class="text-danger">*</span></label>
                                <select class="form-control" v-model="selectedPublisher" required>
                                    <option value="" disabled>Select the Publisher</option>
                                    <option v-for="publisher in publisherData" :key="publisher.id"
                                        :value="publisher.id">
                                        {{ publisher.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- <div v-if="images.length" class="mt-3 d-flex flex-wrap gap-2">
                            <div v-for="(image, index) in images" :key="index" class="position-relative me-2">
                                <button type="button" @click="removeImage(index)"
                                    class="btn-close btn-sm position-absolute top-0 end-0"
                                    style="z-index: 2; background-color: white; border-radius: 50%;"></button>
                                <img :src="`/storage/${image.url}`" alt="Preview" width="100" height="100" class="img-thumbnail" />
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        @click="closeModal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateLogo()"> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useRoute, useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const logoData = ref()
const filteredLogoData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const images = ref([]);
import Swal from 'sweetalert2';
const fileInput = ref(null);
const toastr = useToastr();
const props = defineProps({
    menuId: String,
    section: Number,
});
const selectedPublisher = ref("");
const publisherData = ref([]); // Store publisher data
const showModal = ref(false);
const modalImage = ref('');
const editingData = ref(null);
const isEditMode = ref(false);
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
    resetForm();
};

const onFileSelect = (event) => {
    const files = event.target.files;
    if (files.length === 0) return;
    for (let i = 0; i < files.length; i++) {
        if (files[i].type.split('/')[0] !== 'image') continue;
        if (!images.value.some((e) => e.name === files[i].name)) {
            images.value.push({
                name: files[i].name,
                file: files[i],
                url: URL.createObjectURL(files[i]),
            });
        }
    }
};
const removeImage = (index) => {
    images.value.splice(index, 1);
};

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const editLogo = async (logo) => {
    isEditMode.value = true;
    editingData.value = logo;
    selectedPublisher.value = logo.publisher_id;

    // Since logo.image is a single string, wrap it in an array
    images.value = [{
        file: null,
        url: logo.image
    }];

    await nextTick();
    $('#editModal').modal('show'); // or #editModal if that's your modal ID
};


const onBack = () => {
    router.push('/contentcreator/pages-form/1/Home/0')
}
// Filter handler
const filterByFlag = async (flag) => {
    activeFlag.value = flag; // Update active button state

    // Destroy existing DataTable
    if ($.fn.dataTable.isDataTable('#logoTable')) {
        $('#logoTable').DataTable().destroy();
    }
    // Filter logic
    if (flag === 'ALL') {
        filteredLogoData.value = [...logoData.value];
    } else if (flag === 'PENDING') {
        // Both 'U' (Unapproved) and 'N' (New) are considered pending
        filteredLogoData.value = logoData.value.filter(
            item => item.flag === 'U' || item.flag === 'N'
        );
    } else {
        filteredLogoData.value = logoData.value.filter(item => item.flag === flag);
    }

    await nextTick();
    initDataTable();

};


const initDataTable = () => {
    // Destroy if already exists
    if ($.fn.dataTable.isDataTable('#logoTable')) {
        $('#logoTable').DataTable().destroy();
    }

    nextTick(() => {
        $('#logoTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
}

// Reset form
const resetForm = () => {
    selectedPublisher.value = '';
    images.value = [];
    editingData.value = null;
    isEditMode.value = false;
    if (fileInput.value) fileInput.value.value = '';
};
// Update existing logo
const updateLogo = () => {
    if (!selectedPublisher.value) {
        toastr.error("Please select a publisher.");
        return;
    }

    const formData = new FormData();
    images.value.forEach((image) => {
        if (image.file) {
            formData.append('images[]', image.file, image.file.name);
        } else if (image.url) {
            formData.append('existing_images[]', image.url);
        }
    });
    formData.append("id", editingData.value.id);
    formData.append("menu_id", route.params.menuId);
    formData.append("page_section_master_id", route.params.page_section_id);
    formData.append("publisher_id", selectedPublisher.value);

    axios.post('/update_logo', formData)
        .then(response => {
            const res = response.data;
            if (res.message === "Logo updated successfully") {
                toastr.success(res.message);
                resetForm();
                $('#editModal').modal('hide');
                setTimeout(fetchLogo, 300);
            }
        })
        .catch(error => {
            console.error('Error updating logo:', error);
            toastr.error("Failed to update logo");
        });
};

const uploadImages = () => {
    if (!selectedPublisher.value) {
        toastr.error("Please select a publisher.");
        return false;
    }

    const formData = new FormData();
    images.value.forEach((image) => {
        formData.append('images[]', image.file, image.name);
    });
    formData.append("menu_id", route.params.menuId);
    formData.append("page_section_master_id", route.params.page_section_id);
    formData.append("publisher_id", selectedPublisher.value);

    axios.post('/api/upload_logo', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            const res = response.data;
            if (res.message === "Logos uploaded successfully") {
                toastr.success(res.message);
                images.value = [];
                // Slight delay ensures DB commit is complete before fetching
                setTimeout(() => {
                    fetchLogo();
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error uploading logo:', error);
            toastr.error("Failed to upload logo");
        });
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
    }
};



const deleteLogo = async (id) => {
    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this logo? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.post('/delete_logo', {
                id: id,
                menu_id: route.params.menuId,
                page_section_master_id: route.params.page_section_id
            });

            setTimeout(fetchLogo, 300); // refresh the list
            Swal.fire('Deleted!', response.data.message || 'Logo has been removed.', 'success');
        } catch (error) {
            console.error('Error deleting notice:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};
const fetchLogo = async () => {
    try {
        const response = await axios.get('/get_logo');
        logoData.value = response.data;
        filteredLogoData.value = response.data;
        await nextTick(); // Wait for DOM to update
        initDataTable();
    } catch (error) {
        console.error('Failed to fetch banner:', error);
    }
};

onMounted(() => {
    fetchLogo();
    getAllPublisher();
});

</script>

<style scoped>
@import '../assets/css/style.css';
</style>