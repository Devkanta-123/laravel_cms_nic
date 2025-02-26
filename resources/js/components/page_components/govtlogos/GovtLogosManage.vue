<template>
    <div class="container mt-4">
      <h3 v-if="!isEditing">Manage Logos</h3>
      
      <!-- List of Logos -->
      <div v-if="!isEditing">
        <div v-if="items.length === 0" class="alert alert-warning">No logos available.</div>
        <table v-if="items.length" class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Logo Link</th>
              <th>Logo</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="(item, index) in items" 
              :key="item.id"
              :class="{ dragging: isDragging && draggedIndex === index }"
              @dragover.prevent
              @dragenter="onDragEnter(index)"
              @dragleave="onDragLeave"
              @drop="onDrop(index)"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ item.logo_link }}</td>
              <td>
                <a :href="getAssetUrl(item.logo)" target="_blank" rel="noopener noreferrer">{{ item.logo }}</a>
              </td>
              <td>
                <a href="#" @click="editLogo(index)"><i class="fa fa-edit text-primary"></i></a>
                <a href="#" @click="deleteLogo(item.id)"><i class="fa fa-trash text-danger"></i></a>
                <span 
                  class="drag-handle" 
                  draggable="true"
                  @dragstart="onDragStart(index)"
                  @dragend="onDragEnd"
                >
                  ☰
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Edit Logo Form -->
      <div v-if="isEditing" class="mt-4">
        <h3>Edit Logo</h3>
        <form @submit.prevent="saveLogo">
          <div class="form-group mb-3">
            <label for="logoLink">Logo Link</label>
            <input v-model="editedLogo.logo_link" type="text" id="logoLink" class="form-control" required />
          </div>
          <div class="form-group mb-3">
            <label for="logo">Logo</label>
            <input @change="handleFileUpload" type="file" id="logo" class="form-control" accept="image/*" />
          </div>
          <button class="btn btn-success" type="submit">Save</button>
          <button class="btn btn-secondary" @click="cancelEdit">Cancel</button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useToastr } from '../../../toaster.js';
  
  const items = ref([]);
  const toastr = useToastr();
  const draggedIndex = ref(null);
  const isDragging = ref(false);
  const isEditing = ref(false);
  const editedLogo = ref(null);
  const selectedFile = ref(null);
  
  const fetchData = async () => {
    try {
      const response = await axios.post('/api/get_govt_logos');
      items.value = response.data.data;
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };
  
  const getAssetUrl = (path) => {
    return `${window.location.origin}/storage/${path}`;
  };
  
  const onDragStart = (index) => {
    isDragging.value = true;
    draggedIndex.value = index;
  };
  
  const onDragEnter = (index) => {
    if (draggedIndex.value !== index) {
      const draggedItem = items.value.splice(draggedIndex.value, 1)[0];
      items.value.splice(index, 0, draggedItem);
      draggedIndex.value = index;
    }
  };
  
  const onDragLeave = () => {};
  
  const onDrop = async (index) => {
    isDragging.value = false;
    draggedIndex.value = null;
    await saveOrder();
  };
  
  const onDragEnd = () => {
    isDragging.value = false;
    draggedIndex.value = null;
  };
  
  const saveOrder = async () => {
    try {
      await axios.post('/api/update_logo_order', { items: items.value });
      toastr.success('Order updated successfully!');
    } catch (error) {
      console.error('Error updating order:', error);
      toastr.error('An error occurred while updating the order.');
    }
  };
  
  const deleteLogo = (id) => {
    if (confirm('Are you sure you want to delete this logo?')) {
      axios.post('/api/delete_logo', { id: id })
        .then(response => {
          toastr.success(response.data.message);
          items.value = response.data.data;
        })
        .catch(error => {
          console.error('Error deleting logo:', error);
          toastr.error('An error occurred while deleting the logo.');
        });
    }
  };
  
  const editLogo = (index) => {
    editedLogo.value = { ...items.value[index] };
    isEditing.value = true;
  };
  
  const handleFileUpload = (event) => {
    selectedFile.value = event.target.files[0];
  };
  
  const saveLogo = async () => {
    const formData = new FormData();
    formData.append('id', editedLogo.value.id);
    formData.append('logo_link', editedLogo.value.logo_link);
    if (selectedFile.value) {
      formData.append('logo', selectedFile.value);
    }
  
    try {
      const response = await axios.post('/api/update_logo', formData);
      items.value = response.data.data;
      toastr.success('Logo updated successfully!');
      isEditing.value = false;
      editedLogo.value = null;
      selectedFile.value = null;
    } catch (error) {
      console.error('Error updating logo:', error);
      toastr.error('An error occurred while updating the logo.');
    }
  };
  
  const cancelEdit = () => {
    isEditing.value = false;
    editedLogo.value = null;
    selectedFile.value = null;
  };
  
  fetchData();
  </script>
  
  <style scoped>
  .container {
    margin-top: 20px;
  }
  
  .table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .table th, .table td {
    padding: 8px;
    border: 1px solid #ddd;
    text-align: left;
  }
  
  .table-striped tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
  }
  
  .drag-handle {
    cursor: move;
    margin-left: 10px;
  }
  
  .dragging {
    opacity: 0.5;
  }
  
  .table tbody tr {
    transition: all 0.2s ease;
  }
  </style>
  