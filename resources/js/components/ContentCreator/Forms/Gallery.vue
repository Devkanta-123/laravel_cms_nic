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
                    <h5 class="card-title">Gallery</h5>
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <div class="row">
                                <!-- Gallery Name -->
                                <div class="col-sm-4 mb-4">
                                    <label class="form-label" for="galleryName">Gallery Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="galleryName" v-model="galleryName" placeholder="Gallery name"
                                        class="form-control" />
                                </div>

                                <!-- Gallery Description -->
                                <div class="col-sm-4 mb-4">
                                    <label for="galleryDescription">Gallery Description<span
                                            class="text-danger">*</span></label>
                                    <textarea id="galleryDescription" v-model="galleryDescription"
                                        placeholder="Enter gallery description" class="form-control"></textarea>
                                </div>

                                <!-- Cover Image Upload -->
                                <div class="col-sm-4 mb-4">
                                    <label class="form-label">Cover Image</label>
                                    <input type="file" class="form-control" ref="fileInput" multiple
                                        @change="onFileSelect" accept="image/*,video/*">
                                </div>
                            </div>



                            <!-- Cover Image Preview -->
                            <div v-if="images.length" class="mt-3 d-flex flex-wrap gap-2">
                                <div v-for="(image, index) in images" :key="index" class="position-relative me-2">
                                    <!-- Remove Button -->
                                    <button type="button" @click="removeImage(index)"
                                        class="btn-close btn-sm position-absolute top-0 end-0"
                                        style="z-index: 2; background-color: white; border-radius: 50%;"></button>

                                    <!-- Image Preview -->
                                    <img v-if="image.type === 'image'" :src="image.url" alt="Preview" width="100"
                                        height="100" class="img-thumbnail">

                                    <!-- Video Preview -->
                                    <video v-else-if="image.type === 'video'" :src="image.url" width="100" height="100"
                                        controls class="img-thumbnail">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </div>


                            <!-- Dynamic Gallery Items Section -->
                            <div class="mt-4">
                                <h5>Gallery Items</h5>
                                <div v-for="(item, index) in galleryItems" :key="index"
                                    class="row mb-3 align-items-end">
                                    <div class="col-sm-5">
                                        <label>Gallery Item Name</label>
                                        <input type="text" v-model="item.name" class="form-control"
                                            placeholder="Enter item name">
                                    </div>
                                    <div class="col-sm-5">
                                        <label>Gallery Item Image</label>
                                        <input type="file" @change="e => onGalleryItemImageSelect(e, index)"
                                            class="form-control" accept="image/*,video/*">
                                        <div v-if="item.url" class="mt-2">
                                            <img v-if="item.type === 'image'" :src="item.url" alt="Item" width="100"
                                                class="img-thumbnail">
                                            <video v-else-if="item.type === 'video'" width="100" controls>
                                                <source :src="item.url" type="video/mp4">
                                                Your browser does not support video.
                                            </video>
                                        </div>

                                    </div>
                                    <div class="col-sm-2">
                                        <i class="fas fa-trash-alt text-danger" @click="removeGalleryItem(index)"></i>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" @click="addGalleryItem">+ Add Row</button>
                            </div>
                            <div class="col-4">
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
                                    <button type="button" class="btn btn-success" @click="uploadImages">Save</button>
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
                    <h5 class="card-title pb-0 border-0">All Galleries</h5>
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
                        <table class="table center-aligned-table mb-0" id="galleriesTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>Image</th>
                                    <th>Image Title</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Added By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(gallery, index) in filteredGalleryData" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td @click="openModal(`/storage/${gallery.gallery_image}`)"
                                        style="cursor: pointer;">
                                        <template v-if="!isVideo(gallery.gallery_image)">
                                            <img class="img-fluid avatar-small"
                                                :src="`/storage/${gallery.gallery_image}`" alt="Gallery" />
                                        </template>
                                        <template v-else>
                                            <video class="img-fluid avatar-small"
                                                :src="`/storage/${gallery.gallery_image}`" muted playsinline
                                                preload="metadata"
                                                style="max-height: 50px; max-width: 80px; object-fit: cover;"></video>
                                        </template>
                                    </td>
                                    <td>{{ gallery.gallery_name }}</td>
                                    <td>{{ formatDate(gallery.created_at) }}</td>
                                    <td>
                                        <label v-if="gallery.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="gallery.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>
                                        <div v-else-if="gallery.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ gallery.rejected_remarks }}
                                            </div>
                                        </div>
                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>
                                    <td>{{ gallery.addedby }}</td>
                                    <td><i class="fas fa-trash-alt text-danger" v-if="gallery.flag !== 'A'"
                                            @click="deleteGallery(gallery.id)"></i></td>
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
                                                    <!-- Conditional rendering for video or image -->
                                                    <video v-if="isVideo(modalImage)" class="img-fluid w-100"
                                                        :src="modalImage" controls autoplay
                                                        style="max-height: 70vh; width: 100%;"></video>
                                                    <img v-else class="img-fluid w-100" :src="modalImage"
                                                        alt="Modal Image" />
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


</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import { getCurrentInstance } from 'vue';
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const refs = getCurrentInstance().refs;
const images = ref([]);
const fileInput = ref(null);
const galleryName = ref('');
const galleryItems = ref([
    { name: '', file: null, url: '' }
]);
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';
const route = useRoute();
const router = useRouter();
const selectedPublisher = ref("");
const publisherData = ref([]); // Store publisher categories
const galleryDescription = ref('');
const gallariesData = ref();
const showModal = ref(false);
const modalImage = ref('');
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};
const onBack = () => {
    router.push('/contentcreator/pages-form/1/Home/0')
}
const filteredGalleryData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const MAX_FILE_SIZE_MB = 20;
function isVideo(url) {
    if (!url) return false;
    const ext = url.split('.').pop().toLowerCase();
    return ['mp4', 'mov', 'avi', 'webm'].includes(ext);
}
const onFileSelect = (event) => {
    const file = event.target.files[0];  // ⬅ only take the first file
    if (!file) return;

    const fileType = file.type.split('/')[0]; // 'image' or 'video'

    if (!['image', 'video'].includes(fileType)) {
        toastr.error('Only image and video files are allowed.');
        return;
    }

    const maxSize = MAX_FILE_SIZE_MB * 1024 * 1024;
    if (file.size > maxSize) {
        toastr.error(`${file.name} exceeds ${MAX_FILE_SIZE_MB}MB limit.`);
        return;
    }

    // Replace existing image/video (no duplicates)
    images.value = [{
        name: file.name,
        file,
        url: URL.createObjectURL(file),
        type: fileType
    }];
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
const addGalleryItem = () => {
    galleryItems.value.push({ name: '', file: null, url: '' });
};

const removeGalleryItem = (index) => {
    if (galleryItems.value.length === 1) {
        toastr.warning('At least one gallery item is required.');
        return;
    }

    galleryItems.value.splice(index, 1);
};

const onGalleryItemImageSelect = (event, index) => {
    const file = event.target.files[0];
    if (!file) return;

    const fileType = file.type.split('/')[0];
    if (!['image', 'video'].includes(fileType)) return;

    if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
        toastr.error(`${file.name} exceeds 20MB limit.`);
        return;
    }

    galleryItems.value[index].file = file;
    galleryItems.value[index].url = URL.createObjectURL(file);
    galleryItems.value[index].type = fileType;
};


const uploadImages = () => {
    if (!galleryName.value || !galleryDescription.value) {
        toastr.error('Please enter gallery name and description');
        return;
    }
    if (!selectedPublisher.value) {
        toastr.error("Please select a publisher.");
        return false;
    }

    const formData = new FormData();
    formData.append('gallery_name', galleryName.value);
    formData.append('gallery_description', galleryDescription.value);
    formData.append("menu_id", route.params.menuId);
    formData.append("page_section_master_id", route.params.page_section_id);
    formData.append("publisher_id", selectedPublisher.value)

    // Append main gallery images
    images.value.forEach((image) => {
        formData.append('images[]', image.file, image.name);
    });

    // Append item images
    galleryItems.value.forEach((item, index) => {
        if (item.name && item.file) {
            formData.append(`items[${index}][name]`, item.name);
            formData.append(`items[${index}][file]`, item.file);
        }
    });

    // Log for verification
    for (let pair of formData.entries()) {
        if (pair[1] instanceof File) {
            console.log(pair[0], pair[1].name);
        } else {
            console.log(pair[0], pair[1]);
        }
    }

    // Submit to API
    axios.post('/api/upload_gallery', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then(async () => {
            await getGalleries(); // Refresh table

            // ✅ Reset all values
            galleryName.value = '';
            galleryDescription.value = '';
            images.value = [];

            // ✅ Reset main file input using ref
            if (refs.fileInput) {
                refs.fileInput.value = '';
            }

            // ✅ Reset item images and file inputs
            galleryItems.value = [{
                name: '',
                file: null,
                url: ''
            }];

            // Clear file inputs visually (for sub-items)
            document.querySelectorAll('input[type="file"].form-control').forEach(input => {
                input.value = '';
            });

            toastr.success('Gallery uploaded successfully');
        })
        .catch((error) => {
            console.error('Upload failed', error);
            toastr.error('Upload failed');
        });
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


const getGalleries = async () => {
    try {
        const response = await axios.get('/get_galleries');

        // Step 1: Destroy the current DataTable if exists BEFORE updating data
        if ($.fn.dataTable.isDataTable('#galleriesTable')) {
            $('#galleriesTable').DataTable().destroy();
        }

        // Step 2: Update reactive data (this triggers Vue DOM update)
        gallariesData.value = response.data;
        filteredGalleryData.value = response.data;

        // Step 3: Wait for DOM update (Vue rendering)
        await nextTick();

        // Step 4: Initialize DataTable after DOM is updated with new rows
        $('#galleriesTable').DataTable({
            responsive: true,
            pageLength: 10,
        });

        console.log('gallariesData data', response.data);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

// Filter handler
const filterByFlag = async (flag) => {
    activeFlag.value = flag; // Update active button state
    // Destroy existing DataTable
    if ($.fn.dataTable.isDataTable('#galleriesTable')) {
        $('#galleriesTable').DataTable().destroy();
    }

    // Filter logic
    if (flag === 'ALL') {
        filteredGalleryData.value = [...gallariesData.value];
    } else if (flag === 'PENDING') {
        // Both 'U' (Unapproved) and 'N' (New) are considered pending
        filteredGalleryData.value = gallariesData.value.filter(
            item => item.flag === 'U' || item.flag === 'N'
        );
    } else {
        filteredGalleryData.value = gallariesData.value.filter(item => item.flag === flag);
    }
    await nextTick();
    initDataTable();

};


const initDataTable = () => {
    // Destroy if already exists
    if ($.fn.dataTable.isDataTable('#galleriesTable')) {
        $('#galleriesTable').DataTable().destroy();
    }

    nextTick(() => {
        $('#galleriesTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
};


const deleteGallery = async (id) => {
    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this gallery? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.post('/api/delete_gallery', {
                id: id,
                menu_id: route.params.menuId,
                page_section_master_id: route.params.page_section_id
            });
            await nextTick();
            await getGalleries(); // Refresh table
            Swal.fire('Deleted!', response.data.message || 'Gallery and it items  has been removed.', 'success');
        } catch (error) {
            console.error('Error deleting card:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};


onMounted(() => {
    getGalleries();
    getAllPublisher()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
