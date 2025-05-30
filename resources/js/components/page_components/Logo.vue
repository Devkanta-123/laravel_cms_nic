<template>
    <div class="default-tab">
        <div class="tabs">
            <div class="tab-item" :class="{ active: selectedTab === 'add' }" @click="selectedTab = 'add'">
                Add Logo
            </div>
            <div class="tab-item" :class="{ active: selectedTab === 'manage' }" @click="selectedTab = 'manage'">
                Manage Logo
            </div>

        </div>
    </div>
    <div class="tab-content">
        <div v-show="selectedTab === 'add'">
            <div class="row">
                <div class="col-md-12 mb-2 mb-md-3 " id="">
                    <div class="card">
                        <div class="top">
                            Drag Logo to Upload (max 5mb)
                        </div>

                        <div class="drag-area" @dragover.prevent="onDragOver" @dragleave.prevent="onDragLeave"
                            @drop.prevent="onDrop">
                            <div class="select">Drag Logo here </div>
                            &nbsp; &nbsp;&nbsp; &nbsp; OR &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;
                            <input type="file" name="file" class="file" ref="fileInput" @change="onFileSelect">
                        </div>

                        <div class="card-body scrollable-container">
                            <div class="image" v-for="(image, index) in images" :key="index">
                                <span class="delete" @click="deleteImage(index)"> &times; </span>
                                <img :src="image.url" :alt="image.name" />
                            </div>
                        </div>
                        <button type="upload" @click="uploadImages">Upload</button>
                        <hr>

                        <div class=" card-body scrollable-container">
                            <div class="image" v-for="(image, index) in slides" :key="index">
                                Uploaded File:
                                <span class="delete" @click="deleteDBImage(image, index)"> &times; </span>
                                <img :src="`/storage/${image.image}`" :alt="image.image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="selectedTab === 'manage'">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="logoTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Image</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Added By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(logo, index) in logoData" :key="index">
                                    <td>
                                        <img class="direct-chat-img" :src="`/storage/${logo.image}`"
                                            @click="openModal(`/storage/${logo.image}`)" style="cursor: pointer;"
                                            alt="logo">
                                    </td>
                                    <td>{{ formatDate(logo.created_at) }}</td>
                                    <td>
                                        <label :class="logo.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ logo.flag === 'A' ? 'Approved' : 'Pending' }}
                                        </label>
                                    </td>
                                    <td>
                                        {{ logo.addedby }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="modal fade" id="viewImage" tabindex="-1" role="dialog" aria-hidden="true"
                            :class="{ show: showModal }" :style="{ display: showModal ? 'block' : 'none' }">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header align-items-start">
                                        <div class="modal-title">
                                            <div class="mb-30">
                                                <div class="blog-box blog-2">
                                                    <img class="img-fluid w-100" :src="modalImage" alt="Modal Image" />
                                                    <div class="blog-info pt-10"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="btn-success btn-sm" @click="closeModal">x</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
const logoData = ref()
const images = ref([]);
const isDragging = ref(false);
const fileInput = ref(null);
const slides = ref([]);
const toastr = useToastr();
const showModal = ref(false);
const modalImage = ref('');
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const selectedTab = ref('add') // Default to 'add' tab
const closeModal = () => {
    showModal.value = false;
};
const props = defineProps({
    menu: String
});
const selectFile = () => {
    fileInput.value.click();
}

const onFileSelect = (event) => {
    const files = event.target.files;
    if (files.length === 0) return;
    for (let i = 0; i < files.length; i++) {
        if (files[i].type.split('/')[0] !== 'image') continue;
        if (!images.value.some((e) => e.name === files[i].name)) {
            images.value.push({
                name: files[i].name,
                file: files[i],
                url: URL.createObjectURL(files[i]),
            });
        }
    }
};

const deleteImage = (index) => {
    images.value.splice(index, 1);
}

const onDragOver = (e) => {
    isDragging.value = true;
    e.dataTransfer.dropEffect = 'copy';
};

const onDragLeave = () => {
    isDragging.value = false;
};

const onDrop = (e) => {
    isDragging.value = false;
    const files = e.dataTransfer.files;
    if (files.length === 0) return;
    for (let i = 0; i < files.length; i++) {
        if (files[i].type.split('/')[0] !== 'image') continue;
        if (!images.value.some((e) => e.name === files[i].name)) {
            images.value.push({
                name: files[i].name,
                file: files[i],
                url: URL.createObjectURL(files[i]),
            });
        }
    }
};


const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const uploadImages = () => {
    
    const formData = new FormData();
    formData.append("menu", props.menu);
    images.value.forEach((image) => {
        formData.append('images[]', image.file, image.name);
    });
    formData.append('menu_id', props.menu);

    axios.post('/api/upload_logo', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            fetchLogo();
            toastr.success('Logo uploaded successfully');
            images.value = [];
            fileInput = ref(null);
        })
        .catch(error => {
            console.error('Error uploading Banner:', error);
            toastr.error(error);

        });
};

const deleteDBImage = (slide, index) => {
    axios.post('/api/delete_banner', { id: slide.id })
        .then(response => {
            console.log('Banner deleted successfully:', response.data);
            toastr.success('Banner deleted successfully');
            slides.value.splice(index, 1);
        })
        .catch(error => {
            console.error('Error deleting Banner:', error);
        });
};

const fetchLogo = async () => {
    try {
        const response = await axios.get('/get_logo');
        logoData.value = response.data;
        await nextTick(); // Wait for DOM to update

        if ($.fn.dataTable.isDataTable('#logoTable')) {
            $('#logoTable').DataTable().destroy();
        }
        $('#logoTable').DataTable({
            responsive: true,
            pageLength: 5,
        });
    } catch (error) {
        console.error('Failed to fetch banner:', error);
    }
};

onMounted(() => {
    fetchLogo();
});

</script>

<style scoped>
.card {
    width: 100%;
    padding: 10px;
    box-shadow: 0 0 5px #ffdfdf;
    border-radius: 5px;
    overflow: hidden;
}

.card .top {
    text-align: center;
}

.card p {
    font-weight: bold;
    color: grey;
}

.card button {
    outline: 0;
    border: 0;
    color: white;
    border-radius: 4px;
    font-weight: 400;
    padding: 8px 13px;
    width: 100%;
    background: grey;
}

.card .drag-area {
    height: 150px;
    border-radius: 5px;
    border: 2px dashed grey;
    background: white;
    color: red;
    display: flex;
    justify-content: center;
    user-select: center;
    --webkit-user-select: none;
    margin-top: 10px;
}

.card .drag-area .visible {
    font-size: 18px;
}

.card .drag-area .select {
    color: blue;
    margin-left: 5px;
    cursor: pointer;
    transition: 0.4s;
}

.card .drag-area .select:hover {
    opacity: 0.6;
}

/* .card .container{
      width: 100%;
      height: auto;
      display: flex;
      justify-content: flex-start;
      align-items: flex-start;
      max-height: 200px;
      position: relative;
      margin-bottom: 8px;
      padding-top: 15px;
  } */
.card .scrollable-container {
    width: 100%;
    height: auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    align-items: flex-start;
    max-height: 200px;
    position: relative;
    margin-bottom: 8px;
    padding-top: 15px;
}

.scrollable-container {
    width: 100%;
    height: 200px;
    overflow-y: auto;
    /* Make the container scrollable */
    margin-bottom: 10px;
}

.card .scrollable-container .image {
    position: relative;
    margin: 5px;
}

.card .scrollable-container .image img {
    width: 100px;
    /* Set the width of the image */
    height: auto;
    /* Maintain the aspect ratio */
    border-radius: 5px;
}

.card .scrollable-container .image .delete {
    position: absolute;
    top: -2px;
    right: 9px;
    font-size: 20px;
    cursor: pointer;
    color: red;
}

.separator {
    width: 100%;
    border: 1px solid #ccc;
    margin: 10px 0;
}

/* .card .container .image img{
      position: absolute;
      top: -2px;
      right: 9px;
      font-size: 20px;
      cursor: pointer;
   } */
</style>