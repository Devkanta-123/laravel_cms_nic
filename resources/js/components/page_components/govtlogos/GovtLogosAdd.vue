<template>
    <div class="container mt-4">
      <h3 class="mb-3">Add Government Logo</h3>
      <form @submit.prevent="submitForm">
        <div class="form-group mb-3">
          <label for="logo">Select Logo</label>
          <input type="file" id="logo" class="form-control" @change="onFileChange" required />
        </div>
        <div class="form-group mb-3">
          <label for="link">Link</label>
          <input v-model="link" type="url" id="link" class="form-control" required placeholder="Enter URL" />
        </div>
        <button class="btn btn-primary" type="submit">Add Logo</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const link = ref('');
  const selectedFile = ref(null);
  
  const onFileChange = (event) => {
    selectedFile.value = event.target.files[0];
  };
  
  const submitForm = async () => {
    const formData = new FormData();
    formData.append('logo', selectedFile.value);
    formData.append('link', link.value);
  
    try {
      await axios.post('/api/save_govt_logo', formData);
      alert('Logo added successfully');
      location.reload
    } catch (error) {
      console.error('Error adding logo:', error);
    }
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
    margin: 0 auto;
  }
  </style>
  