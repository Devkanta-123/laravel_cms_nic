<template>
    <div class="container mt-3">
        <div class="tabs">
            <div class="tab-item" :class="{ active: selectedTab === 'add' }" @click="selectedTab = 'add'">
                Add Gallery
            </div>
            <div class="tab-item" :class="{ active: selectedTab === 'manage' }" @click="selectedTab = 'manage'">
                Manage Gallery
            </div>
        </div>
        <div class="tab-content">
            <div v-show="selectedTab === 'add'">
                <div class="modal-body custom-scroll-area"> <!-- Apply scroll class here -->
                    <div class="card">
                        <div class="form-group">
                            <label for="galleryName">Gallery Name</label>
                            <input type="text" id="galleryName" v-model="galleryName" placeholder="Enter gallery name"
                                class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="galleryDescription">Gallery Description</label>
                            <textarea id="galleryDescription" v-model="galleryDescription"
                                placeholder="Enter gallery description" class="form-control"></textarea>
                        </div>
                        <div class="drag-area" @dragover.prevent="onDragOver" @dragleave.prevent="onDragLeave"
                            @drop.prevent="onDrop">
                            <span v-if="!isDragging">
                                Drag Images here
                                <span class="select" role="button" @click="selectFile">Choose</span>
                            </span>
                            <div v-else class="select">Drop Images here</div>
                            <input type="file" name="file" class="file" ref="fileInput" multiple @change="onFileSelect"
                                accept="image/*,video/*">
                        </div>
                        <div class="container">
                            <div class="image" v-for="(image, index) in images" :key="index">
                                <span class="delete" @click="deleteImage(index)"> &times; </span>
                                <img :src="image.url" :alt="image.name" />
                            </div>
                        </div>
                        <div class="mt-4">
                            <div v-for="(item, index) in galleryItems" :key="index" class="row mb-3 align-items-end">
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
                            <div class="col-md-4 d-flex align-items-center gap-2">
                                <button class="btn btn-primary" @click="addGalleryItem"> Add Row</button>
                                <button class="btn btn-success" @click="uploadImages"> Submit</button>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <div v-show="selectedTab === 'manage'">
                <div class="card card-statistics h-100">
                    <div class="card-body">
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
                                            <label
                                                :class="gallery.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
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
                                            <span class="btn-success btn-sm" @click="closeModal">x</span>
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
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios';
import { getCurrentInstance } from 'vue';
import { useToastr } from '../../toaster.js';
const refs = getCurrentInstance().refs;
const galleryItems = ref([
    { name: '', file: null, url: '' }
]);
const toastr = useToastr();
const selectedTab = ref('add') // Default to 'add' tab
const showModal = ref(false);
const modalImage = ref('');
const images = ref([]);
const isDragging = ref(false);
const fileInput = ref(null);
const galleryName = ref('');
const galleryDescription = ref('');
const gallariesData = ref();
const addGalleryItem = () => {
    galleryItems.value.push({ name: '', file: null, url: '' });
};
const props = defineProps({
  menu: String,
  section: Number,
});
const removeGalleryItem = (index) => {
    if (galleryItems.value.length === 1) {
        toastr.warning('At least one gallery item is required.');
        return;
    }

    galleryItems.value.splice(index, 1);
};
const selectFile = () => {
    fileInput.value.click();
};
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
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

const deleteImage = (index) => {
    images.value.splice(index, 1);
};

const onDragOver = (e) => {
    isDragging.value = true;
    e.dataTransfer.dropEffect = 'copy';
};

const onDragLeave = () => {
    isDragging.value = false;
};

const onDrop = (e) => {
    isDragging.value = false;
    const files = e.dataTransfer.files;
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


const uploadImages = () => {
    if (!galleryName.value || !galleryDescription.value) {
        toastr.error('Please enter gallery name and description');
        return;
    }
    const formData = new FormData();
    formData.append('gallery_name', galleryName.value);
    formData.append('gallery_description', galleryDescription.value);
    formData.append("menu_id", props.menu);
    formData.append("page_section_master_id", props.section.page_section_id); 

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
.card {
    width: 100%;
    padding: 10px;
    box-shadow: 0 0 5px #ffdfdf;
    border-radius: 5px;
    overflow: hidden;
}

.card .top {
    text-align: center;
}

.card p {
    font-weight: bold;
    color: grey;
}

.card button {
    outline: 0;
    border: 0;
    color: white;
    border-radius: 4px;
    font-weight: 400;
    padding: 8px 13px;
    width: 100%;
    background: grey;
}

.card .drag-area {
    height: 150px;
    border-radius: 5px;
    border: 2px dashed grey;
    background: white;
    color: red;
    display: flex;
    justify-content: center;
    user-select: center;
    --webkit-user-select: none;
    margin-top: 10px;
}

.card .drag-area .visible {
    font-size: 18px;
}

.card .drag-area .select {
    color: blue;
    margin-left: 5px;
    cursor: pointer;
    transition: 0.4s;
}

.card .drag-area .select:hover {
    opacity: 0.6;
}

/* .card .container{
        width: 100%;
        height: auto;
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        max-height: 200px;
        position: relative;
        margin-bottom: 8px;
        padding-top: 15px;
    } */
.card .container {
    width: 100%;
    height: auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    align-items: flex-start;
    max-height: 200px;
    position: relative;
    margin-bottom: 8px;
    padding-top: 15px;
}

.card .container .image {
    position: relative;
    margin: 5px;
}

.card .container .image img {
    width: 100px;
    /* Set the width of the image */
    height: auto;
    /* Maintain the aspect ratio */
    border-radius: 5px;
}

.card .container .image .delete {
    position: absolute;
    top: -2px;
    right: 9px;
    font-size: 20px;
    cursor: pointer;
    color: red;
}

/* .card .container .image img{
        position: absolute;
        top: -2px;
        right: 9px;
        font-size: 20px;
        cursor: pointer;
    } */
</style>