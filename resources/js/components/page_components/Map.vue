<template>
    <div class="container mt-4">
        <div class="mb-3 row">
            <div class="col-md-12">
                <label class="form-label">iFrame</label>
                <input type="text" class="form-control" v-model="formData.iframe" placeholder="Enter iFrame" />
            </div>
        </div>
        <button class="btn btn-success" @click="submitData">Save</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';

const toastr = useToastr();

// Reactive state
const formData = ref({
    iframe: ""
});

// Submit form data to the backend
const submitData = async () => {
    try {
        debugger;
        const response = await axios.post('/api/addMapData', formData.value, {
            headers: {
                'Content-Type': 'application/json',
            },
        });

        // Handle success response
        toastr.success(response.data.message);
    } catch (error) {
        // Handle error
        console.error('Error:', error?.response?.data);
        toastr.error(error?.response?.data?.message || 'Something went wrong');
    }
};
</script>

<style scoped></style>
