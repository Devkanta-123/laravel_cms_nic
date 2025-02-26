<template>
    <div>
    <h5>Home Page Paragraph <small>(English)</small></h5>
    <ckeditor v-model="editorContent1" :config="editorConfig" ></ckeditor>

    <h5>Home Page Paragraph <small>(Hindi)</small></h5>
    <ckeditor v-model="editorContent2" :config="editorConfig" ></ckeditor>


    <button @click="saveContent">Save Content </button>
  </div>
</template>

<script setup>
  import { ref, defineProps } from 'vue';
  import axios from 'axios';
  import { component as ckeditor } from '@mayasabha/ckeditor4-vue3';
  import { useToastr } from '../../toaster.js';


  const toastr = useToastr();
  const editorContent1 = ref('');
  const editorContent2 = ref('');

  const editorConfig = {};

  defineProps({
        menu: String
    })

  const saveContent = async () => {
    console.log("menu + "+ menu);
    try {
      const response = await axios.post('/api/save-contentWebsite', 
      {   
        content1: editorContent1.value,
        content2: editorContent2.value,
        menu: menu.value 
      });
      toastr.success('Home Page Paragraph saved successfully');

      window.location.href= "/admin/pages";
    } catch (error) {
      console.error('Error saving content:', error);
    }
  };
</script>

