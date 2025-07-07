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
                    <h3 class="card-title">Audit Logs</h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <table c class="table center-aligned-table mb-0" id="auditlogDataTable">
                        <thead>
                          <tr class="text-dark">
                            <th>SL.NO</th>
                            <th>IP_Address</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>UserAgent</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(activity, index) in auditlogData" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ activity.ip_address }}</td>
                            <td>
                              <label v-if="activity.action === 'login' && activity.status === 'success'"
                                class="badge bg-success">
                                {{ activity.action }}
                              </label>
                              <label v-else-if="activity.action === 'login' && activity.status === 'failed'"
                                class="badge bg-danger">
                                {{ activity.action }}
                              </label>
                              <label v-else class="badge bg-secondary">
                                {{ activity.action }}
                              </label>
                            </td>
                            <td>{{ activity.status }}</td>
                            <td>{{ activity.user_agent || 'N/A' }}</td>
                            <td>{{ formatDate(activity.created_at) }}</td>
                            <!-- <td>
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
                            </td> -->
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
const auditlogData = ref([]);

// ✅ Fetch activity log data
const getAuditLogs = async () => {
  try {
    const response = await axios.get('/api/get_auditlogs');
    if (response.data && response.data.status === 'success') {
      auditlogData.value = response.data.data;

      nextTick(() => {
        if ($.fn.DataTable.isDataTable('#auditlogDataTable')) {
          $('#auditlogDataTable').DataTable().destroy();
        }

        $('#auditlogDataTable').DataTable({
          responsive: true,
          pageLength: 10
        });
      });
    }
  } catch (error) {
    toastr.error('Failed to fetch audit log.');
  }
};
function formatDate(dateStr) {
  const date = new Date(dateStr);

  const options = {
    day: '2-digit',
    month: 'short', // e.g., "Jul"
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  };

  return date.toLocaleString('en-US', options);
}


// ✅ On mount
onMounted(() => {
  getAuditLogs();
});
</script>

<style scoped></style>
