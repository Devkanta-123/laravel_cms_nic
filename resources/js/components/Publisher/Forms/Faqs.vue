<template>
    <br>
    <div>
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
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(faq.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :faq="faq.flag === 'A'"
                                                    :disabled="faq.flag === 'A'" @change="approveFAQ(faq.id, index)">
                                                <span></span>
                                            </label>
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

</template>
<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const faqdata = ref();
const toastr = useToastr();

onMounted(() => {
    console.log("Component Mounted");
    getFaq();
});


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

const approveFAQ = async (id, index) => {
    try {
        const response = await axios.put('/approved_faq', { id, index });
        if (response) {
            faqdata.value[index].flag = 'A'; // update UI immediately
            getFaq();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};
</script>

<style scoped>
@import '../assets/css/style.css';
</style>
