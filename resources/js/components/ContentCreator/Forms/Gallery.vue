<template>

    <br>

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
                        <table class="table center-aligned-table mb-0" id="galleriesTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Image</th>
                                    <th>Image Title</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Added By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(gallery, index) in gallariesData" :key="index">
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
                                        <label :class="gallery.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ gallery.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>{{ gallery.addedby }}</td>
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
const MAX_FILE_SIZE_MB = 20;
function isVideo(url) {
    if (!url) return false;
    const ext = url.split('.').pop().toLowerCase();
    return ['mp4', 'mov', 'avi', 'webm'].includes(ext);
}
const onFileSelect = (event) => {
    const files = event.target.files;
    if (files.length === 0) return;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileType = file.type.split('/')[0]; // image or video

        if (!['image', 'video'].includes(fileType)) continue;

        if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
            toastr.error(`${file.name} exceeds 20MB limit.`);
            continue;
        }

        if (!images.value.some((e) => e.name === file.name)) {
            images.value.push({
                name: file.name,
                file,
                url: URL.createObjectURL(file),
                type: fileType
            });
        }
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

    const formData = new FormData();
    formData.append('gallery_name', galleryName.value);
    formData.append('gallery_description', galleryDescription.value);

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



onMounted(() => {
    getGalleries()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
