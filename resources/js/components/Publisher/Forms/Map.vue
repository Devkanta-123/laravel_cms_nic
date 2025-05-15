<template>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/Kfw5nqKx1pG2eU6R7tZLz1kcfk5iZ3Vf0dUSbNjs2a2g/Og" crossorigin="anonymous">

    <br>

    <div style="display: flex;">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Map </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="MapTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Iframe</th>
                                    <th>Added On</th>
                                    <th>Added By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(map, index) in MapData" :key="index">
                                    <td>
                                        <!-- <img class="img-fluid avatar-small" :src="`/storage/${slide.image}`"
                                            alt="Slide Image" @click="openModal(`/storage/${slide.image}`)"
                                            style="cursor: pointer;"> -->

                                        <div class="embed-responsive embed-responsive-16by9">
                                            <div class="embed-responsive-item" v-html="map.iframe" aria-readonly="">
                                            </div>
                                        </div>

                                    </td>

                                    <td>{{ formatDate(map.created_at) }}</td>
                                    <td>{{ map.addedby }}</td>
                                    <td>
                                        <label :class="map.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ map.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(map.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="map.flag === 'A'"
                                                    :disabled="map.flag === 'A'" @change="approveMap(map.id, index)">
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
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
const MapData = ref();
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
// Reactive state
let formData = ref({
    iframe: ""
});


const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const getMaps = async () => {
    try {
        const response = await axios.get('/get_contactus');
        MapData.value = response.data;
        console.log("MapData" + response.data);
        const data = response.data
        if (Array.isArray(data) && data.length > 0) {
            formData.value.iframe = data[0].iframe || ''
        }
        await nextTick(); // Wait for DOM to update

        // Destroy and reinitialize DataTable
        if ($.fn.dataTable.isDataTable('#MapTable')) {
            $('#MapTable').DataTable().destroy();
        }
        $('#MapTable').DataTable({
            responsive: true,
            pageLength: 10,
        });

    }
    catch (error) {
        console.error('Error:', error?.response?.data);

    }
};

const approveMap = async (id, index) => {
    try {
        const response = await axios.put('/approved_map', { id });
        if (response.data.success) {
            MapData.value[index].flag = 'A'; // update UI immediately
            getMaps();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


onMounted(() => {
    getMaps();
});
</script>

<style scoped>
@import '../assets/css/style.css';
</style>
