<template>
    <br>
    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <h5>Paragraph <small>(English)</small></h5>
                <div id="example-basic" role="application" class="wizard clearfix">
                    <div class="content clearfix">

                        <ckeditor v-model="editorContent" :config="editorConfig"></ckeditor>

                        <!-- Save Button -->
                        <div class="actions clearfix mt-3">
                            <ul role="menu" aria-label="Pagination">
                                <li v-if="!hasExistingContent">
                                    <button type="button" class="btn btn-success" role="menuitem" @click="saveContent">
                                        Save Content
                                    </button>
                                </li>
                                <li v-else>
                                    <button type="button" class="btn btn-primary" role="menuitem"
                                        @click="updateContent">
                                        Save Changes
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, reactive, onMounted } from 'vue';
import axios from 'axios';
import { component as ckeditor } from '@mayasabha/ckeditor4-vue3';
import { useToastr } from '../../../toaster.js';
const hasExistingContent = ref(false); // To toggle buttons


const toastr = useToastr();
const editorContent = ref(""); // Stores the CKEditor content
const editorConfig = ref({}); // CKEditor configuration
const errors = ref({ file: null });
const paragraphData = ref();
const fileInput = ref(null); // Reference for the file input

const props = defineProps({
    menuId: String,
    menuName: String,
    section: Object
});

//old method
const saveContent = async () => {

    try {
        const response = await axios.post('/api/save-content', {
            content: editorContent.value,
            menu: props.menuId
        });
        toastr.success('Page created successfully');
    } catch (error) {
        console.error('Error saving content:', error);
    }
};

const updateContent = async () => {
    try {
        const response = await axios.put('/api/update-content', {
            content: editorContent.value,
            menu: props.menuId
        });
        toastr.success('Page updated successfully');
    } catch (error) {
        console.error('Error updating content:', error);
    }
};

//   const saveContent = async () => {
//     try {
//       
//       const formData = new FormData();
//       formData.append("content", editorContent.value);
//       formData.append("menu", props.menuId);
//       formData.append("page_section", props.menuName);

//       const files = fileInput.value?.files;
//       if (files && files.length > 0) {
//         for (let i = 0; i < files.length; i++) {
//           formData.append("files[]", files[i]);
//         }
//       }

//       await axios.post("/api/save-content", formData, {
//         headers: { "Content-Type": "multipart/form-data" },
//       });

//       toastr.success("Page created successfully");
//       //window.location.href = "/admin/pages";
//     } catch (error) {
//       console.error("Error saving content:", error);
//       if (error.response?.data?.errors) {
//         Object.assign(errors, error.response.data.errors); // Populate errors
//       }
//     }
//   };
const fetchPageContent = async () => {
    try {
        
        const response = await axios.get(`/get_page_content/${props.menuId}`);
        if (response.data.content) {
            editorContent.value = response.data.content;
            hasExistingContent.value = true;
            paragraphData.value=response.data;
            
        } else {
            hasExistingContent.value = false;
        }
    } catch (error) {
        console.error("Error fetching page content:", error);
    }
};


onMounted(() => {
    fetchPageContent();
});

</script>