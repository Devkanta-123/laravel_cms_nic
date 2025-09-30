<template>
  <div class="col-lg-12">
    <div
      id="map"
      class="ccmap"
    ></div>
  </div>
  <br>
  <br>
  <FeedBack />
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
import axios from 'axios';
import FeedBack from "../components/FeedBack.vue";

// ✅ OpenLayers imports
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

const mapInstance = ref(null);
const vectorLayer = ref(null);
const mapInitialized = ref(false);
const initializationInProgress = ref(false); // ✅ Prevent concurrent initializations

const props = defineProps({
  data: Object
});

// ✅ Watch for props changes safely
watch(() => props.data, (newData) => {
  if (newData && mapInitialized.value) {
    updateMapFromProps(newData);
  }
}, { deep: true });

const updateMapFromProps = (data) => {
  if (!data) return;
  
  const lat = parseFloat(data.lat);
  const lon = parseFloat(data.lng);
  const zoom = data.zoom ? parseInt(data.zoom) : 14;
  const placeName = data.place_name;

  if (!isNaN(lat) && !isNaN(lon)) {
    updateMapView(lon, lat, placeName, zoom);
  }
};

// ✅ Initialize map only once
const initMap = (lat, lon, placeName, zoom = 17) => {
  // ✅ Double-check initialization state
  if (mapInitialized.value || initializationInProgress.value) {
    console.log('Map initialization already in progress or completed');
    return;
  }

  initializationInProgress.value = true;

  try {
    // ✅ Ensure map target element exists and is empty
    const mapElement = document.getElementById('map');
    if (!mapElement) {
      console.error('Map element not found');
      return;
    }

    // ✅ Clear any existing content
    mapElement.innerHTML = '';

    mapInstance.value = new Map({
      target: "map",
      layers: [new TileLayer({ source: new OSM() })],
      view: new View({
        center: fromLonLat([lon, lat]),
        zoom: zoom,
      }),
    });

    vectorLayer.value = new VectorLayer({
      source: new VectorSource(),
    });
    mapInstance.value.addLayer(vectorLayer.value);

    mapInitialized.value = true;
    
    addMarker(lon, lat, placeName);
    
    console.log('Map initialized successfully');
  } catch (error) {
    console.error('Error initializing map:', error);
    initializationInProgress.value = false;
  } finally {
    initializationInProgress.value = false;
  }
};

// ✅ Update existing map view
const updateMapView = (lon, lat, placeName, zoom = 17) => {
  if (mapInstance.value && mapInitialized.value) {
    mapInstance.value.getView().setCenter(fromLonLat([lon, lat]));
    mapInstance.value.getView().setZoom(zoom);

    if (vectorLayer.value) {
      vectorLayer.value.getSource().clear();
      addMarker(lon, lat, placeName);
    }
  }
};

// ✅ Add marker to map
const addMarker = (lon, lat, placeName) => {
  if (!vectorLayer.value || !mapInitialized.value) return;

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

  vectorLayer.value.getSource().addFeature(feature);
};

// ✅ Fetch contact data
const fetchContactUs = async () => {
  if (mapInitialized.value || initializationInProgress.value) {
    return; // ✅ Prevent duplicate calls
  }

  try {
    const response = await axios.get("/get_contactus", { params: { flag: 'A' } });
    if (response.data[0]) {
      console.log("ContactData", response.data[0]);

      const lat = parseFloat(response.data[0].lat);
      const lon = parseFloat(response.data[0].lng);
      const zoom = response.data[0].zoom ? parseInt(response.data[0].zoom) : 14;
      const placeName = response.data[0].place_name;

      if (!isNaN(lat) && !isNaN(lon)) {
        // ✅ Use setTimeout to ensure DOM is ready without nextTick issues
        setTimeout(() => {
          initMap(lat, lon, placeName, zoom);
        }, 100);
      }
    }
  } catch (error) {
    console.error("Failed to fetch contact details:", error);
  }
};

// ✅ Run on mount - with proper cleanup
onMounted(() => {
  if (!mapInitialized.value && !initializationInProgress.value) {
    fetchContactUs();
  }
});

// ✅ Proper cleanup
onBeforeUnmount(() => {
  if (mapInstance.value) {
    mapInstance.value.setTarget(null);
    mapInstance.value.dispose();
    mapInstance.value = null;
  }
  vectorLayer.value = null;
  mapInitialized.value = false;
  initializationInProgress.value = false;
});
</script>

<style scoped>

</style>