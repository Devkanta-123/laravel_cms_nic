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
                          <tr v-for="(activity, index) in visibleFilteredRows" :key="index">
                            <td>
                              <div>
                                 <button v-if="activity.showBack"
                                  @click="toggleFilterByApplicationId(activity.application_id)"
                                  class="btn btn-sm  btn-outline-light text-dark ms-2">
                                  ← Back
                                </button>
                                <label
                                  @click="toggleFilterByApplicationId(activity.application_id)" style="cursor:pointer">
                                  {{ activity.application_id || 'N/A' }}
                                </label>
                                <!-- Back button shown only in the first matching row -->
                               
                              </div>
                            </td>
                            <td>{{ activity.menu_name }}</td>
                            <td>{{ activity.page_section_name }}</td>
                            <td>{{ activity.user_from_name }}</td>
                            <td>{{ activity.user_to_name || 'N/A' }}</td>
                            <td>{{ activity.created_at }}</td>
                            <td>
                              <div>
                                <span>{{ activity.remarks.split(':')[1]?.trim() }}</span><br>
                                <small class="text-muted">{{ activity.remarks.split(':')[0] }}</small>
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

// ✅ Final computed data with showBack flag
const visibleFilteredRows = computed(() => {
  let backButtonShown = false;
  let filtered = [];

  if (selectedId.value) {
    filtered = activityLogData.value.filter(item => item.id == Number(selectedId.value));
  } else if (selectedApplicationId.value) {
    filtered = activityLogData.value
      .filter(item => item.application_id === selectedApplicationId.value)
      .sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
  } else {
    const grouped = {};
    activityLogData.value.forEach(item => {
      const key = item.application_id || 'null';
      if (!grouped[key] || new Date(item.created_at) > new Date(grouped[key].created_at)) {
        grouped[key] = item;
      }
    });
    filtered = Object.values(grouped);
  }

  // Add showBack flag only for first matching row
  return filtered.map((activity) => {
    if (!backButtonShown && selectedApplicationId.value === activity.application_id) {
      backButtonShown = true;
      return { ...activity, showBack: true };
    }
    return { ...activity, showBack: false };
  });
});

// ✅ Fetch activity log data
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

// ✅ Toggle filter by application ID
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

// ✅ Watch route ID change
watch(() => route.query.id, (newId) => {
  selectedId.value = newId;
  getActivityLog();
});

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

// ✅ On mount
onMounted(() => {
  getActivityLog();
});
</script>

<style scoped></style>
