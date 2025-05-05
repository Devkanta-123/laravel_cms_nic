<template>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/Kfw5nqKx1pG2eU6R7tZLz1kcfk5iZ3Vf0dUSbNjs2a2g/Og" crossorigin="anonymous">

    <br>

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
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="khasiTitle">Khasi Title</label>
                                    <input type="text" id="khasiTitle" class="form-control" v-model="titleK"
                                        placeholder="Khasi Title">
                                </div>
                                <div class="col-sm-4 col-xl-4 col-xxl-4 mb-4">
                                    <label class="form-label" for="hindiTitle">Hindi Title</label>
                                    <input type="text" id="hindiTitle" class="form-control" v-model="titleH"
                                        placeholder="Hindi Title">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Checkbox Switch -->
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
                                </div>
                                <div class="row">
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
                        <table class="table center-aligned-table mb-0" id="slidesTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Image</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(slide, index) in slides" :key="index">
                                    <td>
                                        <img class="img-fluid avatar-small" :src="`/storage/${slide.image}`"
                                            alt="Slide Image">
                                    </td>
                                    <td>{{ formatDate(slide.created_at) }}</td>
                                    <td>
                                        <label :class="slide.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ slide.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>

                                        <i class="fas fa-trash-alt text-danger" @click="deleteSlide(slide.id)"></i>
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
import { ref ,onMounted } from 'vue'
import axios from 'axios'
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();

const showLinkInput = ref(false)
const file = ref(null)
const link = ref('')
const title = ref('')
const titleK = ref('')
const titleH = ref('')
const latestnewsdata = ref('');
// Handle file change
const handleFileChange = (e) => {
    file.value = e.target.files[0]
}

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
        toastr.error('Please correct the errors before submitting.')
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
    debugger;
  try {
    const response = await axios.post('/api/get_latest_news'); 
    latestnewsdata.value = response.data;
    console.log("Latest News data" , response.data)
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};


onMounted(() => {
    fetchLatestNews()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
