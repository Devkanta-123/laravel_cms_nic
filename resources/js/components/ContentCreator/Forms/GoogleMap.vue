<script setup>
import { defineProps, defineEmits } from "vue"

const props = defineProps({
  lat: { type: Number, required: true },
  lng: { type: Number, required: true },
  zoom: { type: Number, default: 14 },
  placeName: { type: String, default: "" }
})

// we emit lat/lng when user clicks on the map
const emit = defineEmits(["map-click", "center-changed"])

function handleClick(event) {
  const clickedLat = event.latLng.lat()
  const clickedLng = event.latLng.lng()
  emit("map-click", { lat: clickedLat, lng: clickedLng })
}

function handleCenterChanged(map) {
  const center = map.getCenter()
  emit("center-changed", { lat: center.lat(), lng: center.lng() })
}
</script>

<template>
  <GMapMap
    :center="{ lat, lng }"
    :zoom="zoom"
    style="width: 300px; height: 200px; border-radius: 10px; overflow: hidden; cursor: pointer;"
    @click="handleClick"
    @center_changed="handleCenterChanged"
  >
    <GMapMarker :position="{ lat, lng }" :title="placeName" />
  </GMapMap>
</template>
