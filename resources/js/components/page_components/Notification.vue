<template>
    <div class="container mt-4">

        <!-- Category Dropdown -->
        <div class="mb-3">
            <label for="categorySelect" class="form-label">Category</label>
            <select class="form-control" v-model="selectedCategory">
                <option value="" disabled selected>Select the category</option>
                <option v-for="category in categorydata" :key="category.id" :value="category.id">
                    {{ category.category_name }}
                </option>
            </select>
        </div>

        <!-- Dynamic Rows -->
        <div v-for="(row, index) in rows" :key="index" class="mb-3 row">
            <div class="col-md-4">
                <label v-if="index === 0" class="form-label">Title</label>
                <input type="text" class="form-control" v-model="row.title" placeholder="Enter Title" />
            </div>

            <div class="col-md-3">
                <label v-if="index === 0" class="form-label">Date</label>
                <input type="date" class="form-control" v-model="row.date" />
            </div>

            <div class="col-md-3">
                <label v-if="index === 0" class="form-label">Upload File</label>
                <input type="file" multiple accept="application/pdf" @change="handleFileUpload($event, index)" />
            </div>

            <div class="col-md-2 d-flex justify-content-end">
                <button class="btn btn-danger" @click="removeRow(index)">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

        </div>
        <div class="col-md-4 d-flex align-items-center gap-2">
            <!-- Add Row Button -->
            <button class="btn btn-primary" @click="addRow"> Add Row</button>

            <!-- Submit Button -->
            <button class="btn btn-success" @click="submitData"> Submit</button>
        </div>


    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';

const toastr = useToastr();

const selectedCategory = ref("");
const rows = ref([{ title: "", date: "", files: [] }]); // Initial row
const categorydata = ref([]); // Store fetched categories

// Fetch categories from API
const getAllCategoryMaster = async () => {
    try {
        const response = await axios.get('/api/getAllCategoryMaster');
        categorydata.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error.response || error);
        toastr.error("Failed to load categories.");
    }
};

onMounted(getAllCategoryMaster);

// Add a new row dynamically
const addRow = () => {
    rows.value.push({ title: "", date: "", files: [] });
};

// Remove a row
const removeRow = (index) => {
    if (rows.value.length > 1) {
        rows.value.splice(index, 1);
    } else {
        toastr.warning("At least one row data is required.");
    }
};

// Handle file uploads for a specific row
const handleFileUpload = (event, index) => {
    const files = Array.from(event.target.files);
    rows.value[index].files = files;
};

// Validate input fields before submission
const validateFields = () => {
    if (!selectedCategory.value) {
        toastr.error("Please select a category.");
        return false;
    }

    for (let i = 0; i < rows.value.length; i++) {
        if (!rows.value[i].title.trim()) {
            toastr.error(`Title is required in row ${i + 1}.`);
            return false;
        }
        if (!rows.value[i].date) {
            toastr.error(`Date is required in row ${i + 1}.`);
            return false;
        }
        if (rows.value[i].files.length === 0) {
            toastr.error(`File upload is required in row ${i + 1}.`);
            return false;
        }
    }

    return true;
};

// Submit data
const submitData = async () => {
    if (!validateFields()) return;

    const formData = new FormData();
    formData.append("category_id", selectedCategory.value);

    rows.value.forEach((row, index) => {
        formData.append(`title[${index}]`, row.title);
        formData.append(`date[${index}]`, row.date);

        row.files.forEach((file, fileIndex) => {
            formData.append(`file[${index}][${fileIndex}]`, file);
        });
    });

    console.log("Prepared FormData:", Object.fromEntries(formData.entries()));

    try {
        const response = await axios.post("/api/submitNotificationsData", formData, {
            headers: { "Content-Type": "multipart/form-data" }
        });

        toastr.success(response.data.message);
        rows.value = [{ title: "", date: "", files: [] }]; // Reset form after success

    } catch (error) {
        console.error("Error submitting data:", error.response || error);
        toastr.error("An error occurred while submitting the data.");
    }
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
</style>