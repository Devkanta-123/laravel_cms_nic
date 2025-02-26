<template>
  <div>
    <h5>Description <small>(English) </small> </h5>
    {{ menu }}
    <ckeditor v-model="editorContent" :config="editorConfig"></ckeditor>

    <div class="mb-3">
      <label for="fileInput" class="form-label">Image/File</label>
      <input type="file" id="fileInput" class="form-control" ref="fileInput"
        accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .png, .jpg, .jpeg" multiple />
      <div v-if="errors.file" class="text-danger">{{ errors.file }}</div>
    </div>
  </div>

  <br>
  <button @click="saveContent">Save Content </button>

</template>

<script setup>
import { ref, defineProps,reactive } from 'vue';
import axios from 'axios';
import { component as ckeditor } from '@mayasabha/ckeditor4-vue3';
import { useToastr } from '../../toaster.js';


const toastr = useToastr();
const editorContent = ref('');
const editorConfig = {
};
const fileInput = ref(null); // Reference for the file input
const errors = reactive({}); // Reactive error object

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
    debugger;
    const formData = new FormData();
    formData.append("content", editorContent.value);
    formData.append("menu",props.menu);
    formData.append("page_section",props.menu);

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
</script>