<template>
    <div class="card p-4 shadow-sm">
        <div class="row align-items-start">
            <!-- English Section -->
            <div class="col-md-7">
                <div class="mb-3">
                    <label class="form-label">
                        English Title <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" placeholder="English Title"
                        v-model="formData.english_question" />
                    <textarea class="form-control mt-2" v-model="formData.english_answer" rows="2"
                        placeholder="English Answer"></textarea>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-8">
                        <label class="form-label">Order</label>
                        <input type="number" class="form-control" v-model="formData.order" placeholder="Order" />
                    </div>
                </div>
            </div>

            <!-- More Languages -->
            <div class="col-md-5">
                <p>More Languages</p>
                <div class="advanced-fields">
                    <div class="mb-3">
                        <label class="form-label">Hindi Question</label>
                        <input type="text" class="form-control" placeholder="Hindi Question"
                            v-model="formData.hindi_question" />
                        <textarea class="form-control mt-2" rows="2" v-model="formData.hindi_answer"
                            placeholder="Hindi Answer"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Khasi Question</label>
                        <input type="text" class="form-control" placeholder="Khasi Question"
                            v-model="formData.khasi_question" />
                        <textarea class="form-control mt-2" rows="2" v-model="formData.khasi_answer"
                            placeholder="Khasi Answer"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button class="btn btn-success mt-3" @click="submitData">Submit</button>
    </div>
</template>



<script setup>
import { onMounted, reactive } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
const props = defineProps({
  menu: String,
  section: Number,
});
const menu_id = props.menu;
const page_section_id = props.section.page_section_id;
const toastr = useToastr();
const formData = reactive({
    english_question: "",
    english_answer: "",
    hindi_question: "",
    hindi_answer: "",
    khasi_question: "",
    khasi_answer: "",
    order: "",

});

onMounted(() => {
    console.log("Component Mounted");
});

// Validate input fields before submission
const validateFields = () => {
    if (
        !formData.english_question.trim() ||
        !formData.english_answer.trim() ||
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
        order: formData.order,
        menu_id:menu_id,
        page_section_master_id:page_section_id
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

.advanced-fields {
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 10px;
}

.advanced-fields input,
.advanced-fields textarea {
    background-color: #f1f1f1;
}
</style>