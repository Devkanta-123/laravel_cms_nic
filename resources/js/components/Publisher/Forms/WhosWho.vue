<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">WhosWho</h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="whoswhoTable">
                            <thead>
                                <tr class="text-dark">
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
                                <tr v-for="(whoswho, index) in WhosWhoData" :key="index">
                                    <td>
                                        <img class="img-fluid avatar-small"
                                            :src="`/storage/${whoswho.profile_image.replace('public/', '')}`"
                                            alt="Profile Image"
                                            @click="openModal(`/storage/${whoswho.profile_image.replace('public/', '')}`)"
                                            style="cursor: pointer;" />
                                    </td>
                                    <td>{{ whoswho.name }}</td>
                                    <td>{{ whoswho.level_name ? whoswho.level_name + ' Level ' : 'N/A' }}</td>
                                    <td>{{ whoswho.district_name ? whoswho.district_name : 'N/A' }}</td>
                                    <td>{{ whoswho.block_name ? whoswho.block_name : 'N/A' }}</td>
                                    <td>{{ whoswho.designation }}</td>
                                    <td>{{ whoswho.addedby }}</td>
                                    <td>{{ formatDate(whoswho.created_at) }}</td>
                                    <td>
                                        <label :class="whoswho.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ whoswho.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="whoswho.flag === 'A'"
                                                    :disabled="whoswho.flag === 'A'"
                                                    @change="approveWhosWho(whoswho.id, index)">
                                                <span></span>
                                            </label>
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


</template>

<script setup>
import { onMounted, ref, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const showModal = ref(false);
const modalImage = ref('');
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const WhosWhoData = ref();

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
        
        const response = await axios.put('/approved_whoswho', { id });
        if (response.data.success) {
            WhosWhoData.value[index].flag = 'A'; // update UI immediately
            getWhosWho();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


onMounted(() => {
    getWhosWho();
});


</script>
<style scoped>
@import '../assets/css/style.css';
</style>
