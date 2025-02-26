<template>
    <div class="card">
        <!-- <div class="top">
            Drag Images to Upload
        </div> -->
        <div class="form-group">
            <label for="galleryName">Gallery Name</label>
            <input type="text" id="galleryName" v-model="galleryName" placeholder="Enter gallery name" class="form-control" />
        </div>
        <div class="form-group">
            <label for="galleryDescription">Gallery Description</label>
            <textarea id="galleryDescription" v-model="galleryDescription" placeholder="Enter gallery description" class="form-control"></textarea>
        </div>
        <div class="drag-area" @dragover.prevent="onDragOver" @dragleave.prevent="onDragLeave" @drop.prevent="onDrop">
            <span v-if="!isDragging">
                Drag Images here
                <span class="select" role="button" @click="selectFile">Choose</span>
            </span>
            <div v-else class="select">Drop Images here</div>
            <input type="file" name="file" class="file" ref="fileInput" multiple @change="onFileSelect">
        </div>
        <div class="container">
            <div class="image" v-for="(image, index) in images" :key="index">
                <span class="delete" @click="deleteImage(index)"> &times; </span>
                <img :src="image.url" :alt="image.name" />
            </div>
        </div>
        <button type="button" @click="uploadImages">Upload</button>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import axios from 'axios';

const images = ref([]);
const isDragging = ref(false);
const fileInput = ref(null);
const galleryName = ref('');
const galleryDescription = ref('');

const selectFile = () => {
    fileInput.value.click();
};

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
};

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

const uploadImages = () => {
    if (!galleryName.value || !galleryDescription.value) {
        alert('Please enter gallery name and description');
        return;
    }

    const formData = new FormData();
    formData.append('gallery_name', galleryName.value);
    formData.append('gallery_description', galleryDescription.value);
    images.value.forEach((image) => {
        formData.append('images[]', image.file, image.name);
    });

    axios.post('/api/upload_gallery', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
    .then((response) => {
        console.log('Images uploaded successfully:', response.data);
        images.value = [];
        galleryName.value = '';
        galleryDescription.value = '';
    })
    .catch((error) => {
        console.error('Error uploading images:', error);
    });
};
</script>

<style scoped>
    .card{
            width: 100%;
            padding: 10px;
            box-shadow: 0 0 5px #ffdfdf;
            border-radius: 5px;
            overflow: hidden;
    }
    .card .top{
        text-align: center;
    }
    .card p{
        font-weight: bold;
        color: grey;
    }
    .card button{
        outline: 0;
        border: 0;
        color: white;
        border-radius: 4px;
        font-weight: 400;
        padding: 8px 13px;
        width:100%;
        background: grey;
    }
    .card .drag-area{
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
    .card .drag-area .visible{
        font-size: 18px;
    }
    .card .drag-area .select{
        color: blue;
        margin-left: 5px;
        cursor: pointer;
        transition: 0.4s;
    }
    .card .drag-area .select:hover{
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
    .card .container {
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
    .card .container .image {
    position: relative;
    margin: 5px;
    }
    .card .container .image img {
    width: 100px; /* Set the width of the image */
    height: auto; /* Maintain the aspect ratio */
    border-radius: 5px;
    }
    .card .container .image .delete {
    position: absolute;
    top: -2px;
    right: 9px;
    font-size: 20px;
    cursor: pointer;
    color: red;
    }
    /* .card .container .image img{
        position: absolute;
        top: -2px;
        right: 9px;
        font-size: 20px;
        cursor: pointer;
    } */

</style>