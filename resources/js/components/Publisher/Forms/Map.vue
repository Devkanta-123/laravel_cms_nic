<template>
    <br>
    <br>
    <br>
  <div class="content ml-6 mr-6">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Pages / {{ route.params.menuName }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" @click="onBack()" class="btn btn-primary btn-sm pl-3 pr-3 pt-2 pb-2">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="display: flex;">
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Map </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <div class="fc-toolbar fc-header-toolbar">
                            <div class="fc-right mb-3">
                                <div class="fc-button-group">
                                  
                                </div>
                            </div>
                        </div>
                        <table class="table center-aligned-table mb-0" id="MapTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Map</th>
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
                                        <label v-if="map.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="map.flag === 'U'" class="badge bg-warning">
                                            Pending
                                        </label>
                                        <div v-else-if="map.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ map.rejected_remarks }}
                                            </div>
                                        </div>
                                        <label v-else class="badge bg-warning">
                                            Pending
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

                                            &nbsp;
                                            <!-- Show icon only if flag is not 'A' -->
                                            <i class="fas fa-times text-danger" data-toggle="modal"
                                                v-if="map.flag !== 'A' && map.flag !== 'R'" data-target="#rejectedModal"
                                                @click="rejectedModal(map)"></i>
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
    <div class="modal fade" id="rejectedModal" tabindex="-1" role="dialog" aria-labelledby="rejectedModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejected Remarks <span class="text-danger">*</span></label>
                        <textarea v-model="rejectedRemarks" class="form-control" placeholder="Remarks...."></textarea>
                        <span v-if="rejectedRemarksError" class="text-danger">Remarks are
                            required</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="rejected">Rejected
                    </button>
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
import { useRoute,useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
// Reactive state
let formData = ref({
    iframe: ""
});
	
	const onBack = () => {
    if (window.history.length > 1) {
        router.back();
    } else {
        router.push('/publisher/pages-form/1/Home/0')
    }
}
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedMap = ref({}) // To store the clicked notice
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
        const response = await axios.put('/approved_map', { id, menu_id: route.params.menuId, page_section_master_id: route.params.page_section_id });
        if (response.data.success) {
            MapData.value[index].flag = 'A'; // update UI immediately
            getMaps();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


const rejectedModal = (map) => {
    selectedMap.value = map;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};


const rejected = async () => {
    if (!rejectedRemarks.value.trim()) {
        rejectedRemarksError.value = true;
        return;
    }
    rejectedRemarksError.value = false;
    try {
        const response = await axios.post('/rejected_map', {
            id: selectedMap.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response.data.success) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
            getMaps();
            toastr.success('Map  has been Rejected');
        } else {
            toastr.error('Failed to reject the  Map data');

        }
    } catch (error) {
        console.error(error);
        toastr.error('Something went wrong');
    }
};
onMounted(() => {
    getMaps();
});
</script>

<style scoped>
@import '../assets/css/style.css';
</style>
