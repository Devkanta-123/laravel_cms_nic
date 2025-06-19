<template>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/Kfw5nqKx1pG2eU6R7tZLz1kcfk5iZ3Vf0dUSbNjs2a2g/Og" crossorigin="anonymous">
    <br>
    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <h5 class="card-title pb-0 border-0">Carousel</h5>
                <!-- action group -->
                <div class="table-responsive">
                    <table id="slidesTable" class="table center-aligned-table mb-0 display">
                        <thead>
                            <tr class="text-dark">
                                <th>#</th>
                                <th>Image</th>
                                <th>Added by</th>
                                <th>Added On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(slide, index) in slides" :key="slide.id">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <img class="img-fluid avatar-small" :src="`/storage/${slide.image}`"
                                        alt="Slide Image" @click="openModal(`/storage/${slide.image}`)"
                                        style="cursor: pointer;">
                                </td>
                                <td>{{ slide.addedby }}</td>
                                <td>{{ formatDate(slide.addedon) }}</td>
                                <td>
                                    <label v-if="slide.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="slide.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>
                                        <div v-else-if="slide.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ slide.rejected_remarks }}
                                            </div>
                                        </div>

                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                </td>
                                <td>
                                    <div class="checkbox checbox-switch switch-success">
                                        <label>
                                            <input type="checkbox" :checked="slide.flag === 'A'"
                                                :disabled="slide.flag === 'A'" @change="approveSlide(slide.id, index)">
                                            <span></span>
                                        </label>
                                        &nbsp;&nbsp;
                                        <!-- Show icon only if flag is not 'A' -->
                                        <i class="fas fa-times text-danger"
                                            v-if="slide.flag !== 'A' && slide.flag !== 'R'" data-toggle="modal"
                                            data-target="#rejectedModal" @click="rejectedModal(slide)"></i>
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
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const showModal = ref(false);
import { useRoute } from 'vue-router';
const route = useRoute();
const modalImage = ref('');
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedSlide = ref({}) // To store the clicked notice
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

const rejectedModal = (slide) => {
    selectedSlide.value = slide;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};

const rejected = async () => {
    debugger;
    if (!rejectedRemarks.value.trim()) {
        rejectedRemarksError.value = true;
        return;
    }
    rejectedRemarksError.value = false;
    try {
        const response = await axios.post('/rejectedSlide', {
            id: selectedSlide.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response.data.success) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
           fetchSlides();
            toastr.success('Carousel rejected successfully');
        } else {
            alert('Failed to reject the notice');
            toastr.error('Failed to reject the  carousel');

        }
    } catch (error) {
        console.error(error);
        alert('Something went wrong');
    }
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
        const response = await axios.get('/get_carousel', {
            params: { flag: 4 }
        })
        slides.value = response.data

        await nextTick() // wait until DOM is updated
        if ($.fn.dataTable.isDataTable('#slidesTable')) {
            $('#slidesTable').DataTable().destroy()
        }
        $('#slidesTable').DataTable()
    } catch (error) {
        console.error('Failed to fetch slides:', error)
    }
}
const approveSlide = async (id, index) => {
    try {
        const response = await axios.post('/approve_carousel', { id, menu_id: route.params.menuId, page_section_master_id: route.params.page_section_id });
        if (response.data.success) {
            slides.value[index].flag = 'A'; // update UI immediately
            fetchSlides();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


onMounted(() => {
    fetchSlides();
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
