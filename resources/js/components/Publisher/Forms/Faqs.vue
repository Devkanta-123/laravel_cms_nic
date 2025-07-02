<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">FAQ's</h5>
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
                        <table class="table center-aligned-table mb-0" id="faqTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>English Question</th>
                                    <th>English Answer</th>
                                    <th>Hindi Question</th>
                                    <th>Hindi Answer</th>
                                    <th>Khasi Question</th>
                                    <th>Khasi Answer</th>
                                    <th>Added By</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(faq, index) in filteredFAQData" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ faq.english_title_question || 'N/A' }}</td>
                                    <td>{{ faq.english_answer || 'N/A' }}</td>
                                    <td>{{ faq.hindi_title_question || 'N/A' }}</td>
                                    <td>{{ faq.hindi_answer || 'N/A' }}</td>
                                    <td>{{ faq.khasi_title_question || 'N/A' }}</td>
                                    <td>{{ faq.khasi_answer || 'N/A' }}</td>
                                    <td>{{ faq.addedby || 'N/A' }}</td>
                                    <td>{{ formatDate(faq.created_at) }}</td>
                                    <td>
                                        <label v-if="faq.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="faq.flag === 'U'" class="badge bg-warning">
                                            Pending
                                        </label>
                                        <div v-else-if="faq.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ faq.rejected_remarks }}
                                            </div>
                                        </div>

                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>
                                    <td>
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(faq.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :faq="faq.flag === 'A'"
                                                    :disabled="faq.flag === 'A'" @change="approveFAQ(faq.id, index)">
                                                <span></span>
                                            </label>
                                            <i class="fas fa-times text-danger"
                                                v-if="faq.flag !== 'A' && faq.flag !== 'R'" data-toggle="modal"
                                                data-target="#rejectedModal" @click="rejectedModal(faq)"></i>
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
import { onMounted, reactive, ref, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const faqdata = ref();
const toastr = useToastr();
import { useRoute,useRouter } from 'vue-router';
const router = useRouter();
import Swal from 'sweetalert2';
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedFAQ = ref({}) // To store the clicked notice
const route = useRoute();
onMounted(() => {
    console.log("Component Mounted");
    getFaq();
});
const filteredFAQData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const getFaq = async () => {
    try {
        const response = await axios.get('/get_faq');
        console.log(response.data);
        faqdata.value = response.data;
        filteredFAQData.value = response.data;
        await nextTick() // wait until DOM is updated
        if ($.fn.dataTable.isDataTable('#faqTable')) {
            $('#faqTable').DataTable().destroy()
        }
        $('#faqTable').DataTable()
    } catch (error) {
        console.error('Failed to fetch faqData:', error);
    }
};


const onBack = () => {
    router.push('/publisher/pages-form/1/Home')
}
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const approveFAQ = async (id, index) => {
    try {
        const response = await axios.put('/approved_faq', {
            id, index, menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response) {
            faqdata.value[index].flag = 'A'; // update UI immediately
            getFaq();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};

const rejectedModal = (slide) => {
    selectedFAQ.value = slide;
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
        const response = await axios.post('/rejectedFAQ', {
            id: selectedFAQ.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response.data.success) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
            getFaq();
            toastr.success('FAQ  been rejected');
        } else {
            alert('Failed to reject the notice');
            toastr.error('Failed to reject the  Faq');

        }
    } catch (error) {
        console.error(error);
        alert('Something went wrong');
    }
};

const filterByFlag = async (flag) => {
    activeFlag.value = flag; // Update active button state
    // Destroy existing DataTable
    if ($.fn.dataTable.isDataTable('#faqTable')) {
        $('#faqTable').DataTable().destroy();
    }

    // Filter logic
    if (flag === 'ALL') {
        filteredFAQData.value = [...faqdata.value];
    } else if (flag === 'PENDING') {
        // Both 'U' (Unapproved) and 'N' (New) are considered pending
        filteredFAQData.value = faqdata.value.filter(
            item => item.flag === 'U' || item.flag === 'N'
        );
    } else {
        filteredFAQData.value = faqdata.value.filter(item => item.flag === flag);
    }
    await nextTick();
    initDataTable();
};


const initDataTable = () => {
    // Destroy if already exists
    if ($.fn.dataTable.isDataTable('#faqTable')) {
        $('#faqTable').DataTable().destroy();
    }
    nextTick(() => {
        $('#faqTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
};


</script>

<style scoped>
@import '../assets/css/style.css';
</style>
