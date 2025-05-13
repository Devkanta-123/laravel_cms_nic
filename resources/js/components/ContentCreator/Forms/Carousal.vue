<template>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/Kfw5nqKx1pG2eU6R7tZLz1kcfk5iZ3Vf0dUSbNjs2a2g/Og" crossorigin="anonymous">

    <br>

    <div style="display: flex;">
        <div class="col-xl-4 mb-30">
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title">Carousel</h5>
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <section class="body current" aria-hidden="false">
                                <label for="uName">Image</label>
                                <input type="file" name="file" ref="fileInput" multiple @change="onFileSelect"
                                    class="form-control" required>
                            </section>
                        </div>

                        <!-- Image Preview Section -->
                        <div v-if="images.length" class="mt-3 d-flex flex-wrap gap-2">
                            <div v-for="(image, index) in images" :key="index" class="position-relative me-2">
                                <button type="button" @click="removeImage(index)"
                                    class="btn-close btn-sm position-absolute top-0 end-0"
                                    style="z-index: 2; background-color: white; border-radius: 50%;">
                                </button>
                                <img :src="image.url" alt="Preview" width="100" height="100" class="img-thumbnail">
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="actions clearfix mt-3">
                            <ul role="menu" aria-label="Pagination">
                                <li>
                                    <button class="btn btn-success" role="menuitem" @click="uploadImages">Save</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xl-8 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">List </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0"  id="slidesTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Image</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(slide, index) in slides" :key="index">
                                    <td>
                                       <img class="img-fluid avatar-small" :src="`/storage/${slide.image}`"
                                        alt="Slide Image"  @click="openModal(`/storage/${slide.image}`)"
                                            style="cursor: pointer;">
                                    </td>
                                    <td>{{ formatDate(slide.created_at) }}</td>
                                    <td>
                                        <label :class="slide.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ slide.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>
                                        
                                        <i class="fas fa-trash-alt text-danger" @click="deleteSlide(slide.id)"></i>
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
import { ref, onMounted,nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const showModal = ref(false);
const modalImage = ref('');
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};
const images = ref([]);
const isDragging = ref(false);
// fileInput.value = null;
const slides = ref([]);
const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('en-IN', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  });
};
const onFileSelect = (event) => {
    const files = event.target.files;
    if (!files || files.length === 0) return;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Skip non-image files
        if (!file || file.type.split('/')[0] !== 'image') continue;

        // Avoid duplicates by file name
        if (!images.value.some((e) => e.name === file.name)) {
            const url = URL.createObjectURL(file);

            images.value.push({
                name: file.name,
                file: file,
                url: url, // This will be used to preview
            });
        }
    }
};

const removeImage = (index) => {
    images.value.splice(index, 1);
};

// console.log("RoleID" + role_id);
const uploadImages = () => {
    debugger;
    // Check if images array is empty or contains invalid entries
    if (!images.value || images.value.length === 0 || images.value.every(img => !img || !img.file)) {
        toastr.error('Please select at least one  image before uploading.');
        return;
    }
    const formData = new FormData();
    images.value.forEach((image) => {
        formData.append('images[]', image.file, image.name);
    });

    axios.post('/api/upload_carousel', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            console.log('Images uploaded successfully:', response.data);
            fetchSlides();
            toastr.success('Images uploaded successfully');
            images.value = [];
        })
        .catch(error => {
            console.error('Error uploading images:', error);
        });
};

const deleteDBImage = (slide, index) => {

    axios.post('/api/delete_slide', { id: slide.id })
        .then(response => {
            console.log('Slide deleted successfully:', response.data);
            toastr.success('Slide deleted successfully');
            slides.value.splice(index, 1);
        })
        .catch(error => {
            console.error('Error deleting slide:', error);
        });
};

const fetchSlides = async () => {
    try {
        const response = await axios.get('/get_carousel');
        console.log(response.data);
        slides.value = response.data;
        await nextTick() // wait until DOM is updated
        if ($.fn.dataTable.isDataTable('#slidesTable')) {
            $('#slidesTable').DataTable().destroy()
        }
        $('#slidesTable').DataTable()
    } catch (error) {
        console.error('Failed to fetch slides:', error);
    }
};


onMounted(() => {
    fetchSlides();
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
