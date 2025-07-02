<template>
    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <h5 class="card-title pb-0 border-0">Logo</h5>
                <!-- action group -->
                <div class="table-responsive">
                       <div class="fc-toolbar fc-header-toolbar">
                            <div class="fc-right mb-3">
                                <div class="fc-button-group">
                                     <button type="button"
                                    class="fc-month-button fc-button fc-state-default fc-corner-left fc-state-active"
                                    @click="onBack()"> Back</button>
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
                                <th>Added By</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(logo, index) in filteredLogoData" :key="index">
                                <td>{{ index + 1}}</td>
                                <td>
                                    <img class="img-fluid avatar-small" :src="`/storage/${logo.image}`"
                                        @click="openModal(`/storage/${logo.image}`)" style="cursor: pointer;"
                                        alt="logo">
                                </td>
                                <td>{{ formatDate(logo.created_at) }}</td>
                                <td>
                                    {{ logo.addedby }}
                                </td>
                                <td>
                                    <label v-if="logo.flag === 'A'" class="badge bg-success">
                                        Approved
                                    </label>
                                    <label v-else-if="logo.flag === 'U'" class="badge bg-warning    ">
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
                                    <div class="checkbox checbox-switch switch-success">
                                        <label>
                                            <input type="checkbox" :checked="logo.flag === 'A'"
                                                :disabled="logo.flag === 'A'"
                                                @change="approveLatestNews(logo.id, index)">
                                            <span></span>
                                        </label>&nbsp;
                                        <i class="fas fa-times text-danger"
                                            v-if="logo.flag !== 'A' && logo.flag !== 'R'" data-toggle="modal"
                                            data-target="#rejectedModal" @click="rejectedModal(logo)"></i>
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
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="rejectedModal" tabindex="-1" role="dialog" aria-labelledby="rejectedModalTitle"
        aria-hidden="true">
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
                        <textarea v-model="rejectedRemarks" class="form-control" placeholder="Remarks...."></textarea>
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
</template>
<script setup>
import { useRoute,useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const logoData = ref()
const toastr = useToastr();
const props = defineProps({
    menuId: String,
    section: Number,
});
const filteredLogoData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedLogo = ref({}) // To store the clicked notice
const showModal = ref(false);
const modalImage = ref('');
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};
const rejectedModal = (logo) => {
    selectedLogo.value = logo;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};



const fetchLogo = async () => {
    try {
        const response = await axios.get('/get_logo');
        logoData.value = response.data;
        filteredLogoData.value = response.data;
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

const onBack = () => {
    router.push('/publisher/pages-form/1/Home')
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


const approveLatestNews = async (id, index) => {
    try {

        const response = await axios.put('/approved_logo', { id, menu_id: route.params.menuId, page_section_master_id: route.params.page_section_id });
        if (response) {
            logoData.value[index].flag = 'A'; // update UI immediately
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


const rejected = async () => {
    if (!rejectedRemarks.value.trim()) {
        rejectedRemarksError.value = true;
        return;
    }
    rejectedRemarksError.value = false;
    try {
        const response = await axios.post('/rejected_logo', {
            id: selectedLogo.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response.data.success) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
            fetchLogo();
            toastr.success('Logo  has been Rejected');
        } else {
            toastr.error('Failed to reject the Logo');

        }
    } catch (error) {
        console.error(error);
        toastr.error('Something went wrong');
    }
};
onMounted(() => {
    fetchLogo();
});

</script>

<style scoped>
@import '../assets/css/style.css';
</style>