<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">WhosWho</h5>
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
                        <table class="table center-aligned-table mb-0" id="whoswhoTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>District Name</th>
                                    <th>Block Name</th>
                                    <th>Designation</th>
                                    <th>Added By</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(whoswho, index) in filteredWhosWhoData" :key="index">
                                    <td>{{ index + 1}}</td>
                                    <td>
                                        <img class="direct-chat-img" :src="whoswho.profile_image
                                            ? `/storage/${whoswho.profile_image.replace('public/', '')}`
                                            : userlogo" alt="Profile Image"
                                            @click="whoswho.profile_image && openModal(`/storage/${whoswho.profile_image.replace('public/', '')}`)"
                                            :style="{ cursor: whoswho.profile_image ? 'pointer' : 'default' }" />
                                    </td>
                                    <td>{{ whoswho.name }}</td>
                                    <td>{{ whoswho.level_name ? whoswho.level_name + ' Level ' : 'N/A' }}</td>
                                    <td>{{ whoswho.district_name ? whoswho.district_name : 'N/A' }}</td>
                                    <td>{{ whoswho.block_name ? whoswho.block_name : 'N/A' }}</td>
                                    <td>{{ whoswho.designation }}</td>
                                    <td>{{ whoswho.addedby }}</td>
                                    <td>{{ formatDate(whoswho.created_at) }}</td>
                                    <td>
                                        <label v-if="whoswho.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="whoswho.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>
                                        <div v-else-if="whoswho.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ whoswho.rejected_remarks }}
                                            </div>
                                        </div>

                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>
                                    <td>
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="whoswho.flag === 'A'"
                                                    :disabled="whoswho.flag === 'A'"
                                                    @change="approveWhosWho(whoswho.id, index)">
                                                <span></span>
                                            </label>&nbsp;
                                            <i class="fas fa-times text-danger"
                                                v-if="whoswho.flag !== 'A' && whoswho.flag !== 'R'" data-toggle="modal"
                                                data-target="#rejectedModal" @click="rejectedModal(whoswho)"></i>
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
    </div>
    <div class="modal fade" id="rejectedModal" tabindex="-1" role="dialog" aria-labelledby="rejectedModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejected Remarks <span class="text-danger">*</span></label>
                        <textarea v-model="rejectedRemarks" class="form-control" placeholder="Remarks...."></textarea>
                        <span v-if="rejectedRemarksError" class="text-danger">Remarks are required</span>
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
import { onMounted, ref, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const showModal = ref(false);
const modalImage = ref('');
const WhosWhoData = ref();
import { useRoute,useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
import userlogo from '@/assets/images/user.jpg'
const selectedWhosWho = ref({})
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const filteredWhosWhoData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};


const getWhosWho = async () => {
    try {
        const response = await axios.get('/get_whoswho');
        WhosWhoData.value = response.data;
        filteredWhosWhoData.value = response.data;
        await nextTick(); // Wait for DOM to update
        if ($.fn.dataTable.isDataTable('#whoswhoTable')) {
            $('#whoswhoTable').DataTable().destroy();
        }
        $('#whoswhoTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
        console.log(response.data);

    }
    catch (error) {
        console.error('Error fetching whoswhodata', error.response);

    }
}

const approveWhosWho = async (id, index) => {
    try {
        const response = await axios.put('/approved_whoswho', { id, menu_id: route.params.menuId, page_section_master_id: route.params.page_section_id });
        if (response.data.success) {
            WhosWhoData.value[index].flag = 'A'; // update UI immediately
            getWhosWho();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


const rejectedModal = (whoswho) => {
    selectedWhosWho.value = whoswho;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};

const rejected = async () => {
    if (!rejectedRemarks.value.trim()) {
        rejectedRemarksError.value = true;
        return;
    }

    rejectedRemarksError.value = false;
    try {
        const response = await axios.post('/api/rejected_whoswho', {
            id: selectedWhosWho.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });

        if (response.data.success) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
            getWhosWho();
            toastr.success('WHosWho has been  rejected');
        } else {
            toastr.error('Failed to reject the WHosWho');
        }
    } catch (error) {
        console.error(error);
        toastr.error('Something went wrong');
    }
};

const initDataTable = () => {
    // Destroy if already exists
    if ($.fn.dataTable.isDataTable('#whoswhoTable')) {
        $('#whoswhoTable').DataTable().destroy();
    }

    nextTick(() => {
        $('#whoswhoTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
};

// Filter handler
const filterByFlag = async (flag) => {
    activeFlag.value = flag; // Update active button state

    // Destroy existing DataTable
    if ($.fn.dataTable.isDataTable('#whoswhoTable')) {
        $('#whoswhoTable').DataTable().destroy();
    }

    // Filter logic
    if (flag === 'ALL') {
        filteredWhosWhoData.value = [...WhosWhoData.value];
    } else if (flag === 'PENDING') {
        // Both 'U' (Unapproved) and 'N' (New) are considered pending
        filteredWhosWhoData.value = WhosWhoData.value.filter(
            item => item.flag === 'U' || item.flag === 'N'
        );
    } else {
        filteredWhosWhoData.value = WhosWhoData.value.filter(item => item.flag === flag);
    }

    await nextTick();
    initDataTable();
};


const onBack = () => {
    router.push('/contentcreator/pages')
}


onMounted(() => {
    getWhosWho();
});


</script>
<style scoped>
@import '../assets/css/style.css';
</style>
