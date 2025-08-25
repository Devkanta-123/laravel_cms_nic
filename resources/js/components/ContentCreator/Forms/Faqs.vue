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
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <br>
                <h5 class="card-title"> Frequently Ask Question</h5>
                <div class="card-body">
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">English Question<span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" placeholder="English Question"
                                        v-model="formData.english_question" />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Hindi Question</label>
                                    <input type="text" class="form-control" placeholder="Hindi Question"
                                        v-model="formData.hindi_question" />
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Khasi Question</label>
                                    <input type="text" class="form-control" placeholder="Khasi Question"
                                        v-model="formData.khasi_question" />
                                    <!-- <textarea class="form-control mt-2" v-model="formData.english_answer" rows="2"
                                            placeholder="English Answer"></textarea> -->
                                </div>
                             
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">English Answer<span class="text-danger"> *</span></label>
                                    <textarea class="form-control mt-2" v-model="formData.english_answer" rows="2"
                                        placeholder="English Answer"></textarea>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Hindi Answer</label>
                                    <textarea class="form-control mt-2" rows="2" v-model="formData.hindi_answer"
                                        placeholder="Hindi Answer"></textarea>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Khasi Description</label>
                                    <textarea class="form-control mt-2" rows="2" v-model="formData.khasi_answer"
                                        placeholder="Khasi Answer"></textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="form-label my-1 me-2" for="inlineFormSelectPref">Publisher <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" v-model="selectedPublisher">
                                    <option value="" disabled>Select the Publisher</option>
                                    <option v-for="publisher in publisherData" :key="publisher.id"
                                        :value="publisher.id">
                                        {{ publisher.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="actions clearfix mt-3">
                                <ul role="menu" aria-label="Pagination">
                                    <li>
                                        <button type="submit" class="btn btn-success" role="menuitem"
                                            @click="submitData">
                                            Save
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">FAQ's</h5>
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
                        <table class="table center-aligned-table mb-0" id="faqTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>English Question</th>
                                    <th>English Answer</th>
                                    <!-- <th>Hindi Question</th>
                                    <th>Hindi Answer</th>
                                    <th>Khasi Question</th>
                                    <th>Khasi Answer</th> -->
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
                                    <!-- <td>{{ faq.hindi_title_question || 'N/A' }}</td>
                                    <td>{{ faq.hindi_answer || 'N/A' }}</td>
                                    <td>{{ faq.khasi_title_question || 'N/A' }}</td>
                                    <td>{{ faq.khasi_answer || 'N/A' }}</td> -->
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
                                        <i class="fas fa-pencil-alt text-info" v-if="faq.flag !== 'N' && faq.flag !== 'A'"
                                            @click="updateFAQ(faq)"></i>&nbsp;
                                        <!-- <i class="fas fa-trash-alt text-danger" v-if="faq.flag !== 'A'"
                                            @click="deleteFAQ(faq.id)"></i> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FAQ  edit Modal -->
    <div class="modal fade" id="faqeditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        {{ isEditMode ? 'Edit FAQ' : 'Add FAQ' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        @click="resetForm">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitData">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">English Question<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="English Question"
                                    v-model="formData.english_question" required />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hindi Question</label>
                                <input type="text" class="form-control" placeholder="Hindi Question"
                                    v-model="formData.hindi_question" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Khasi Question</label>
                                <input type="text" class="form-control" placeholder="Khasi Question"
                                    v-model="formData.khasi_question" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Order<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" placeholder="Order" v-model="formData.order"
                                    required readonly />
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label class="form-label">English Answer<span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="2" placeholder="English Answer"
                                    v-model="formData.english_answer" required></textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hindi Answer</label>
                                <textarea class="form-control" rows="2" placeholder="Hindi Answer"
                                    v-model="formData.hindi_answer"></textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Khasi Answer</label>
                                <textarea class="form-control" rows="2" placeholder="Khasi Answer"
                                    v-model="formData.khasi_answer"></textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Publisher<span class="text-danger">*</span></label>
                                <select class="form-control" v-model="selectedPublisher" required>
                                    <option value="" disabled>Select the Publisher</option>
                                    <option v-for="publisher in publisherData" :key="publisher.id"
                                        :value="publisher.id">
                                        {{ publisher.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                @click="resetForm">Close</button>
                            <button type="submit" class="btn btn-success">
                                {{ isEditMode ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';
const route = useRoute();
const router = useRouter();
const selectedPublisher = ref("");
const publisherData = ref([]); // Store publisher categories
const faqdata = ref();
const isEditMode = ref(false)
const currentFAQId = ref(null)
const toastr = useToastr();
const formData = reactive({
    english_question: "",
    english_answer: "",
    hindi_question: "",
    hindi_answer: "",
    khasi_question: "",
    khasi_answer: "",
});
const filteredFAQData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const resetForm = () => {
    Object.keys(formData).forEach(key => formData[key] = "");
    selectedPublisher.value = "";
    isEditMode.value = false;
    currentFAQId.value = null;
};
const onBack = () => {
    router.push('/contentcreator/pages-form/1/Home/0')
}

onMounted(() => {
    getFaq();
    getAllPublisher();
});
const getAllPublisher = async () => {
    try {
        const response = await axios.get('/api/get_allpublisher');
        publisherData.value = response.data.data;
        // Auto-select if only one publisher exists
        if (publisherData.value.length === 1) {
            selectedPublisher.value = publisherData.value[0].id;
        }
    } catch (error) {
        console.error('Error fetching publishers:', error.response || error);
        toastr.error("Failed to load publishers.");
    }
};

// Validate input fields before submission
const validateFields = () => {
    if (
        !formData.english_question.trim() ||
        !formData.english_answer.trim()
    ) {
        toastr.error("All fields are required!");
        return false;
    }

    if (!selectedPublisher.value) {
        toastr.error("Please select a publisher.");
        return false;
    }
   

    return true;
};

// const submitData = async () => {
//     if (!validateFields()) return;
//     const dataToSend = {
//         english_question: formData.english_question,
//         english_answer: formData.english_answer,
//         hindi_question: formData.hindi_question,
//         hindi_answer: formData.hindi_answer,
//         khasi_question: formData.khasi_question,
//         khasi_answer: formData.khasi_answer,
//         order: formData.order,
//         menu_id: route.params.menuId,
//         page_section_master_id: route.params.page_section_id,
//         publisher_id: selectedPublisher.value
//     };
//     console.log("Prepared API Data:", dataToSend);
//     let response;
//     try {
//         response = await axios.post("/api/submitFAQData", dataToSend);
//         await getFaq();
//         toastr.success(response.data.message);
//         // Reset form after successful submission
//         Object.keys(formData).forEach(key => formData[key] = "");
//     } catch (error) {
//         if (error.message == "Request failed with status code 409") {
//             error.message = "same order number already exists"
//             toastr.warning(error.message);
//         }
//     }
//     toastr.error(error.message);

// };

const submitData = async () => {
    if (!validateFields()) return;
    const dataToSend = {
        english_question: formData.english_question,
        english_answer: formData.english_answer,
        hindi_question: formData.hindi_question,
        hindi_answer: formData.hindi_answer,
        khasi_question: formData.khasi_question,
        khasi_answer: formData.khasi_answer,
        menu_id: route.params.menuId,
        page_section_master_id: route.params.page_section_id,
        publisher_id: selectedPublisher.value
    };
    console.log("Prepared API Data:", dataToSend);
    let response;
    try {
        if (isEditMode.value && currentFAQId.value) {
            // UPDATE existing FAQ
            response = await axios.post(`/api/update_faq/${currentFAQId.value}`, dataToSend);
        } else {
            // CREATE new FAQ
            response = await axios.post("/api/submitFAQData", dataToSend);
        }

        await getFaq();
        toastr.success(response.data.message);

        // Reset form after successful submission
        Object.keys(formData).forEach(key => formData[key] = "");
        selectedPublisher.value = "";
        isEditMode.value = false;
        currentFAQId.value = null;
        $('#faqeditModal').modal('hide'); // Hide modal
    } catch (error) {
        if (error.response && error.response.status === 409) {
            toastr.warning("Same order number already exists");
        } else {
            toastr.error(error.message || "Something went wrong");
        }
    }
};

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
const updateFAQ = (faq) => {
    isEditMode.value = true
    currentFAQId.value = faq.id
    formData.english_question = faq.english_title_question || ''
    formData.hindi_question = faq.hindi_title_question || ''
    formData.khasi_question = faq.khasi_title_question || ''
    formData.english_answer = faq.english_answer || ''
    formData.hindi_answer = faq.hindi_answer || ''
    formData.khasi_answer = faq.khasi_answer || ''
    formData.order = faq.order || ''
    selectedPublisher.value = faq.publisher_id || ''
    $('#faqeditModal').modal('show')
}
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

const deleteFAQ = async (id) => {
    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this FAQ? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.post('/api/deleted_faq', {
                id: id,
                menu_id: route.params.menuId,
                page_section_master_id: route.params.page_section_id
            });
            await getFaq();
            Swal.fire('Deleted!', response.data.message || 'FAQ has been deleted.', 'success');
        } catch (error) {
            console.error('Error deleting FAQ:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
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
</script>


<style scoped>
@import '../assets/css/style.css';
</style>
