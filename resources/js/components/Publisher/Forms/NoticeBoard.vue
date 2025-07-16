<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Notice Board </h5>
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
                                    ]" @click="filterByFlag('PENDING')">
                                        Pending
                                    </button>


                                </div>
                            </div>


                        </div>
                        <table class="table center-aligned-table mb-0" id="noticaboardTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>Title</th>
                                    <th>Added By</th>
                                    <th>Approver</th>
                                    <th>Added On</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(notice, index) in filteredNoticeboardData" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td><a :href="`/storage/${notice.file}`" target="_blank" class="text-primary">
                                            {{ notice.title }} <span v-if="notice.ispinned === 1"> <i
                                                    class="fas fa-thumbtack text-danger"></i></span> </a>
                                    </td>
                                    <td>{{ notice.addedby }}</td>
                                    <td>{{ notice.approver || 'N/A' }}</td>
                                    <td>{{ formatDate(notice.created_at) }}</td>
                                    <td>{{ notice.category_name }}</td>
                                    <td>
                                        <label v-if="notice.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="notice.flag === 'U'" class="badge bg-warning">
                                            Pending
                                        </label>

                                        <div v-else-if="notice.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ notice.rejected_remarks }}
                                            </div>
                                        </div>

                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>

                                    <td>
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(news.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="notice.flag === 'A'"
                                                    :disabled="notice.flag === 'A'"
                                                    @change="approveNoticeBoard(notice.id, index)">
                                                <span></span>
                                            </label>
                                            &nbsp;&nbsp;
                                            <!-- Show icon only if flag is not 'A' -->
                                            <i class="fas fa-times text-danger"
                                                v-if="notice.flag !== 'A' && notice.flag !== 'R'" data-toggle="modal"
                                                data-target="#rejectedModal" @click="rejectedModal(notice)"></i>
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                        {{ selectedNotice.title }}
                    </h5>
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
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const categorydata = ref([]); // Store fetched categories
const noticeboarddata = ref([]);
const selectedNotice = ref({}) // To store the clicked notice
import { useRoute, useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const filteredNoticeboardData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
const onBack = () => {
    router.push('/publisher/pages-form/1/Home')
}

// Called when clicking reject icon
const rejectedModal = (notice) => {
    selectedNotice.value = notice;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};
const getAllCategoryMaster = async () => {
    try {
        const response = await axios.get('/api/getAllCategoryMaster');
        categorydata.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error.response || error);
        toastr.error("Failed to load categories.");
    }
};




const getAllNotifications = async () => {
    try {
        const response = await axios.get('/get_notifications');
        noticeboarddata.value = response.data;
        filteredNoticeboardData.value = response.data; // Default to all
        console.log("data", response.data)
        await nextTick(); // Wait for DOM to update
        initDataTable();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const initDataTable = () => {
    // Destroy if already exists
    if ($.fn.dataTable.isDataTable('#noticaboardTable')) {
        $('#noticaboardTable').DataTable().destroy();
    }

    nextTick(() => {
        $('#noticaboardTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
};

// Filter handler
const filterByFlag = async (flag) => {
    activeFlag.value = flag; // Update active button state

    // Destroy existing DataTable
    if ($.fn.dataTable.isDataTable('#noticaboardTable')) {
        $('#noticaboardTable').DataTable().destroy();
    }

    // Filter logic
    if (flag === 'ALL') {
        filteredNoticeboardData.value = [...noticeboarddata.value];
    } else if (flag === 'PENDING') {
        // Both 'U' (Unapproved) and 'N' (New) are considered pending
        filteredNoticeboardData.value = noticeboarddata.value.filter(
            item => item.flag === 'U' || item.flag === 'N'
        );
    } else {
        filteredNoticeboardData.value = noticeboarddata.value.filter(item => item.flag === flag);
    }

    await nextTick();
    initDataTable();
};

const approveNoticeBoard = async (id, index) => {
    try {
        const response = await axios.put('/approved_noticeboard', {
            id, menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response) {
            noticeboarddata.value[index].flag = 'A'; // update UI flag immediately
            getAllNotifications();
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
        const response = await axios.post('/api/rejectedNotification', {
            id: selectedNotice.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });

        if (response.data.success) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
            getAllNotifications();
            toastr.success('Notice rejected successfully');
        } else {
            alert('Failed to reject the notice');
        }
    } catch (error) {
        console.error(error);
        alert('Something went wrong');
    }
};


onMounted(() => {
    getAllNotifications()
    getAllCategoryMaster()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
