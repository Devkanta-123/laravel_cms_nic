<template>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/Kfw5nqKx1pG2eU6R7tZLz1kcfk5iZ3Vf0dUSbNjs2a2g/Og" crossorigin="anonymous">

    <br>

    <div style="display: flex;">
        <div class="col-xl-4 mb-30">
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title">Map </h5>
                    <br>
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <section class="body current" aria-hidden="false">
                                <label class="form-label">iFrame</label>
                                <textarea type="text" class="form-control" v-model="formData.iframe" row="3"
                                    placeholder="Enter iFrame"></textarea>
                            </section>
                        </div>
                        <!-- Save Button -->
                        <div class="actions clearfix mt-3">
                            <ul role="menu" aria-label="Pagination">
                                <li>
                                    <button class="btn btn-success" @click="submitData">Save</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xl-8 mb-30">
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
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const MapData = ref();
const toastr = useToastr();

// Reactive state
let formData = ref({
    iframe: ""
});
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



const loading = ref(false);
const submitData = async () => {
    try {
        debugger;
        loading.value = true;
        const response = await axios.post('/api/addMapData', formData.value, {
            headers: {
                'Content-Type': 'application/json',
            },
        });

        await getMaps();
        toastr.success(response.data.message);
    } catch (error) {
        console.error('Error:', error?.response?.data);
        toastr.error(error?.response?.data?.message || 'Something went wrong');
    } finally {
        loading.value = false;
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
