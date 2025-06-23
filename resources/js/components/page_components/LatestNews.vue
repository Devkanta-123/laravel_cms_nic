<template>
  <div class="container mt-3">
    <div class="tabs">
      <div class="tab-item" :class="{ active: selectedTab === 'add' }" @click="selectedTab = 'add'">
        Add Latest News
      </div>
      <div class="tab-item" :class="{ active: selectedTab === 'manage' }" @click="selectedTab = 'manage'">
        Manage
      </div>

    </div>

    <div class="tab-content">
      <div v-show="selectedTab === 'add'">
        <LatestNewsAdd  :menu="menu"  :section="section"/>
      </div>
      <div v-show="selectedTab === 'manage'">
        <LatestNewsManage />
      </div>
    </div>

  </div>
</template>





<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import LatestNewsAdd from './LatestNewsAdd.vue';
import LatestNewsManage from './LatestNewsManage.vue';

const selectedTab = ref('add');

const props = defineProps({
  menu: String,
  section: Object
});

</script>

<style scoped>
.tabs {
  display: flex;
  margin-bottom: 1em;
}

.tab-item {
  margin-right: 1em;
  padding: 0.5em 1em;
  cursor: pointer;
  background-color: #eee;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.tab-item.active {
  background-color: #007bff;
  color: white;
}

.tab-item:hover {
  background-color: #ddd;
}

.tab-content {
  border: 1px solid #eee;
  padding: 1em;
}
</style>