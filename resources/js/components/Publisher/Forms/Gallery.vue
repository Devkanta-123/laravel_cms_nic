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
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Galleries</h5>
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
                                        <label v-else-if="gallery.flag === 'U'" class="badge bg-warning">
                                            Pending
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
                                                &nbsp;&nbsp;
                                            </label>
                                            <i class="fas fa-times text-danger"
                                                v-if="gallery.flag !== 'A' && gallery.flag !== 'R'" data-toggle="modal"
                                                data-target="#rejectedModal" @click="rejectedModal(gallery)"></i>
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

                        <div class="modal fade" id="rejectedModal" tabindex="-1" role="dialog"
                            aria-labelledby="rejectedModalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Rejected Remarks <span class="text-danger">*</span></label>
                                            <textarea v-model="rejectedRemarks" class="form-control"
                                                placeholder="Remarks...."></textarea>
                                            <span v-if="rejectedRemarksError" class="text-danger">Remarks are
                                                required</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-danger" @click="rejected">Rejected
                                        </button>
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
import { useRoute,useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedGallery = ref({});
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
function isVideo(url) {
    if (!url) return false;
    const ext = url.split('.').pop().toLowerCase();
    return ['mp4', 'mov', 'avi', 'webm'].includes(ext);
}
	const onBack = () => {
    if (window.history.length > 1) {
        router.back();
    } else {
        router.push('/publisher/pages-form/1/Home/0')
    }
}
	
const closeModal = () => {
    showModal.value = false;
};
const rejectedModal = (gallery) => {
    selectedGallery.value = gallery;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};
const filteredGalleryData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const rejected = async () => {
    if (!rejectedRemarks.value.trim()) {
        rejectedRemarksError.value = true;
        return;
    }
    rejectedRemarksError.value = false;
    try {
        const response = await axios.post('/rejected_gallery', {
            id: selectedGallery.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
            getGalleries();
            toastr.success('Gallery has been rejected ');
        } else {
            toastr.error('Failed to reject the  gallery');

        }
    } catch (error) {
        console.error(error);
        alert('Something went wrong');
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


const getGalleries = async () => {
    try {
        const response = await axios.get('/get_galleries');
        gallariesData.value = response.data;
        filteredGalleryData.value = response.data;
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
        const response = await axios.post('/approved_gallery', {
            id, menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response) {
            gallariesData.value[index].flag = 'A'; // update UI immediately
            getGalleries();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
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




onMounted(() => {
    getGalleries()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
