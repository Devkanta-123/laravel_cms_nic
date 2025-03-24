<template>
    <div class="container mt-4">
        <!-- Level Selection -->
        <div class="mb-3">
            <label class="form-label">Level</label>
            <select class="form-control" v-model="selectedLevel">
                <option value="1">State</option>
                <option value="2">District</option>
                <option value="3">Block</option>
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
import { useToastr } from '../../toaster.js';
const imagePreview = ref(null); // Stores preview URL

// Dynamic fields storage
const dynamicFields = ref([]);
const selectedLevel = ref("1"); // Default to State
// Watch for category changes and update dynamic fields
watch(selectedLevel, (newVal) => {
    if (newVal === "2") {
        // Load District fields
        dynamicFields.value = [
            { key: "district_name", label: "District Name", placeholder: "Enter District Name" },
        ];
    } else if (newVal === "3") {
        // Load Block fields
        dynamicFields.value = [
            { key: "block_name", label: "Block Name", placeholder: "Enter Block Name" },
        ];
    } else {
        // Remove dynamic fields if "State" is selected
        dynamicFields.value = [];
    }
});


// Handle Image Upload and Preview
const handleImageUpload = (event) => {
    debugger;
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
const toastr = useToastr();
const formData = ref({
    name: "",
    designation: "",
    email: "",
    contact: "",
    profile: null, // Stores selected file
});
onMounted(() => {
    console.log("Component Mounted");
});

// Validate input fields before submission
const validateFields = () => {
    if (
        !formData.english_question.trim() ||
        !formData.english_answer.trim() ||
        !formData.hindi_question.trim() ||
        !formData.hindi_answer.trim() ||
        !formData.khasi_question.trim() ||
        !formData.khasi_answer.trim() ||
        !formData.order.toString().trim()
    ) {
        toastr.error("All fields are required!");
        return false;
    }

    if (isNaN(parseInt(formData.order))) {
        toastr.error("Order must be a valid number.");
        return false;
    }

    return true;
};

const submitData = async () => {
    debugger;
    console.log("Current Form Data:", JSON.stringify(formData));

    if (!validateFields()) return;

    const dataToSend = {
        english_question: formData.english_question,
        english_answer: formData.english_answer,
        hindi_question: formData.hindi_question,
        hindi_answer: formData.hindi_answer,
        khasi_question: formData.khasi_question,
        khasi_answer: formData.khasi_answer,
        order: formData.order
    };

    console.log("Prepared API Data:", dataToSend);
    let response;
    try {
        response = await axios.post("/api/submitFAQData", dataToSend);
        toastr.success(response.data.message);
        // Reset form after successful submission
        Object.keys(formData).forEach(key => formData[key] = "");
        console.log("Form reset successfully");

    } catch (error) {
        if (error.message == "Request failed with status code 409") {
            error.message = "same order number already exists"
            toastr.warning(error.message);
        }
    }
    toastr.error(error.message);

};

</script>


<style scoped>
.container {
    max-width: 800px;
}

.row {
    display: flex;
    align-items: center;
}

/* Container to align radio buttons in a row */
.radio-group {
    display: flex;
    align-items: center;
    gap: 20px;
    /* Space between radio buttons */
}

/* Wrapper for each radio button */
.radio-wrapper {
    display: flex;
    align-items: center;
    position: relative;
}

/* Custom radio button */
input[type="radio"] {
    appearance: none;
    width: 20px;
    height: 20px;
    border: 2px solid #ccc;
    border-radius: 50%;
    outline: none;
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease;
}

/* Highlight effect when checked */
input[type="radio"]:checked {
    border-color: transparent;
    box-shadow: 0px 0px 5px 3px rgba(0, 123, 255, 0.7);
}

/* Dot inside the radio button when checked */
input[type="radio"]::before {
    content: "";
    width: 10px;
    height: 10px;
    background-color: #007bff;
    /* Default blue */
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

/* Show dot when checked */
input[type="radio"]:checked::before {
    opacity: 1;
}

/* Label styles */
.radio-label {
    margin-left: 8px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    cursor: pointer;
    transition: color 0.3s ease;
}

/* Change radio dot color based on selection */
#radioEnglish:checked::before {
    background-color: #007bff;
    /* Blue */
}

#radioHindi:checked::before {
    background-color: #dc3545;
    /* Red */
}

#radioKhasi:checked::before {
    background-color: #28a745;
    /* Green */
}
</style>