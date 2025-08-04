<template>
    <div class="card card-statistics h-100">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table center-aligned-table mb-0" id="feedbackTable">
                    <thead>
                        <tr class="text-dark">
                            <th>Name</th>
                            <th>Added On</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <!-- Optional: <th>Message</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in feedbackData" :key="index">
                            <td>{{ item.name }}</td>
                            <td>{{ formatDate(item.created_at) }}</td>
                            <td>{{ item.phone_no }}</td>
                            <td>{{ item.email || 'N/A' }}</td>
                            <!-- <td>{{ item.message }}</td> -->
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
    </div>



</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
const feedbackData = ref()
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};


const getFeedbacks = async () => {
    try {
        const response = await axios.get('/api/getFeedBacks');
        feedbackData.value = response.data;

        await nextTick();

        if ($.fn.dataTable.isDataTable('#feedbackTable')) {
            $('#feedbackTable').DataTable().destroy();
        }

        $('#feedbackTable').DataTable({
            responsive: true,
            pageLength: 5,
        });
    } catch (error) {
        console.error('Failed to fetch Feedback:', error);
    }
};

onMounted(() => {
    getFeedbacks();
});

</script>
