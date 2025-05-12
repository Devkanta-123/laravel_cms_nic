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
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="englishTitle">Gallery Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="galleryName" v-model="galleryName" placeholder="Gallery name"
                                        class="form-control" />
                                </div>
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label for="galleryDescription">Gallery Description<span
                                            class="text-danger">*</span></label>
                                    <textarea id="galleryDescription" v-model="galleryDescription"
                                        placeholder="Enter gallery description" class="form-control"></textarea>
                                </div>
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="hindiTitle">Image</label>
                                    <input type="file" name="file" class="form-control" ref="fileInput" multiple
                                        @change="onFileSelect">
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
                                    <td>
                                        <img class="img-fluid avatar-small" :src="`/storage/${gallery.gallery_image}`"
                                            @click="openModal(`/storage/${gallery.gallery_image}`)"
                                            style="cursor: pointer;" alt="Gallery">
                                    </td>
                                    <td>{{ gallery.gallery_name }}</td>
                                    <td>{{ formatDate(gallery.created_at) }}</td>
                                    <td>
                                        <label :class="gallery.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ gallery.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>
                                        {{ gallery.addedby }}
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


</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const images = ref([]);
const fileInput = ref(null);
const galleryName = ref('');
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

const uploadImages = () => {
    if (!galleryName.value || !galleryDescription.value) {
        toastr.error('Please enter gallery name and description');
        return;
    }

    const formData = new FormData();
    formData.append('gallery_name', galleryName.value);
    formData.append('gallery_description', galleryDescription.value);
    images.value.forEach((image) => {
        formData.append('images[]', image.file, image.name);
    });

    axios.post('/api/upload_gallery', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then((response) => {
            // console.log('Images uploaded successfully:', response.data);
            toastr.success('Gallery Image Upload Success');
            images.value = [];
            galleryName.value = '';
            galleryDescription.value = '';
        })
        .catch((error) => {
            console.error('Error uploading images:', error);
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
        debugger;
        const response = await axios.get('/get_galleries');
        gallariesData.value = response.data;

        await nextTick(); // Wait for DOM to update

        // Destroy and reinitialize DataTable
        if ($.fn.dataTable.isDataTable('#galleriesTable')) {
            $('#galleriesTable').DataTable().destroy();
        }
        $('#galleriesTable').DataTable({
            responsive: true,
            pageLength: 10,
        });

        console.log('gallariesData  data', response.data);
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
