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
                                    <th>Added By</th>
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
                                        <label :class="news.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ news.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                        
                                    </td>
                                    <td>
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(news.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                        <label>
                                            <input type="checkbox" :checked="news.flag === 'A'"
                                                :disabled="news.flag === 'A'" @change="approveLatestNews(news.id, index)">
                                            <span></span>
                                        </label>
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
// Handle file change
const handleFileChange = (e) => {
    file.value = e.target.files[0]
}

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



const approveLatestNews = async (id, index) => {
    try {
        debugger;
        const response = await axios.post('/approved_latestnews', { id });
        if (response.data.success) {
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
