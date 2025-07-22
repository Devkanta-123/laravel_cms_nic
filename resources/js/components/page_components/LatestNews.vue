<template>
  <div class="container mt-3">
    <div class="tabs">
      <div class="tab-item" :class="{ active: selectedTab === 'add' }" @click="selectedTab = 'add'">
        Add Latest News
      </div>
      <div class="tab-item" :class="{ active: selectedTab === 'manage' }" @click="selectedTab = 'manage'">
        Manage
      </div>

    </div>

    <div class="tab-content">
      <div v-show="selectedTab === 'add'">
        <!-- <LatestNewsAdd  :menu="menu"  :section="section"/> -->

        <div class="content">
          <!-- Add News Form Content -->
          <!-- Text Input Row -->
          <div>

            <label for="textInput" class="form-label">Title <small>(English)</small> <span
                class="text-danger">*</span></label>
            <input type="text" id="textInputTitle" class="form-control" v-model="title" placeholder="Enter some text" />
            <!-- <ckeditor v-model="title" :config="editorConfig"></ckeditor> -->
            <div v-if="errors.title" class="text-danger">{{ errors.title }}</div>
          </div>
          <!--   
      <div class="mb-3">
        <label for="textInput" class="form-label">Title <small>(Khasi)</small> </label>
        <input
          type="text"
          id="textInput"
          class="form-control"
          v-model="titleK"
          placeholder="Enter some text"
        />
      </div>
  
      <div class="mb-3">
        <label for="textInput" class="form-label">Title <small>(Hindi)</small> </label>
        <input
          type="text"
          id="textInput"
          class="form-control"
          v-model="titleH"
          placeholder="Enter some text"
        />
      </div>
  
      <div class="mb-3">
        <label for="textInput" class="form-label">Title <small>(Others)</small> </label>
        <input
          type="text"
          id="textInput"
          class="form-control"
          v-model="titleO"
          placeholder="Enter some text"
        />
      </div> -->

          <!-- File Input -->
          <fieldset class="mb-3 custom-fieldset">
            <legend>Select Link or File</legend>

            <!-- File Input -->
            <div class="mb-3">
              <label for="fileInput" class="form-label">Upload File (PDF or Office files)</label>
              <input type="file" id="fileInput" class="form-control" ref="fileInput" @change="handleFileChange"
                accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx" :disabled="isFileDisabled" />
              <div v-if="errors.file" class="text-danger">{{ errors.file }}</div>
            </div>

            <!-- Link Input -->
            <div class="mb-3">
              <label for="linkInput" class="form-label">Link</label>
              <input type="text" id="linkInput" class="form-control" v-model="link" placeholder="Enter a link"
                :disabled="isLinkDisabled" @input="handleLinkChange" />
              <div v-if="errors.link" class="text-danger">{{ errors.link }}</div>
            </div>

            <div>
              <!-- Bootstrap Select Dropdown -->

            </div>
          </fieldset>

          <!-- Save Button -->
          <div class="row mb-3">
            <input type="button" value="Save" @click="saveLatestNews()" class="btn btn-secondary w-100">
          </div>
        </div>
      </div>
      <div v-show="selectedTab === 'manage'">
        <div class="container">
          <!-- Check if data is available -->
          <table class="table table-striped table-responsive" id="adminLatestNews">
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
              <tr v-for="(item, index) in items" :key="item.id"
                :class="{ dragging: isDragging && draggedIndex === index }" @dragover.prevent
                @dragenter="onDragEnter(index)" @dragleave="onDragLeave" @drop="onDrop(index)">
                <td>{{ index + 1 }}</td>

                <td>{{ item.title }}</td>
                <td>{{ item.type }}</td>
                <td v-if="item.type === 'file'">
                  <a :href="`/storage/${item.file}`" target="_blank" class="text-primary">
                    {{ item.title }}
                  </a>
                </td>
                <td v-else-if="item.type === 'link'">
                  <a :href="item.link" target="_blank" class="text-success">
                    {{ item.title }}
                  </a>
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

        </div>
      </div>
    </div>

  </div>
</template>
<script setup>
import { ref, onMounted, nextTick, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useToastr } from '../../toaster.js';
const selectedTab = ref('add') // Default to 'add' tab
const toastr = useToastr();
const isDataTableInitialized = ref(false) // Track if DataTable already initialized
import ModalComponent from '../../pages/pages/ModalComponent.vue';
const modalComponent = ref(null); // Component to load dynamically
const props = defineProps({
  menu: String,
  section: Object
});
const items = ref([]); // Initialize an empty array to hold the data
const title = ref('');
const titleK = ref('');
const titleH = ref('');
const titleO = ref('');
const link = ref('');
const route = useRoute();
import { useRoute } from 'vue-router';
const fileInput = ref(null);
const errors = ref({ title: '', file: '', link: '', menu_type: null });
let file = null;
const isFileDisabled = ref(false);
const isLinkDisabled = ref(false);

const handleFileChange = () => {
  file = fileInput.value?.files[0];
  if (file) {
    link.value = ''; // Clear link if a file is selected
    isLinkDisabled.value = true; // Disable the link input
    errors.value.link = ''; // Clear link error
  } else {
    isLinkDisabled.value = false; // Enable the link input if no file is selected
  }
};

const handleLinkChange = () => {
  debugger;
  if (link.value) {
    file = null; // Clear file if a link is entered
    fileInput.value.value = ''; // Reset file input
    isFileDisabled.value = true; // Disable the file input
    errors.value.file = ''; // Clear file error
  } else {
    isFileDisabled.value = false; // Enable the file input if no link is entered
  }
};

const validateInputs = () => {
  let isValid = true;
  if (!title.value) {
    errors.value.title = 'Title is required.';
    isValid = false;
  } else {
    errors.value.title = ''; // Clear title error if valid
  }

  if (!file && !link.value) {
    errors.value.file = 'Either a file or a link is required.';
    errors.value.link = 'Either a file or a link is required.';
    isValid = false;
  } else {
    errors.value.file = ''; // Clear file error if valid
    errors.value.link = ''; // Clear link error if valid
  }

  return isValid;
};



const saveLatestNews = () => {
  if (!validateInputs()) {
    toastr.error('Please correct the errors before submitting.');
    return;
  }

  const formData = new FormData();
  if (file) formData.append('file', file);
  if (link.value) formData.append('link', link.value);
  formData.append('title', title.value);
  formData.append('titleK', titleK.value);
  formData.append('titleH', titleH.value);
  formData.append('titleO', titleO.value);
  formData.append("menu_id", route.params.menuId);
  formData.append("page_section_master_id", props.section.page_section_id);

  axios.post('/api/save_latest_news', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
  .then(async (response) => {
    toastr.success(response.data.message);

    // ✅ Wait a short time before fetching latest data
    setTimeout(async () => {
      await fetchData();
      await nextTick();
      initDataTable();
    }, 300); // Wait 300ms
  })
  .catch(error => {
    console.error('Error saving news:', error);
    toastr.error('An error occurred while saving the news.');
  });
};

const deleteNews = (id) => {
  debugger;
  if (confirm('Are you sure you want to delete this item?')) {
    axios.post('/api/delete_news', {
      id: id,
      menu_id: route.params.menuId,
      page_section_master_id: props.section.page_section_master.id
    })
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

const fetchData = async () => {
  try {
    const response = await axios.post('/api/get_latest_news');
    items.value = response.data || [];
  } catch (error) {
    console.error('Error fetching news:', error);
    toastr.error('Failed to load news.');
    items.value = [];
  }
};

const initDataTable = () => {
  const table = $('#adminLatestNews');

  if (!table.length || items.value.length === 0) {
    // Table not in DOM or no data
    console.warn('Table not ready or no data yet.');
    return;
  }

  // Destroy existing instance
  if ($.fn.DataTable.isDataTable(table)) {
    table.DataTable().destroy();
  }

  // Initialize new instance
  table.DataTable({
    scrollX: true,
    scrollY: '400px',
    scrollCollapse: true,
    responsive: true,
    language: {
      emptyTable: 'No data available',
    },
  });
};

watch(selectedTab, async (newVal) => {
  if (newVal === 'manage') {
    await fetchData();
    
    // Wait for Vue to render <tr> rows based on items.value
    await nextTick();

    // Add a buffer to ensure DataTable operates after DOM is updated
    setTimeout(() => {
      initDataTable();
    }, 10); // 10ms is usually enough
  }
});

// Optional: if user starts directly on "manage"
onMounted(async () => {
  if (selectedTab.value === 'manage') {
    await fetchData()
  }
})



</script>

<style scoped></style>