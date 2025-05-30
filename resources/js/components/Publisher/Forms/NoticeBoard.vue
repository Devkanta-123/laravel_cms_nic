<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Notice Board </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="noticaboardTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Title</th>
                                    <th>Added By</th>
                                    <th>Added On</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(notice, index) in noticeboarddata" :key="index">
                                    <td>
                                        <a :href="`/storage/${notice.file}`" target="_blank" class="text-primary">
                                            {{ notice.title }}
                                        </a>
                                    </td>
                                    <td>{{ notice.addedby }}</td>
                                    <td>{{ formatDate(notice.created_at) }}</td>
                                    <td>{{ notice.category_name }}</td>
                                    <td>
                                        <label v-if="notice.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="notice.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>
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
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const categorydata = ref([]); // Store fetched categories
const noticeboarddata = ref([]);

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
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
        await nextTick(); // Wait for DOM to update
        // Destroy and reinitialize DataTable
        if ($.fn.dataTable.isDataTable('#noticaboardTable')) {
            $('#noticaboardTable').DataTable().destroy();
        }
        $('#noticaboardTable').DataTable({
            responsive: true,
            pageLength: 10,
        });

        console.log('Notice Board   data', response.data);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const approveNoticeBoard = async (id, index) => {
    try {
        const response = await axios.put('/approved_noticeboard', { id });
        if (response) {
            noticeboarddata.value[index].flag = 'A'; // update UI flag immediately
            getAllNotifications();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
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
