<template>
    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <h5 class="card-title pb-0 border-0">Logo</h5>
                <!-- action group -->
                <div class="table-responsive">
                    <table class="table center-aligned-table mb-0" id="logoTable">
                        <thead>
                            <tr class="text-dark">
                                <th>Image</th>
                                <th>Added On</th>
                                <th>Added By</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(logo, index) in logoData" :key="index">
                                <td>
                                    <img class="img-fluid avatar-small" :src="`/storage/${logo.image}`"
                                        @click="openModal(`/storage/${logo.image}`)" style="cursor: pointer;"
                                        alt="logo">
                                </td>
                                <td>{{ formatDate(logo.created_at) }}</td>
                                <td>
                                    {{ logo.addedby }}
                                </td>
                                <td>
                                    <label :class="logo.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                        {{ logo.flag === 'A' ? 'Approved' : 'Pending' }}
                                    </label>
                                </td>


                                <td>
                                    <div class="checkbox checbox-switch switch-success">
                                        <label>
                                            <input type="checkbox" :checked="logo.flag === 'A'"
                                                :disabled="logo.flag === 'A'"
                                                @change="approveLatestNews(logo.id, index)">
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
</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const logoData = ref()
const images = ref([]);
const fileInput = ref(null);
const slides = ref([]);
const toastr = useToastr();
const props = defineProps({
    menuId: String,
    section: Number,
});
const showModal = ref(false);
const modalImage = ref('');
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};


const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};



const fetchLogo = async () => {
    try {
        
        const response = await axios.get('/get_logo');
        logoData.value = response.data;
        await nextTick(); // Wait for DOM to update

        if ($.fn.dataTable.isDataTable('#logoTable')) {
            $('#logoTable').DataTable().destroy();
        }
        $('#logoTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    } catch (error) {
        console.error('Failed to fetch banner:', error);
    }
};


const approveLatestNews = async (id, index) => {
    try {
        
        const response = await axios.put('/approved_logo', { id });
        if (response) {
            logoData.value[index].flag = 'A'; // update UI immediately
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};

onMounted(() => {
    fetchLogo();
});

</script>

<style scoped>
@import '../assets/css/style.css';
</style>