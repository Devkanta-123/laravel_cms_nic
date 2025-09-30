<template>
  <div class="footer__newsletter-three mb-3">
    <form @submit.prevent>
      <input type="text" v-model="searchQuery" class="form-control" placeholder="Search ...." />
    </form>
  </div>
  <br /><br />

  <section class="team__area-four">
    <div class="container" style="margin-top: -160px;">
      <div v-if="filteredData.length === 0">
        <p>No data available.</p>
      </div>

      <template v-else>
        <!-- State Level -->
        <div v-if="stateLevel.length > 0" class="position-relative">
          <h4 class="title contactus">State Level</h4>

          <!-- Cards Row -->
          <div class="row justify-content-center">
            <WhosWhoCard
              v-for="person in paginatedStateLevel"
              :key="person.id"
              :person="person"
            />
          </div>

          <!-- Navigation Arrows -->
          <div class="project__nav-wrap">
            <div
              class="project-button-prev"
              tabindex="0"
              role="button"
              aria-label="Previous slide"
              @click="changeStatePage(statePage - 1)"
              :class="{ disabled: statePage === 1 }"
            >
              <i class="fa fa-chevron-right"></i>
            </div>
            <div
              class="project-button-next"
              tabindex="0"
              role="button"
              aria-label="Next slide"
              @click="changeStatePage(statePage + 1)"
              :class="{ disabled: statePage === statePages }"
            >
              <i class="fa fa-chevron-right"></i>
            </div>
          </div>
        </div>

        <br />

        <!-- District Level -->
        <div v-if="districtLevel.length > 0" class="position-relative">
          <h4 class="title contactus">District Level</h4>

          <!-- Cards Row -->
          <div class="row justify-content-center">
            <WhosWhoCard
              v-for="person in paginatedDistrictLevel"
              :key="person.id"
              :person="person"
            />
          </div>

          <!-- Navigation Arrows -->
          <div class="project__nav-wrap">
            <div
              class="project-button-prev"
              tabindex="0"
              role="button"
              aria-label="Previous slide"
              @click="changeDistrictPage(districtPage - 1)"
              :class="{ disabled: districtPage === 1 }"
            >
              <i class="fa fa-chevron-right"></i>
            </div>
            <div
              class="project-button-next"
              tabindex="0"
              role="button"
              aria-label="Next slide"
              @click="changeDistrictPage(districtPage + 1)"
              :class="{ disabled: districtPage === districtPages }"
            >
              <i class="fa fa-chevron-right"></i>
            </div>
          </div>
        </div>

        <br />

        <!-- Block Level -->
        <div v-if="blockLevel.length > 0" class="position-relative">
          <h4 class="title contactus">Block Level</h4>

          <!-- Cards Row -->
          <div class="row justify-content-center">
            <WhosWhoCard
              v-for="person in paginatedBlockLevel"
              :key="person.id"
              :person="person"
            />
          </div>

          <!-- Navigation Arrows -->
          <div class="project__nav-wrap">
            <div
              class="project-button-prev"
              tabindex="0"
              role="button"
              aria-label="Previous slide"
              @click="changeBlockPage(blockPage - 1)"
              :class="{ disabled: blockPage === 1 }"
            >
              <i class="fa fa-chevron-right"></i>
            </div>
            <div
              class="project-button-next"
              tabindex="0"
              role="button"
              aria-label="Next slide"
              @click="changeBlockPage(blockPage + 1)"
              :class="{ disabled: blockPage === blockPages }"
            >
              <i class="fa fa-chevron-right"></i>
            </div>
          </div>
        </div>
      </template>
    </div>
  </section>
</template>


<script setup>
import { ref, watch, computed } from "vue";
import WhosWhoCard from "../components/WhosWhoCard.vue";

const props = defineProps({
  data: {
    type: Array,
    default: () => []
  }
});

const whoswhoData = ref([]);
const searchQuery = ref("");

// ✅ filteredData based on search
const filteredData = computed(() => {
  if (!searchQuery.value) return whoswhoData.value;

  const q = searchQuery.value.toLowerCase();
  return whoswhoData.value.filter(
    (p) =>
      p.name?.toLowerCase().includes(q) ||
      p.designation?.toLowerCase().includes(q) ||
      p.district_name?.toLowerCase().includes(q) ||
      p.block_name?.toLowerCase().includes(q)
  );
});

const stateLevel = ref([]);
const districtLevel = ref([]);
const blockLevel = ref([]);

const itemsPerPage = 3;

// separate page refs
const statePage = ref(1);
const districtPage = ref(1);
const blockPage = ref(1);

const categorizeData = () => {
  stateLevel.value = filteredData.value.filter((p) => p.level_id === 1);
  districtLevel.value = filteredData.value.filter((p) => p.level_id === 2);
  blockLevel.value = filteredData.value.filter((p) => p.level_id === 3);
};

// Watch data from parent
watch(
  () => props.data,
  (newVal) => {
    whoswhoData.value = newVal || [];
    categorizeData();
  },
  { immediate: true }
);

// Watch search query → reset pagination + recategorize
watch(searchQuery, () => {
  statePage.value = 1;
  districtPage.value = 1;
  blockPage.value = 1;
  categorizeData();
});

// total pages
const statePages = computed(() =>
  Math.ceil(stateLevel.value.length / itemsPerPage)
);
const districtPages = computed(() =>
  Math.ceil(districtLevel.value.length / itemsPerPage)
);
const blockPages = computed(() =>
  Math.ceil(blockLevel.value.length / itemsPerPage)
);

// paginate
const paginatedStateLevel = computed(() => {
  const start = (statePage.value - 1) * itemsPerPage;
  return stateLevel.value.slice(start, start + itemsPerPage);
});
const paginatedDistrictLevel = computed(() => {
  const start = (districtPage.value - 1) * itemsPerPage;
  return districtLevel.value.slice(start, start + itemsPerPage);
});
const paginatedBlockLevel = computed(() => {
  const start = (blockPage.value - 1) * itemsPerPage;
  return blockLevel.value.slice(start, start + itemsPerPage);
});

// handlers
const changeStatePage = (page) => {
  if (page >= 1 && page <= statePages.value) statePage.value = page;
};
const changeDistrictPage = (page) => {
  if (page >= 1 && page <= districtPages.value) districtPage.value = page;
};
const changeBlockPage = (page) => {
  if (page >= 1 && page <= blockPages.value) blockPage.value = page;
};
</script>
