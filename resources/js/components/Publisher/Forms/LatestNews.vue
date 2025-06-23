<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">List </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="latestNewsTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Title</th>
                                    <th>Added On</th>
                                    <th>AddedBy</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(news, index) in latestnews" :key="index">
                                    <td>
                                        <a v-if="news.type === 'file'" :href="`/storage/${news.file}`" target="_blank"
                                            class="text-primary">
                                            {{ news.title }}
                                        </a>
                                        <a v-else-if="news.type === 'link'" :href="news.link" target="_blank"
                                            class="text-primary">
                                            {{ news.title }}
                                        </a>
                                        <span v-else>{{ news.title }}</span>
                                    </td>
                                    <td>{{ formatDate(news.addedon) }}</td>
                                    <td>{{ news.addedby }}</td>
                                    <td>
                                        <label v-if="news.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="news.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>
                                        <div v-else-if="news.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ news.rejected_remarks }}
                                            </div>
                                        </div>
                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>

                                    </td>
                                    <td>
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(news.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="news.flag === 'A'"
                                                    :disabled="news.flag === 'A'"
                                                    @change="approveLatestNews(news.id, index)">
                                                <span></span>
                                            </label>
                                            <i class="fas fa-pencil-alt text-success" data-toggle="modal"
                                                data-target="#editModal" @click="editModal(news)">
                                            </i>
                                            &nbsp;
                                            <!-- Show icon only if flag is not 'A' -->
                                            <i class="fas fa-times text-danger"
                                                v-if="news.flag !== 'A' && news.flag !== 'R'" data-toggle="modal"
                                                data-target="#rejectedModal" @click="rejectedModal(news)"></i>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const showLinkInput = ref(false)
const file = ref(null)
const link = ref('')
const title = ref('')
const titleK = ref('')
const titleH = ref('')
const latestnews = ref([]);
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedLatestNew = ref({}) // To store the clicked notice
import { useRoute } from 'vue-router';
const route = useRoute();
// Handle file change
const handleFileChange = (e) => {
    file.value = e.target.files[0]
}
const rejectedModal = (news) => {
    debugger;
    selectedLatestNew.value = news;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
// Simple validation
const validateInputs = () => {
    if (!title.value.trim()) return false
    if (showLinkInput.value && !link.value.trim()) return false
    if (!showLinkInput.value && !file.value) return false
    return true
}

// Save function
const saveLatestNews = () => {
    if (!validateInputs()) {
        toastr.error('Please fill the required fields.')
        return
    }
    const formData = new FormData()
    if (showLinkInput.value) {
        formData.append('link', link.value)
    } else {
        formData.append('file', file.value)
    }

    formData.append('title', title.value)
    formData.append('titleK', titleK.value)
    formData.append('titleH', titleH.value)

    axios.post('/api/save_latest_news', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then((response) => {
            toastr.success(response.data.message || 'News saved successfully.')
        })
        .catch((error) => {
            console.error('Error saving news:', error)
            toastr.error('An error occurred while saving the news.')
        })
}
const fetchLatestNews = async () => {
    try {
        const response = await axios.post('/api/get_latest_news', { flag: 4 });
        latestnews.value = response.data;
        console.log(response.data);

        await nextTick(); // Wait for DOM to update

        // Destroy and reinitialize DataTable
        if ($.fn.dataTable.isDataTable('#latestNewsTable')) {
            $('#latestNewsTable').DataTable().destroy();
        }
        $('#latestNewsTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const rejected = async () => {
    if (!rejectedRemarks.value.trim()) {
        rejectedRemarksError.value = true;
        return;
    }
    rejectedRemarksError.value = false;
    try {
        const response = await axios.post('/rejected_latestNews', {
            id: selectedLatestNew.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response.data.success) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
           fetchLatestNews();
            toastr.success('Latest News Rejected');
        } else {
            toastr.error('Failed to reject the  Latest News');

        }
    } catch (error) {
        console.error(error);
        toastr.error('Something went wrong');
    }
};

const approveLatestNews = async (id, index) => {
    try {
        const response = await axios.post('/approved_latestnews', { id ,menu_id: route.params.menuId, page_section_master_id: route.params.page_section_id});
        if (response) {
            latestnews.value[index].flag = 'A'; // update UI immediately
            fetchLatestNews();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


onMounted(() => {
    fetchLatestNews()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
