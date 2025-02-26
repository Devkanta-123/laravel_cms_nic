<template>
  <div class="default-tab">
		<ul class="nav nav-tabs" role="tablist">
	    		  <li class="nav-item">
	        		<a href="#General" class="ME-Tabs nav-link active" data-bs-toggle="tab">Upload Images</a>
	        	</li>

	        	<li class="nav-item">
	        		<a href="#Advance" class="ME-Tabs nav-link " data-bs-toggle="tab">Edit Images</a>
	        	</li>
    </ul>
	</div>
  <div class="tab-content">
    <div class="tab-pane fade show active" id="General" role="tabpanel">  
				<div class="row">													
						<div class="col-md-12 mb-2 mb-md-3 " id="">
              <div class="card">
                  <div class="top">
                      Drag Images to Upload
                  </div>
          
                  <div class="drag-area" @dragover.prevent="onDragOver" @dragleave.prevent="onDragLeave" @drop.prevent="onDrop">
                      <div  class="select">Drag Images here </div>
                      &nbsp; &nbsp;&nbsp; &nbsp; OR &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;
                      <input type="file" name="file" class="file" ref="fileInput" multiple @change="onFileSelect">
                  </div>

                  <div class="card-body scrollable-container">
                      <div class="image" v-for="(image, index) in images" :key="index">
                          <span class="delete" @click="deleteImage(index)"> &times; </span>
                          <img :src="image.url" :alt="image.name" />
                      </div>
                  </div>

                  <button type="upload" @click="uploadImages">Upload</button>
              </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade " id="Advance" role="tabpanel">  
				<div class="row">					
						<div class="col-md-12 mb-2 mb-md-3 " id="">
              <div class="card">
                <div class=" card-body scrollable-container">
                        <div class="image" v-for="(image, index) in slides" :key="index">
                            <span class="delete" @click="deleteDBImage(image, index)"> &times; </span>
                            <img :src="`/storage/${image.link}`"  :alt="image.link" />
                        </div>
                    </div>
              </div>
            </div>  
        </div>
    </div>

  </div>
   
</template>
<script setup>
import {  ref, onMounted } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';

const images =ref([]);
const isDragging =ref(false);
const fileInput = ref(null);
const slides = ref([]);
const toastr = useToastr(); 

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
    images.value.splice(index,1);
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


const uploadImages = () => {
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
    fileInput = ref(null);
  })
  .catch(error => {
    console.error('Error uploading images:', error);
  });
};

const deleteDBImage = (slide,index) => {
 
  axios.post('/api/delete_slide', {id:slide.id})
  .then(response => {
    console.log('Slide deleted successfully:', response.data);
    toastr.success('Slide deleted successfully');
    slides.value.splice(index,1);
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
    overflow-y: auto; /* Make the container scrollable */
    margin-bottom: 10px;
}
.card .scrollable-container .image {
    position: relative;
    margin: 5px;
}
.card .scrollable-container .image img {
  width: 100px; /* Set the width of the image */
  height: auto; /* Maintain the aspect ratio */
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