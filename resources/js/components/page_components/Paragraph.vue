<template>
  <div>
    <h5>Description <small>(English)</small></h5>
    <ckeditor v-model="editorContent" :config="editorConfig"></ckeditor>

    <div class="mb-3">
      <label for="fileInput" class="form-label">Image/File</label>
      <input type="file" id="fileInput" class="form-control" ref="fileInput"
        accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .png, .jpg, .jpeg" multiple />
      <div v-if="errors.file" class="text-danger">{{ errors.file }}</div>
    </div>

    <br>
    <button @click="saveContent" class="btn btn-success">Save Content</button>
  </div>
</template>

<script setup>
import { ref, defineProps, reactive, onMounted } from 'vue';
import axios from 'axios';
import { component as ckeditor } from '@mayasabha/ckeditor4-vue3';
import { useToastr } from '../../toaster.js';


const toastr = useToastr();
const editorContent = ref(""); // Stores the CKEditor content
const editorConfig = ref({}); // CKEditor configuration
const errors = ref({ file: null });

const fileInput = ref(null); // Reference for the file input

const props = defineProps({
  menu: String,
  section: Object
});

//old method
// const saveContent = async () => {

//   try {
//     const response = await axios.post('/api/save-content', {
//       content: editorContent.value,
//       menu: props.menu,
//       page_section: props.section.page_section_id
//     });

//     toastr.success('Page created successfully');
//     window.location.href = "/admin/pages";
//   } catch (error) {
//     console.error('Error saving content:', error);
//   }
// };

const saveContent = async () => {
  try {
    
    const formData = new FormData();
    formData.append("content", editorContent.value);
    formData.append("menu", props.menu);
    formData.append("page_section", props.menu);

    const files = fileInput.value?.files;
    if (files && files.length > 0) {
      for (let i = 0; i < files.length; i++) {
        formData.append("files[]", files[i]);
      }
    }

    await axios.post("/api/save-content", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    toastr.success("Page created successfully");
    //window.location.href = "/admin/pages";
  } catch (error) {
    console.error("Error saving content:", error);
    if (error.response?.data?.errors) {
      Object.assign(errors, error.response.data.errors); // Populate errors
    }
  }
};
const fetchPageContent = async () => {
  try {
    console.log("Fetching content for menu:", props.menu);
    const response = await axios.get(`/get_page_content/${props.menu}`);

    if (response.data.content) {
      editorContent.value = response.data.content; // Set content if available
    }
  } catch (error) {
    console.error("Error fetching page content:", error);
  }
};

onMounted(() => {
  fetchPageContent();
});

</script>