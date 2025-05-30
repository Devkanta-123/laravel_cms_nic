<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Galleries</h5>
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
                                    <th>Action</th>
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
                                    <td>
                                        {{ gallery.addedby }}
                                    </td>
                                    <td>
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="gallery.flag === 'A'"
                                                    :disabled="gallery.flag === 'A'"
                                                    @change="approveGallery(gallery.id, index)">
                                                <span></span>
                                            </label>
                                        </div>
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
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const gallariesData = ref();
const showModal = ref(false);
const modalImage = ref('');
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
function isVideo(url) {
    if (!url) return false;
    const ext = url.split('.').pop().toLowerCase();
    return ['mp4', 'mov', 'avi', 'webm'].includes(ext);
}
const closeModal = () => {
    showModal.value = false;
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
const approveGallery = async (id, index) => {
    try {
        
        const response = await axios.post('/approved_gallery', { id });
        if (response) {
            gallariesData.value[index].flag = 'A'; // update UI immediately
            getGalleries();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};



onMounted(() => {
    getGalleries()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
