<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <br>
                <h5 class="card-title">Frequently Ask Question</h5>
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
                                <div class="col-md-3">
                                    <label class="form-label">Order <span class="text-danger"> *</span></label>
                                    <input type="number" class="form-control" v-model="formData.order"
                                        placeholder="Order" />
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
                        <table class="table center-aligned-table mb-0" id="faqTable">
                            <thead>
                                <tr class="text-dark">
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
                                    <td>{{ faq.english_title_question || 'N/A' }}</td>
                                    <td>{{ faq.english_answer || 'N/A' }}</td>
                                    <td>{{ faq.hindi_title_question || 'N/A' }}</td>
                                    <td>{{ faq.hindi_answer || 'N/A' }}</td>
                                    <td>{{ faq.khasi_title_question || 'N/A' }}</td>
                                    <td>{{ faq.khasi_answer || 'N/A' }}</td>
                                    <td>{{ faq.addedby || 'N/A' }}</td>
                                    <td>{{ formatDate(faq.created_at) }}</td>
                                    <td>
                                        <label :class="faq.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ faq.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>
                                        <i class="fas fa-trash-alt text-danger" @click="deleteSlide(faq.id)"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                    </div>
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
const formData = reactive({
    english_question: "",
    english_answer: "",
    hindi_question: "",
    hindi_answer: "",
    khasi_question: "",
    khasi_answer: "",
    order: ""
});

onMounted(() => {
    console.log("Component Mounted");
    getFaq();
});

// Validate input fields before submission
const validateFields = () => {
    if (
        !formData.english_question.trim() ||
        !formData.english_answer.trim() ||
        !formData.order.toString().trim()
    ) {
        toastr.error("All fields are required!");
        return false;
    }

    if (isNaN(parseInt(formData.order))) {
        toastr.error("Order must be a valid number.");
        return false;
    }

    return true;
};

const submitData = async () => {
    
    console.log("Current Form Data:", JSON.stringify(formData));

    if (!validateFields()) return;

    const dataToSend = {
        english_question: formData.english_question,
        english_answer: formData.english_answer,
        hindi_question: formData.hindi_question,
        hindi_answer: formData.hindi_answer,
        khasi_question: formData.khasi_question,
        khasi_answer: formData.khasi_answer,
        order: formData.order
    };

    console.log("Prepared API Data:", dataToSend);
    let response;
    try {
        response = await axios.post("/api/submitFAQData", dataToSend);
        toastr.success(response.data.message);
        // Reset form after successful submission
        Object.keys(formData).forEach(key => formData[key] = "");
        console.log("Form reset successfully");

    } catch (error) {
        if (error.message == "Request failed with status code 409") {
            error.message = "same order number already exists"
            toastr.warning(error.message);
        }
    }
    toastr.error(error.message);

};
const getFaq = async () => {
    try {
        const response = await axios.get('/get_faq');
        console.log(response.data);
        faqdata.value = response.data;
        await nextTick() // wait until DOM is updated
        if ($.fn.dataTable.isDataTable('#faqTable')) {
            $('#faqTable').DataTable().destroy()
        }
        $('#faqTable').DataTable()
    } catch (error) {
        console.error('Failed to fetch faqData:', error);
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


<style scoped></style>