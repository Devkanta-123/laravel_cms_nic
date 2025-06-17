<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Activity Log</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Activity Log</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Activity Log</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <table class="table center-aligned-table mb-0" id="activityLogTable">
                                                <thead>
                                                    <tr class="text-dark">
                                                        <th>Menu</th>
                                                        <th>PageSection</th>
                                                        <th>FromUser</th>
                                                        <th>ToUser</th>
                                                        <th>EntryDateTime</th>
                                                        <th>Remarks</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(activity, index) in activityLogData" :key="index">
                                                        <td>{{ activity.menu_name }}</td>
                                                        <td>{{ activity.page_section_name }}</td>
                                                        <td>{{ activity.user_from_name }}</td>
                                                        <td>{{ activity.user_to_name || 'N/A'}}</td>
                                                        <!-- <td>{{ activity.action }}</td> -->
                                                        <td>{{ activity.created_at }}</td>
                                                        <td>{{ activity.remarks }}</td>
                                                        <td>
                                                            <label :class="{
                                                                'badge bg-info': activity.action === 'Add',
                                                                'badge bg-primary': activity.action === 'Updated',
                                                                'badge bg-danger': activity.action === 'Rejected',
                                                                'badge bg-success': activity.action === 'Approved',
                                                                'badge bg-secondary': activity.action === 'Deleted',
                                                                'badge bg-warning': !['Add', 'Updated', 'Rejected', 'New', 'Approved', 'Deleted'].includes(activity.action)
                                                            }">
                                                                {{ formatActionLabel(activity.action) }}
                                                            </label>
                                                        </td>
                                                        <!-- <td>
                                                            <i class="fas fa-trash-alt text-danger"
                                                                @click="deleteSlide(news.id)"></i>
                                                        </td> -->
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
const activityLogData = ref();
const toastr = useToastr();
const getActivityLog = async () => {
    try {
        const response = await axios.get('/api/get_archivitylog');
        if (response.data && response.data.status === 'success') {
            activityLogData.value = response.data.data; // <-- correct this
            // Destroy and re-init DataTable if already initialized
            if ($.fn.DataTable.isDataTable('#activityLogTable')) {
                $('#activityLogTable').DataTable().destroy();
            }

            nextTick(() => {
                $('#activityLogTable').DataTable({
                    responsive: true,
                    pageLength: 10
                });
            });
        }
    } catch (error) {
        toastr.error('Failed to fetch activity log.');
    }
}

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
};

const formatActionLabel = (action) => {
    switch (action) {
        case 'Add':
            return 'NewEntry';
        case 'Updated':
            return 'Updated';
        case 'Rejected':
            return 'Rejected';
        case 'Approved':
            return 'Approved';
        case 'Deleted':
            return 'Deleted';
        case 'New':
            return 'New';
        default:
            return 'Pending';
    }
};


onMounted(() => {
    getActivityLog()
})

</script>
<style scoped></style>
