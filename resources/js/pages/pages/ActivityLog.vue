<template>
<br>       
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Activity Log</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <table class="table center-aligned-table mb-0" id="activityLogTable">
                                                <thead>
                                                    <tr class="text-dark">
                                                        <th>ApplicationID</th>
                                                        <th>Menu</th>
                                                        <th>PageSection</th>
                                                        <th>FromUser</th>
                                                        <th>ToUser</th>
                                                        <th>EntryDateTime</th>
                                                        <th>Remarks</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(activity, index) in filteredActivityLog" :key="index">
                                                        <td>
                                                            <label class="badge bg-primary"
                                                                @click="toggleFilterByApplicationId(activity.application_id)"
                                                                style="cursor:pointer">
                                                                {{ activity.application_id || 'N/A' }}
                                                            </label>
                                                        </td>
                                                        <td>{{ activity.menu_name }}</td>
                                                        <td>{{ activity.page_section_name }}</td>
                                                        <td>{{ activity.user_from_name }}</td>
                                                        <td>{{ activity.user_to_name || 'N/A' }}</td>
                                                        <td>{{ activity.created_at }}</td>
                                                        <td>
                                                            <div>
                                                                <span>{{ activity.remarks.split(':')[1]?.trim()
                                                                    }}</span><br>
                                                                <small class="text-muted">{{
                                                                    activity.remarks.split(':')[0] }}</small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <label :class="{
                                                                'badge bg-info': activity.action === 'Add',
                                                                'badge bg-primary': activity.action === 'Updated',
                                                                'badge bg-danger': activity.action === 'Rejected',
                                                                'badge bg-success': activity.action === 'Approved',
                                                                'badge bg-secondary': activity.action === 'Deleted',
                                                                'badge bg-warning': !['Add', 'Updated', 'Rejected', 'New', 'Approved', 'Deleted'].includes(activity.action)
                                                            }">
                                                                {{ formatActionLabel(activity.action) }}
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, nextTick, computed, watch } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import { useToastr } from '../../toaster.js';

const route = useRoute();
const toastr = useToastr();

const activityLogData = ref([]);
const selectedApplicationId = ref(null);
const selectedId = ref(route.query.id ?? null);

// ✅ Computed filter based on selectedId → selectedApplicationId → default
const filteredActivityLog = computed(() => {
  if (selectedId.value) {
    return activityLogData.value.filter(item => item.id == Number(selectedId.value));
  }

  if (selectedApplicationId.value) {
    return activityLogData.value
      .filter(item => item.application_id === selectedApplicationId.value)
      .sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
  }

  const grouped = {};
  activityLogData.value.forEach(item => {
    const key = item.application_id || 'null';
    if (!grouped[key] || new Date(item.created_at) > new Date(grouped[key].created_at)) {
      grouped[key] = item;
    }
  });

  return Object.values(grouped);
});

// ✅ Fetch data
const getActivityLog = async () => {
  try {
    const response = await axios.get('/api/get_archivitylog');
    if (response.data && response.data.status === 'success') {
      activityLogData.value = response.data.data;

      console.log("Selected ID:", selectedId.value);
      console.log("WholeData:", response.data.data);

      nextTick(() => {
        if ($.fn.DataTable.isDataTable('#activityLogTable')) {
          $('#activityLogTable').DataTable().destroy();
        }

        $('#activityLogTable').DataTable({
          responsive: true,
          pageLength: 10,
          order: [[5, 'asc']]
        });
      });
    }
  } catch (error) {
    toastr.error('Failed to fetch activity log.');
  }
};

// ✅ Filter toggle
const toggleFilterByApplicationId = (appId) => {
  if ($.fn.DataTable.isDataTable('#activityLogTable')) {
    $('#activityLogTable').DataTable().destroy();
  }

  if (selectedApplicationId.value === appId) {
    selectedApplicationId.value = null;
    $('#activityLogCollapse').collapse('hide');
  } else {
    selectedApplicationId.value = appId;
    $('#activityLogCollapse').collapse('show');
  }

  nextTick(() => {
    $('#activityLogTable').DataTable({
      responsive: true,
      pageLength: 10,
      order: [[5, 'asc']]
    });
  });
};

// ✅ Watch query param changes
watch(() => route.query.id, (newId) => {
  selectedId.value = newId;
  getActivityLog(); // optional — only if needed when route param changes
});

// ✅ Date formatter
const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleString('en-IN', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  });
};

// ✅ Action label formatter
const formatActionLabel = (action) => {
  switch (action) {
    case 'Add': return 'NewEntry';
    case 'Updated': return 'Updated';
    case 'Rejected': return 'Rejected';
    case 'Approved': return 'Approved';
    case 'Deleted': return 'Deleted';
    case 'New': return 'New';
    default: return 'Pending';
  }
};

// ✅ Initial load
onMounted(() => {
  getActivityLog();
});
</script>

<style scoped></style>
