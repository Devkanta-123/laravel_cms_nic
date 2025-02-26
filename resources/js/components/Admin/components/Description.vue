<template>
    <div>
    <h2>Description</h2>
    <ckeditor v-model="editorContent" :config="editorConfig" ></ckeditor>
    <button @click="saveContent">Save Content </button>
  </div>
</template>

<script setup>
  import { ref, defineProps } from 'vue';
  import axios from 'axios';
  import { component as ckeditor } from '@mayasabha/ckeditor4-vue3';
  import { useToastr } from '../../../toaster.js';


  const toastr = useToastr();
  const editorContent = ref('');
  const editorConfig = {
  };

  defineProps({
        menu: String
    })

  const saveContent = async () => {
    try {
      const response = await axios.post('/api/save-content', { content: editorContent.value, menu: menu.value });

      toastr.success('Page created successfully');
      window.location.href= "/admin/pages";
    } catch (error) {
      console.error('Error saving content:', error);
    }
  };
</script>