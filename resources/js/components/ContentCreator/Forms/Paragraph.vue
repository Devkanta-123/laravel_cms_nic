<template>
    <br>
    <br>
    <br>
     <div class="content ml-6 mr-6">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Pages / {{ route.params.menuName }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="#" @click="onBack()" class="btn btn-primary btn-sm pl-3 pr-3 pt-2 pb-2">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <h5>Paragraph <small>(English)</small></h5>
                <div id="example-basic" role="application" class="wizard clearfix">
                    <div class="content clearfix">

                        <ckeditor v-model="editorContent" :config="editorConfig"></ckeditor>

                        <div class="col-md-4">
                            <label class="form-label">Publisher<span class="text-danger">*</span></label>
                            <select class="form-control" v-model="selectedPublisher" required>
                                <option value="" disabled>Select the Publisher</option>
                                <option v-for="publisher in publisherData" :key="publisher.id" :value="publisher.id">
                                    {{ publisher.name }}
                                </option>
                            </select>

                        </div>
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
import DOMPurify from 'dompurify';
import { component as ckeditor } from '@mayasabha/ckeditor4-vue3';
import { useToastr } from '../../../toaster.js';
const hasExistingContent = ref(false); // To toggle buttons
const selectedPublisher = ref("");
const publisherData = ref([]); // Store publisher categories
const toastr = useToastr();
const editorContent = ref(""); // Stores the CKEditor content
const editorConfig = ref({}); // CKEditor configuration
const errors = ref({ file: null });
const paragraphData = ref();
const fileInput = ref(null); // Reference for the file input
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';
const route = useRoute();
const router = useRouter();
const props = defineProps({
    menuId: String,
    menuName: String,
    section: Object
});
const paraID = ref(null);
//old method
const saveContent = async () => {
    try {
        // Sanitize content from CKEditor
        const sanitizedContent = DOMPurify.sanitize(editorContent.value, {
            ALLOWED_TAGS: ['p', 'b', 'i', 'u', 'a', 'ul', 'ol', 'li', 'br', 'img', 'iframe'],
            ALLOWED_ATTR: ['href', 'src', 'alt', 'title', 'width', 'height', 'frameborder', 'allow', 'allowfullscreen']
        });


        const response = await axios.post('/api/save-content', {
            content: sanitizedContent,
            menu: props.menuId,
            publisher_id: selectedPublisher.value,
        });

        toastr.success('Page created successfully');
    } catch (error) {
        console.error('Error saving content:', error);
    }
};

const onBack = () => {
    router.push('/contentcreator/pages-form/1/Home/0')
}
const getAllPublisher = async () => {
    try {
        const response = await axios.get('/api/get_allpublisher');
        publisherData.value = response.data.data;

        // Auto-select if only one publisher exists
        if (publisherData.value.length === 1) {
            selectedPublisher.value = publisherData.value[0].id;
        }

        // ✅ Auto-select based on previously fetched paragraph data
        if (paragraphData.value?.publisher_id) {
            selectedPublisher.value = paragraphData.value.publisher_id;
        }

    } catch (error) {
        console.error('Error fetching publishers:', error.response || error);
        toastr.error("Failed to load publishers.");
    }
};

const updateContent = async () => {
    try {
        const response = await axios.post('/api/save-content', {
            content: editorContent.value,
            menu_id: route.params.menuId,
            page_section_id: route.params.page_section_id,
            publisher_id: selectedPublisher.value,
            id: paraID.value
        });
        if (response)
            toastr.success('Page updated successfully');
    } catch (error) {
        console.error('Error updating content:', error);
    }
};


const fetchPageContent = async () => {
    try {
        const response = await axios.get(`/get_page_content/${props.menuId}`);
        if (response.data.content) {
            editorContent.value = response.data.content;
            hasExistingContent.value = true;
            paragraphData.value = response.data;
            paraID.value = response.data.id
            if (response.data.publisher_id) {
                selectedPublisher.value = response.data.publisher_id;
            }
        } else {
            hasExistingContent.value = false;
        }
    } catch (error) {
        console.error("Error fetching page content:", error);
    }
};


onMounted(() => {
    fetchPageContent();
    getAllPublisher();
});

</script>