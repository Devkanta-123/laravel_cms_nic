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
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="0.0000001" class="form-control" v-model="formData.lat"
                            placeholder="Enter Latitude" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="0.0000001" class="form-control" v-model="formData.lng"
                            placeholder="Enter Longitude" />
                    </div>

                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label class="form-label">Zoom</label>
                        <input type="number" min="1" max="20" class="form-control" v-model="formData.zoom"
                            placeholder="Enter Zoom (default 14)" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Place Name</label>
                        <input type="text" class="form-control" v-model="formData.place_name"
                            placeholder="Enter Place Name" />
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
                                        <div id="map" class="map" style="width:400px; height:300px;"></div>
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
import { ref, nextTick, onMounted, reactive } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
const route = useRoute();
import { useToastr } from '../../toaster.js';
const selectedTab = ref('add') // Default to 'add' tab
const toastr = useToastr();
const MapData = ref();
const props = defineProps({
    menu: String,
    section: Number,
});
const menu_id = props.menu;
const page_section_master_id = props.section.page_section_id;
// Reactive state
const formData = reactive({
    lat: '',
    lng: '',
    zoom: 14,
    place_name: ''
});

// Submit form data to the backend
const loading = ref(false);
const submitData = async () => {
    try {
        debugger;

        if (!formData.lat || !formData.lng) {
            toastr.error("Please enter latitude and longitude.");
            return;
        }

        // add publisher_id and route params
        const payload = {
            ...formData,
            menu_id: route.params.menuId,
            page_section_master_id: props.section.page_section_id,
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

const getMaps = async () => {
    try {
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
