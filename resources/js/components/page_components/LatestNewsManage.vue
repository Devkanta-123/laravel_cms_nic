<template>
  <div class="container">
    <!-- Check if data is available -->
    <table v-if="items.length" class="table table-striped table-responsive">
      <thead>
        <tr>
          <th>#</th>
          <th> Title</th>
          <th>Type</th>
          <th>File/Link</th>
          <th>Status</th>
          <th>Added By</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- Loop through items and display them in table rows -->
        <tr v-for="(item, index) in items" :key="item.id" :class="{ dragging: isDragging && draggedIndex === index }"
          @dragover.prevent @dragenter="onDragEnter(index)" @dragleave="onDragLeave" @drop="onDrop(index)">
          <td>{{ index + 1 }}</td>

          <td>{{ item.title }}</td>
          <td>{{ item.type }}</td>
          <td v-if="item.type === 'file'">
            <a :href="getAssetUrl(item.link)" target="_blank" rel="noopener noreferrer">{{ item.file }}</a>
          </td>
          <td v-else>
            <a :href="item.link" target="_blank">{{ item.link }}</a>
          </td>
          <td>
            <label :class="item.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
              {{ item.flag === 'A' ? 'Approved' : 'Pending' }}
            </label>
          </td>
          <td>{{ item.addedby }}</td>
          <td>
            <a href="#" @click="deleteNews(item.id)"><i class="fa fa-trash text-danger"></i></a> &nbsp;
            <!-- <a href="#" @click="onOpenModal(item.page)" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a> -->
            <span class="drag-handle" draggable="true" @dragstart="onDragStart(index)" @dragend="onDragEnd">

            </span>
          </td>
        </tr>
      </tbody>
    </table>
    <ModalComponent :isOpen="isModalOpen" :title="modalTitle" :component="modalComponent" :section="selectedSection"
      :menu="menuId" @close="isModalOpen = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
import Paragraph from '../../components/page_components/Paragraph.vue';
import ModalComponent from '../../pages/pages/ModalComponent.vue';
const items = ref([]); // Initialize an empty array to hold the data
const toastr = useToastr();
const draggedIndex = ref(null);
const isDragging = ref(false);
const isModalOpen = ref(false); // State for modal visibility
const modalTitle = ref("Paragraph"); // Title of the modal
const modalComponent = ref(null); // Component to load dynamically
const selectedSection = ref({ content: "This is section data" }); // Section data
const menuId = ref(null); // Menu ID passed to the modal
const onOpenModal = (id) => {
  modalComponent.value = Paragraph; // Dynamically assign the Paragraph component
  menuId.value = id; // Pass the menu ID to the modal
  isModalOpen.value = true; // Open the modal
};
// Fetch data from the backend when the component is mounted
const fetchData = async () => {
  try {
    const response = await axios.post('/api/get_latest_news'); // Replace with your API endpoint
    items.value = response.data; // Assign the fetched data to the items array
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

const onDragLeave = () => { };

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
    await axios.post('/api/update_news_order', { items: items.value });
    //toastr.success('Order updated successfully!');
  } catch (error) {
    console.error('Error updating order:', error);
    toastr.error('An error occurred while updating the order.');
  }
};





const deleteNews = (id) => {
  if (confirm('Are you sure you want to delete this item?')) {
    axios.post('/api/delete_news', { id: id })
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



// Update the order of items after drag-and-drop
const updateOrder = async () => {
  try {
    // Create a new array with updated order
    const orderedItems = items.value.map((item, index) => ({
      ...item,
      order: index + 1 // Update order to match the new position
    }));

    // Send updated order to the server
    await axios.post('/api/update_item_order', orderedItems);
    toastr.success('Order updated successfully.');
  } catch (error) {
    console.error('Error updating order:', error);
    toastr.error('An error occurred while updating the order.');
  }
};

onMounted(() => {
  fetchData();
});
</script>


<style scoped>
.container {
  margin-top: 20px;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
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