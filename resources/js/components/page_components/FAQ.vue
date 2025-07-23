<template>
    <div class="default-tab">
        <div class="tabs">
            <div class="tab-item" :class="{ active: selectedTab === 'add' }" @click="selectedTab = 'add'">
                Add FAQs
            </div>
            <div class="tab-item" :class="{ active: selectedTab === 'manage' }" @click="selectedTab = 'manage'">
                Manage FAQs
            </div>

        </div>
    </div>
    <div class="tab-content">
        <div v-show="selectedTab === 'add'">
            <div class="card p-4 shadow-sm">
                <div class="row align-items-start">
                    <!-- English Section -->
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label">
                                English Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" placeholder="English Title"
                                v-model="formData.english_question" />
                            <textarea class="form-control mt-2" v-model="formData.english_answer" rows="2"
                                placeholder="English Answer"></textarea>
                        </div>
                    </div>
                    <!-- More Languages -->
                    <div class="col-md-5">
                        <p>More Languages</p>
                        <div class="advanced-fields">
                            <div class="mb-3">
                                <label class="form-label">Hindi Question</label>
                                <input type="text" class="form-control" placeholder="Hindi Question"
                                    v-model="formData.hindi_question" />
                                <textarea class="form-control mt-2" rows="2" v-model="formData.hindi_answer"
                                    placeholder="Hindi Answer"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Khasi Question</label>
                                <input type="text" class="form-control" placeholder="Khasi Question"
                                    v-model="formData.khasi_question" />
                                <textarea class="form-control mt-2" rows="2" v-model="formData.khasi_answer"
                                    placeholder="Khasi Answer"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button class="btn btn-success mt-3" @click="submitData">Submit</button>
            </div>
        </div>
        <div v-show="selectedTab === 'manage'">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Template Section -->
                        <table class="table center-aligned-table mb-0" id="faqTable">
                            <thead>
                                <tr>
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
                                <tr v-for="(faq, index) in faqdata" :key="index">
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
                                        <template v-if="faq.flag === 'A'">
                                            <label class="badge bg-success">Approved</label>
                                        </template>
                                        <template v-else-if="faq.flag === 'U'">
                                            <label class="badge bg-warning">Pending</label>
                                        </template>
                                        <template v-else-if="faq.flag === 'R'">
                                            <label class="badge bg-danger">Rejected</label><br>
                                            <small class="text-muted">Remarks: {{ faq.rejected_remarks }}</small>
                                        </template>
                                        <template v-else>
                                            <label class="badge bg-warning">Pending</label>
                                        </template>
                                    </td>
                                    <td>
                                        <i class="fas fa-pencil-alt text-info" v-if="faq.flag !== 'N'"
                                            @click="updateFAQ(faq)">
                                        </i>
                                        &nbsp;
                                        <i class="fas fa-trash-alt text-danger" v-if="faq.flag !== 'N'"
                                            @click="deleteFAQ(faq.id)"></i>

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
import { useToastr } from '../../toaster.js';
import { useRoute } from 'vue-router';
const route = useRoute();
const faqdata = ref();
import Swal from 'sweetalert2';
const props = defineProps({
    menu: String,
    section: Number,
});
const selectedTab = ref('add') // Default to 'add' tab
const menu_id = props.menu;
const page_section_id = props.section.page_section_id;
const toastr = useToastr();
const formData = reactive({
    english_question: "",
    english_answer: "",
    hindi_question: "",
    hindi_answer: "",
    khasi_question: "",
    khasi_answer: "",
});
const currentFAQId = ref(null)
const isEditMode = ref(false)
// Validate input fields before submission
const validateFields = () => {
    if (
        !formData.english_question.trim() ||
        !formData.english_answer.trim()
    ) {
        toastr.error("All fields are required!");
        return false;
    }


    return true;
};
const resetForm = () => {
    Object.keys(formData).forEach(key => formData[key] = "");
    isEditMode.value = false;
    currentFAQId.value = null;
};

const submitData = async () => {
    debugger;
    if (!validateFields()) return;
    const dataToSend = {
        english_question: formData.english_question,
        english_answer: formData.english_answer,
        hindi_question: formData.hindi_question,
        hindi_answer: formData.hindi_answer,
        khasi_question: formData.khasi_question,
        khasi_answer: formData.khasi_answer,
        menu_id: menu_id,
        page_section_master_id: page_section_id
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
        $('#faqeditModal').modal('hide')
        // Reset form after successful submission
        Object.keys(formData).forEach(key => formData[key] = "");
        isEditMode.value = false;
        currentFAQId.value = null;
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
        // Destroy existing DataTable if already initialized
        if ($.fn.DataTable.isDataTable('#faqTable')) {
            $('#faqTable').DataTable().destroy();
        }

        const response = await axios.get('/get_faq');
        faqdata.value = response.data;

        await nextTick(); // Wait until DOM is updated

        $('#faqTable').DataTable({
            scrollX: true,
            scrollY: '400px', // 🔹 Set vertical scroll height
            scrollCollapse: true, // Optional: collapse height when fewer rows
            paging: true,
            pageLength: 10,
        });

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
    $('#faqeditModal').modal('show')
}



const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
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
                id: id, menu_id: menu_id,
                page_section_master_id: page_section_id
            });
            await getFaq();
            Swal.fire('Deleted!', response.data.message || 'FAQ has been deleted.', 'success');
        } catch (error) {
            console.error('Error deleting FAQ:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};

onMounted(() => {
    getFaq()
    console.log("Component Mounted");
});

</script>


<style scoped>
.container {
    max-width: 800px;
}

.advanced-fields {
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 10px;
}

.advanced-fields input,
.advanced-fields textarea {
    background-color: #f1f1f1;
}
</style>