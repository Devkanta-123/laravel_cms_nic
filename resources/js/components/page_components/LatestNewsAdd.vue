<template>
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
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import { useToastr } from '../../toaster.js';
import { useLatestNews } from '@/composables/useLatestNews';
const { fetchData } = useLatestNews(); // Reuse the same composable
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
const toastr = useToastr();
const pagemenudata = ref([]);
const selectedMenu = ref(null);
const editorConfig = {
  height: 200,
};

const props = defineProps({
  section: Object,
  menu: Number
})

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
  debugger
  if (!validateInputs()) {
    toastr.error('Please correct the errors before submitting.');
    return; // Stop execution if validation fails
  }

  const formData = new FormData();
  if (file) {
    formData.append('file', file);
  }
  if (link.value) {
    formData.append('link', link.value);
  }
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
    .then(response => {
      console.log('Latest News saved successfully:', response.data.message);
      fetchData(); // ✅ calling fetchData after save
      toastr.success(response.data.message);
    })
    .catch(error => {
      console.error('Error saving news:', error);
      toastr.error('An error occurred while saving the news.');
    });
};

onMounted(() => 
{
    fetchData();

});
</script>

<style scoped></style>