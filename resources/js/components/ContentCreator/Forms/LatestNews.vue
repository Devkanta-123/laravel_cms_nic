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
                    <h5 class="card-title">Latest News</h5>
                    <div id="example-basic" role="application" class="wizard clearfix">
                        <div class="content clearfix">
                            <div class="row">
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="englishTitle">English Title <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="englishTitle" class="form-control" v-model="title"
                                        placeholder="English Title" required>
                                </div>
                                 <div class="col-4">
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

                            <div class="row">
                                 <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <div class="form-group mb-3">
                                        <div class="checkbox checbox-switch switch-primary">
                                            <label>
                                                <input type="checkbox" v-model="showLinkInput">
                                                <span></span> Is URL?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Checkbox Switch -->
                               <!-- File Upload (Shown only when showLinkInput is false) -->
                                <div v-show="!showLinkInput" class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="fileInput">File Upload</label>
                                    <input type="file" id="fileInput" class="form-control" @change="handleFileChange"
                                        accept=".pdf">
                                </div>

                                <!-- Link Input (Shown only when showLinkInput is true) -->
                                <div v-show="showLinkInput" class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="linkInput">URL</label>
                                    <input type="text" id="linkInput" class="form-control" v-model="link"
                                        placeholder="Enter URL" autocomplete="off">
                                </div>
                               
                            </div>
                          
                               
                        </div>

                        <!-- Save Button -->
                        <div class="actions clearfix mt-3">
                            <ul role="menu" aria-label="Pagination">
                                <li>
                                    <button type="button" class="btn btn-success" role="menuitem"
                                        @click="saveLatestNews">
                                        Save
                                    </button>
                                </li>
                            </ul>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">List </h5>
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
                        <table class="table center-aligned-table mb-0" id="latestNewsTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>Title</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(news, index) in filteredLatestNewsData" :key="index">
                                    <td>{{ index + 1}}</td>
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
                                    <td>
                                        <label v-if="news.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="news.flag === 'U'" class="badge bg-warning">
                                            Pending
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
                                    <!-- <td>
                                        <i class="fas fa-trash-alt text-danger" v-if="news.flag !== 'A'"
                                            @click="deleteLatestNews(news.id)"></i>
                                    </td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
const selectedPublisher = ref("");
const publisherData = ref([]); // Store publisher categories
import { useRoute,useRouter } from 'vue-router';
import Swal from 'sweetalert2';
const route = useRoute();
const router = useRouter();
const showLinkInput = ref(false)
const file = ref(null)
const link = ref('')
const title = ref('')
const latestnews = ref([]);
const filteredLatestNewsData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'

// Handle file change
const handleFileChange = (e) => {
    file.value = e.target.files[0]
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
        toastr.error("Failed to load publishers.");
    }
};
const onBack = () => {
    router.push('/contentcreator/pages-form/1/Home/0')
}
// Filter handler
const filterByFlag = async (flag) => {
    activeFlag.value = flag; // Update active button state

    // Destroy existing DataTable
    if ($.fn.dataTable.isDataTable('#latestNewsTable')) {
        $('#latestNewsTable').DataTable().destroy();
    }

    // Filter logic
    if (flag === 'ALL') {
        filteredLatestNewsData.value = [...latestnews.value];
    } else if (flag === 'PENDING') {
        // Both 'U' (Unapproved) and 'N' (New) are considered pending
        filteredLatestNewsData.value = latestnews.value.filter(
            item => item.flag === 'U' || item.flag === 'N'
        );
    } else {
        filteredLatestNewsData.value = latestnews.value.filter(item => item.flag === flag);
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
    if (!selectedPublisher.value) {
        toastr.error("Please select a publisher.");
        return false;
    }

    return true
}



const deleteLatestNews = async (id) => {
    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this news? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.post('/api/delete_news', {
                id: id,
                menu_id: route.params.menuId,
                page_section_master_id: route.params.page_section_id
            });
            await nextTick();
            await fetchLatestNews(); Swal.fire('Deleted!', response.data.message || 'News has been deleted.', 'success');
        } catch (error) {
            console.error('Error deleting notice:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};

// Save function
const saveLatestNews = () => {
    if (!validateInputs()) {
        toastr.error('Please fill the required fields.');
        return;
    }

    const formData = new FormData();
    if (showLinkInput.value) {
        formData.append('link', link.value);
    } else {
        formData.append('file', file.value);
    }
    formData.append('title', title.value);
    formData.append("menu_id", route.params.menuId);
    formData.append("page_section_master_id", route.params.page_section_id);
    formData.append("publisher_id", selectedPublisher.value);
    axios.post('/api/save_latest_news', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then(async (response) => {
            toastr.success(response.data.message || 'News saved successfully.');

            // Step 1: Destroy existing DataTable
            if ($.fn.dataTable.isDataTable('#latestNewsTable')) {
                $('#latestNewsTable').DataTable().destroy();
            }

            // Step 2: Clear old news to force re-render
            latestnews.value = [];

            // Step 3: Wait for DOM to update
            await nextTick();

            // Step 4: Fetch new data
            await fetchLatestNews();
            title.value = '';
            link.value = '';
            file.value = null;
            showLinkInput.value = false; // or true, based on your toggle
        })
        .catch((error) => {
            console.error('Error saving news:', error);
            toastr.error('An error occurred while saving the news.');
        });
};

const fetchLatestNews = async () => {
    try {
        const response = await axios.post('/api/get_latest_news');
        latestnews.value = response.data;
        filteredLatestNewsData.value =response.data;
        await nextTick(); // Wait for DOM update
         initDataTable();
        console.log('Latest News data:', response.data);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};



onMounted(() => {
    fetchLatestNews();
    getAllPublisher();
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
