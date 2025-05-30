<template>
    <div style="display: flex;">
        <div class="col-xl-6 mb-30">
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
        <div class="col-xl-6 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Logo</h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="logoTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Image</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Added By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(logo, index) in logoData" :key="index">
                                    <td>
                                        <img class="img-fluid avatar-small" :src="`/storage/${logo.image}`"
                                            @click="openModal(`/storage/${logo.image}`)" style="cursor: pointer;"
                                            alt="logo">
                                    </td>
                                    <td>{{ formatDate(logo.created_at) }}</td>
                                    <td>
                                        <label :class="logo.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ logo.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>
                                        {{ logo.addedby }}
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
const logoData = ref()
const images = ref([]);
const fileInput = ref(null);
const slides = ref([]);
const toastr = useToastr();
const props = defineProps({
    menuId: String,
    section: Number,
});
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

const uploadImages = () => {
    const formData = new FormData();
    images.value.forEach((image) => {
        formData.append('images[]', image.file, image.name);
    });
    formData.append("menu", props.menuId);
    axios.post('/api/upload_logo', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            fetchLogo();
            toastr.success('Logo uploaded successfully');
            images.value = [];
            fileInput = ref(null);
        })
        .catch(error => {
            console.error('Error uploading Banner:', error);
        });
};

const deleteDBImage = (slide, index) => {

    axios.post('/api/delete_banner', { id: slide.id })
        .then(response => {
            console.log('Banner deleted successfully:', response.data);
            toastr.success('Banner deleted successfully');
            slides.value.splice(index, 1);
        })
        .catch(error => {
            console.error('Error deleting Banner:', error);
        });
};

const fetchLogo = async () => {
    try {
        
        const response = await axios.get('/get_logo');
        logoData.value = response.data;
          await nextTick(); // Wait for DOM to update

        if ($.fn.dataTable.isDataTable('#logoTable')) {
            $('#logoTable').DataTable().destroy();
        }
        $('#logoTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    } catch (error) {
        console.error('Failed to fetch banner:', error);
    }
};

onMounted(() => {
    fetchLogo();
});

</script>

<style scoped>
@import '../assets/css/style.css';
</style>