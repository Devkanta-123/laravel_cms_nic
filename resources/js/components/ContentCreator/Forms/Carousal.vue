<template>
    <br>
    <div class="col-xl-6 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <h5 class="card-title">Carousel</h5>
                <div id="example-basic" role="application" class="wizard clearfix">
                    <div class="content clearfix">
                        <section id="example-basic-p-0" role="tabpanel" aria-labelledby="example-basic-h-0"
                            class="body current" aria-hidden="false">
                            <h4 class="sr-only">&nbsp;</h4>
                            <label for="uName">Image</label>
                            <input type="file" name="file" ref="fileInput" multiple @change="onFileSelect"
                                class="form-control">

                        </section>

                    </div>
                    <div class="actions clearfix">
                        <ul role="menu" aria-label="Pagination">
                            <li aria-hidden="false" aria-disabled="false"><a href="#next" role="menuitem"
                                    @click="uploadImages">Save</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const toastr = useToastr(); 

const images = ref([]);
const isDragging = ref(false);
// fileInput.value = null;
const slides = ref([]);

const selectFile = () => {
    fileInput.value.click();
}

const onFileSelect = (event) => {
    debugger;
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

// console.log("RoleID" + role_id);
const uploadImages = () => {
    debugger;
    const formData = new FormData();
    images.value.forEach((image) => {
        formData.append('images[]', image.file, image.name);
    });

    axios.post('/api/upload_carousel', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            fetchSlides();
            console.log('Images uploaded successfully:', response.data);
            toastr.success('Images uploaded successfully');
            images.value = [];
        })
        .catch(error => {
            console.error('Error uploading images:', error);
        });
};

const deleteDBImage = (slide, index) => {

    axios.post('/api/delete_slide', { id: slide.id })
        .then(response => {
            console.log('Slide deleted successfully:', response.data);
            toastr.success('Slide deleted successfully');
            slides.value.splice(index, 1);
        })
        .catch(error => {
            console.error('Error deleting slide:', error);
        });
};

const fetchSlides = async () => {
    try {
        const response = await axios.get('/get_carousel');
        console.log(response.data);
        slides.value = response.data;
    } catch (error) {
        console.error('Failed to fetch slides:', error);
    }
};

onMounted(() => {
    fetchSlides();
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
