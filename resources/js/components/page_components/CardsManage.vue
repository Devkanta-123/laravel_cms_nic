<template>
    <div class="container">
      <table v-if="items.length" class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Logo</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in items" :key="item.id" 
              :class="{ dragging: isDragging && draggedIndex === index }"
              @dragover.prevent
              @dragenter="onDragEnter(index)"
              @dragleave="onDragLeave"
              @drop="onDrop(index)">
            <td>{{ index + 1 }}</td>
            <td>{{ item.card_title }}</td>
            <td>{{ item.card_description }}</td>
            <td>
              <a :href="getAssetUrl(item.image)" target="_blank" rel="noopener noreferrer">{{ item.image }}</a>
            </td>
            <td>
              <a href="#" @click="deleteNews(item.id)"><i class="fa fa-trash text-danger"></i></a>
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
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useToastr } from '../../toaster.js';
  
  const items = ref([]); // Initialize an empty array to hold the data
  const toastr = useToastr();
  const draggedIndex = ref(null);
  const isDragging = ref(false);
  
  // Fetch data from the backend when the component is mounted
  const fetchData = async () => {
    try {
      const response = await axios.post('/api/get_cards'); // Replace with your API endpoint
      items.value = response.data.data; // Assign the fetched data to the items array
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };
  
  // Function to generate the full asset URL
  const getAssetUrl = (path) => {
    return `${window.location.origin}/storage/${path}`;
  };
  
  // Drag-and-drop functions
  const onDragStart = (index) => {
    isDragging.value = true;
    draggedIndex.value = index;
  };
  
  const onDragEnter = (index) => {
    if (draggedIndex.value !== index) {
      // Swap items in the array
      const draggedItem = items.value.splice(draggedIndex.value, 1)[0];
      items.value.splice(index, 0, draggedItem);
      draggedIndex.value = index;
    }
  };
  
  const onDragLeave = () => {};
  
  const onDrop = async (index) => {
    isDragging.value = false;
    draggedIndex.value = null;
    // Save the new order to the database
    await saveOrder();
  };
  
  const onDragEnd = () => {
    isDragging.value = false;
    draggedIndex.value = null;
  };
  
  // Save the new order to the database
  const saveOrder = async () => {
    try {
      await axios.post('/api/update_card_order', { items: items.value });
    //toastr.success('Order updated successfully!');
    } catch (error) {
      console.error('Error updating order:', error);
      toastr.error('An error occurred while updating the order.');
    }
  };
  
  const deleteNews = (id) => {
    if (confirm('Are you sure you want to delete this item?')) {
      axios.post('/api/delete_card', { id: id })
        .then(response => {
          console.log('News deleted successfully:', response.data.message);
          toastr.success(response.data.message);
          items.value = response.data.data;
        })
        .catch(error => {
          console.error('Error deleting news:', error);
          toastr.error('An error occurred while deleting the news.');
        });
    }
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
  