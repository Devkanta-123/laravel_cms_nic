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
    <div>
        <div class="col-xl-12 mb-30">
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title">Map </h5>
                    <br>
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <section class="body current" aria-hidden="false">

                                <!-- Row 1: Latitude & Longitude -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Latitude</label>
                                        <input type="number" step="0.0000001" class="form-control"
                                            v-model="formData.lat" placeholder="Enter Latitude" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Longitude</label>
                                        <input type="number" step="0.0000001" class="form-control"
                                            v-model="formData.lng" placeholder="Enter Longitude" />
                                    </div>
                                </div>

                                <!-- Row 2: Zoom & Place Name -->
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Zoom</label>
                                        <input type="number" min="1" max="20" class="form-control"
                                            v-model="formData.zoom" placeholder="Enter Zoom (default 14)" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Place Name</label>
                                        <input type="text" class="form-control" v-model="formData.place_name"
                                            placeholder="Enter Place Name" />
                                    </div>
                                </div>

                                <!-- Row 3: Publisher -->
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <label class="form-label my-1 me-2" for="inlineFormSelectPref">Publisher <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select my-1 me-sm-2" v-model="selectedPublisher">
                                            <option value="" disabled>Select the Publisher</option>
                                            <option v-for="publisher in publisherData" :key="publisher.id"
                                                :value="publisher.id">
                                                {{ publisher.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="actions clearfix mt-3">
                                    <ul role="menu" aria-label="Pagination">
                                        <li>
                                            <button class="btn btn-success" @click="submitData">Save</button>
                                        </li>
                                    </ul>
                                </div>

                            </section>
                        </div>
                    </div>


                </div>

            </div>
        </div>

        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Map </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <div class="fc-toolbar fc-header-toolbar">
                            <div class="fc-right mb-3">
                                <div class="fc-button-group">
                                    <button type="button" :class="[
                                        'fc-month-button fc-button fc-state-default fc-corner-left',
                                        activeFlag === 'ALL' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('ALL')">All</button>

                                    <button type="button" :class="[
                                        'fc-month-button fc-button fc-state-default fc-corner-left',
                                        activeFlag === 'A' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('A')">Approved</button>

                                    <button type="button" :class="[
                                        'fc-agendaWeek-button fc-button fc-state-default',
                                        activeFlag === 'R' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('R')">Rejected</button>

                                    <button type="button" :class="[
                                        'fc-agendaDay-button fc-button fc-state-default fc-corner-right',
                                        activeFlag === 'PENDING' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('PENDING')">Pending</button>

                                </div>
                            </div>
                        </div>
                        <table class="table center-aligned-table mb-0" id="MapTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>Map</th>
                                    <th>Added On</th>
                                    <th>Added By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(map, index) in MapData" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        <div id="map" class="map" style="width:400px; height:300px;"></div>
                                    </td>
                                    <td>{{ formatDate(map.created_at) }}</td>
                                    <td>{{ map.addedby }}</td>
                                    <td>
                                        <label v-if="map.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="map.flag === 'U'" class="badge bg-primary">
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
                                        <i class="fas fa-trash-alt text-danger" @click="deleteMap(map.id)"></i>
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
import { ref, onMounted, nextTick, reactive } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
// Import OpenLayers modules
import Map from "ol/Map.js";
import View from "ol/View.js";
import TileLayer from "ol/layer/Tile.js";
import OSM from "ol/source/OSM.js";
import { fromLonLat } from "ol/proj.js";
import Feature from "ol/Feature.js";
import Point from "ol/geom/Point.js";
import VectorSource from "ol/source/Vector.js";
import VectorLayer from "ol/layer/Vector.js";
import { Icon, Style, Text, Fill, Stroke } from "ol/style.js";
import Overlay from "ol/Overlay.js";
const MapData = ref();
const toastr = useToastr();
import { useRoute, useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
const selectedPublisher = ref("");
const publisherData = ref([]); // Store publisher data
const filteredMapData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
import Swal from 'sweetalert2';
let mapInstance = null;
// Reactive state
const formData = reactive({
    lat: '',
    lng: '',
    zoom: 14,
    place_name: ''
});

const getMaps = async () => {
    try {
        debugger;
        loading.value = true;
        const response = await axios.get("/get_contactus");
        const data = response.data;
        console.log("Map API response:", data);

        if (Array.isArray(data) && data.length > 0) {
            MapData.value = data;

            const mapItem = data[0]; // first record
            formData.lat = parseFloat(mapItem.lat);
            formData.lng = parseFloat(mapItem.lng);
            formData.zoom = mapItem.zoom ?? 14;
            formData.place_name = mapItem.place_name || "";
            selectedPublisher.value = mapItem.publisher_id || "";

            // Initialize map with the fetched coordinates
            await nextTick();
            initMap(formData.lat, formData.lng, formData.place_name);
        }
    } catch (error) {
        console.error("Error loading map data:", error);
    } finally {
        loading.value = false;
    }
};

const initMap = (lat, lon, placeName) => {
    const map = new Map({
        target: "map",
        layers: [new TileLayer({ source: new OSM() })],
        view: new View({
            center: fromLonLat([lon, lat]),
            zoom: 17,
        }),
    });

    const feature = new Feature({
        geometry: new Point(fromLonLat([lon, lat])),
        name: placeName,
        lon,
        lat,
    });

    feature.setStyle(
        new Style({
            image: new Icon({
                anchor: [0.5, 1],
                src: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
                scale: 0.05,
            }),
            text: new Text({
                text: placeName,
                offsetY: -25,
                font: "14px Calibri,sans-serif",
                fill: new Fill({ color: "#000" }),
                stroke: new Stroke({ color: "#fff", width: 2 }),
            }),
        })
    );

    const vectorLayer = new VectorLayer({
        source: new VectorSource({ features: [feature] }),
    });
    map.addLayer(vectorLayer);
};


// Filter handler
const filterByFlag = async (flag) => {
    activeFlag.value = flag; // Update active button state

    // Destroy existing DataTable
    if ($.fn.dataTable.isDataTable('#MapTable')) {
        $('#MapTable').DataTable().destroy();
    }

    // Filter logic
    if (flag === 'ALL') {
        filteredMapData.value = [...MapData.value];
    } else if (flag === 'PENDING') {
        // Both 'U' (Unapproved) and 'N' (New) are considered pending
        filteredMapData.value = MapData.value.filter(
            item => item.flag === 'U' || item.flag === 'N'
        );
    } else {
        filteredMapData.value = MapData.value.filter(item => item.flag === flag);
    }

    await nextTick();
    initDataTable();

};


const initDataTable = () => {
    // Destroy if already exists
    if ($.fn.dataTable.isDataTable('#latestNewsTable')) {
        $('#latestNewsTable').DataTable().destroy();
    }

    nextTick(() => {
        $('#latestNewsTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
};

const onBack = () => {
    router.push('/contentcreator/pages-form/1/Home/0')
}

const getAllPublisher = async () => {
    try {
        const response = await axios.get('/api/get_allpublisher');
        publisherData.value = response.data.data;
        // Auto-select if only one publisher exists
        if (publisherData.value.length === 1) {
            selectedPublisher.value = publisherData.value[0].id;
        }
    } catch (error) {
        console.error('Error fetching publishers:', error.response || error);
    }
};



const loading = ref(false);
const submitData = async () => {
    try {
        debugger;
        if (!selectedPublisher.value) {
            toastr.error("Please select a publisher.");
            return;
        }
        if (!formData.lat || !formData.lng) {
            toastr.error("Please enter latitude and longitude.");
            return;
        }

        // add publisher_id and route params
        const payload = {
            ...formData,
            publisher_id: selectedPublisher.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id,
        };

        loading.value = true;

        const response = await axios.post('/api/addMapData', payload, {
            headers: { 'Content-Type': 'application/json' },
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

const deleteMap = async (id) => {
    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this logo? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.post('/deleted_map', {
                id: id,
                menu_id: route.params.menuId,
                page_section_master_id: route.params.page_section_id
            });

            setTimeout(getMaps, 200); // refresh the list
            Swal.fire('Deleted!', response.data.message || 'Map has been removed.', 'success');
        } catch (error) {
            console.error('Error deleting map:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};



onMounted(() => {
    getMaps();
    getAllPublisher();
});
</script>
<style scoped>
@import '../assets/css/style.css';

.map {
    width: 500px;
    height: 200px;
    border: 1px solid #ccc;
}
</style>
