<template>
    <div class="tabs">
        <div class="tab-item" :class="{ active: selectedTab === 'add' }" @click="selectedTab = 'add'">
            Add Map
        </div>
        <div class="tab-item" :class="{ active: selectedTab === 'manage' }" @click="selectedTab = 'manage'">
            Manage Map
        </div>

    </div>
    <div class="tab-content">
        <div v-show="selectedTab === 'add'">
            <div class="container mt-4">
                <div class="mb-3 row">
                    <div class="col-md-12">
                        <label class="form-label">iFrame</label>
                        <input type="text" class="form-control" v-model="formData.iframe" placeholder="Enter iFrame" />
                    </div>
                </div>
                <button class="btn btn-success" @click="submitData">Save</button>
            </div>
        </div>
        <div v-show="selectedTab === 'manage'">
            <div class="card card-statistics h-100">
                <div class="card-body">
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
                                        <i class="fas fa-trash-alt text-danger" @click="deleteSlide(map.id)"></i>
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
import { ref, nextTick, onMounted } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
const selectedTab = ref('add') // Default to 'add' tab
const toastr = useToastr();
const MapData = ref();
// Reactive state
const formData = ref({
    iframe: ""
});

// Submit form data to the backend
const submitData = async () => {
    try {
        debugger;
        const response = await axios.post('/api/addMapData', formData.value, {
            headers: {
                'Content-Type': 'application/json',
            },
        });

        // Handle success response
        toastr.success(response.data.message);
    } catch (error) {
        // Handle error
        console.error('Error:', error?.response?.data);
        toastr.error(error?.response?.data?.message || 'Something went wrong');
    }
};

const getMaps = async () => {
    try {
        const response = await axios.get('/get_contactus');
        const data = response.data;

        if (Array.isArray(data) && data.length > 0) {
            MapData.value = data;
            formData.value.iframe = data[0].iframe || '';
        } else {
            MapData.value = [];
            formData.value.iframe = '';
        }
        // Wait for Vue to update the DOM
        await nextTick();

        // Only reset DataTable if data is available and rows are rendered
        if (Array.isArray(MapData.value) && MapData.value.length > 0) {
            if ($.fn.dataTable.isDataTable('#MapTable')) {
                $('#MapTable').DataTable().destroy();
            }

            $('#MapTable').DataTable({
                responsive: true,
                pageLength: 10,
            });
        }

    } catch (error) {
        console.error('Error loading map data:', error?.response?.data);
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




onMounted(() => {
    getMaps();
});
</script>

<style scoped></style>
