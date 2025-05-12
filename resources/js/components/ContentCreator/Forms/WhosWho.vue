<template>
    <div class="container mt-4">
        <!-- Level Selection -->
        <div class="mb-3">
            <label class="form-label">Level</label>
            <select class="form-control" v-model="selectedLevel">
                <option value="" disabled selected>Select the Level</option>
                <option v-for="level in masterleveldata" :key="level.id" :value="level.id">
                    {{ level.level_name }}
                </option>
            </select>
        </div>

        <!-- Dynamic Fields for District or Block -->
        <div class="mb-3 row" v-if="dynamicFields.length > 0">
            <div class="col-md-12" v-for="field in dynamicFields" :key="field.key">
                <label class="form-label">{{ field.label }}</label>
                <input type="text" class="form-control" v-model="formData[field.key]"
                    :placeholder="field.placeholder" />
            </div>
        </div>

        <!-- Name & Designation -->
        <div class="mb-3 row">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" v-model="formData.name" placeholder="Enter Name" />
            </div>

            <div class="col-md-6">
                <label class="form-label">Designation</label>
                <input type="text" class="form-control" v-model="formData.designation"
                    placeholder="Enter Designation" />
            </div>
        </div>

        <!-- Email & Contact -->
        <div class="mb-3 row">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" v-model="formData.email" placeholder="Enter Email" />
            </div>

            <div class="col-md-6">
                <label class="form-label">Contact</label>
                <input type="number" class="form-control" v-model="formData.contact" placeholder="Enter Contact" />
            </div>
        </div>

        <!-- Profile Upload with Image Preview -->
        <div class="mb-3 row">
            <div class="col-md-6">
                <label class="form-label">Profile</label>
                <input type="file" class="form-control" @change="handleImageUpload" accept="image/*" />
            </div>

            <!-- Image Preview -->
            <div class="col-md-6" v-if="imagePreview">
                <img :src="imagePreview" class="img-thumbnail" style="max-width: 150px; height: auto;" />
            </div>
        </div>
        <button class="btn btn-success" @click="submitData">Submit</button>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';

// Initialize required references
const imagePreview = ref(null); // Stores preview URL
const masterleveldata = ref([]); // Store levels data
const dynamicFields = ref([]); // Stores dynamic fields based on level
const selectedLevel = ref(''); // The selected level ID

const formData = ref({
    name: "",
    designation: "",
    email: "",
    contact: "",
    profile: null, // For storing profile image file
    level_id: null, // Store the selected level id
    dynamicFields: {}, // Store dynamic fields data
});

// Fetch all levels (Master data)
const getAllLevelMaster = async () => {
    try {
        const response = await axios.get('/api/getAllLevelMaster');
        masterleveldata.value = response.data; // Update masterleveldata with the response
    } catch (error) {
        console.error('Error fetching levels:', error.response || error);
        toastr.error("Failed to load levels.");
    }
};

// Watch for changes in selectedLevel and update dynamic fields accordingly
watch(selectedLevel, (newVal) => {
    debugger;
    console.log("Selected level changed to:", newVal); // Check if the watch is triggered
    if (newVal === 2) {
        dynamicFields.value = [
            { key: "district_name", label: "District Name", placeholder: "Enter District Name" },
        ];
    } else if (newVal === 3) {
        dynamicFields.value = [
            { key: "block_name", label: "Block Name", placeholder: "Enter Block Name" },
        ];
    } else {
        console.log("Clearing dynamic fields"); // Debugging message
        dynamicFields.value = [];
    }
});

// Handle Image Upload and Preview
const handleImageUpload = (event) => {
    const file = event.target.files[0]; // Get selected file
    if (file) {
        formData.value.profile = file; // Store file in formData
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result; // Generate preview
        };
        reader.readAsDataURL(file);
    } else {
        formData.value.profile = null;
        imagePreview.value = null;
    }
};

// Toastr for notifications
const toastr = useToastr();

// Fetch all level data when component is mounted
onMounted(() => {
    getAllLevelMaster();
});

// Submit the form data to the backend
const submitData = async () => {
    debugger;
    // Prepare form data to send to the backend
    const dataToSend = new FormData();

    // Append standard form data values
    dataToSend.append('name', formData.value.name);
    dataToSend.append('designation', formData.value.designation);
    dataToSend.append('email', formData.value.email);
    dataToSend.append('contact', formData.value.contact);
    dataToSend.append('level_id', selectedLevel.value); // Append selected level id
    dataToSend.append('profile_image', formData.value.profile); // Add profile image if uploaded

    // Append dynamic fields based on selected level
    if (dynamicFields.value.length > 0) {
        dynamicFields.value.forEach(field => {
            dataToSend.append(field.key, formData.value[field.key] || ''); // Handle dynamic field values
        });
    }

    try {
        const response = await axios.post('/api/addWhosWho', dataToSend, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        // Handle the success response
        toastr.success(response.data.message);
    } catch (error) {
        // Handle error
        console.error('Error:', error.response.data);
        toastr.error(error.response.data.message || 'Something went wrong');
    }
};

</script>


<style scoped>

</style>