<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Paragraph </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="paragraphTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Old Content</th>
                                    <th>New Content</th>
                                    <th>Added On</th>
                                    <th>Added By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(para, index) in paragraphData" :key="index">
                                    <td> {{ stripHtml(para.description) }} </td>
                                    <!-- <td> {{ stripHtml(para.new_description) }} </td> -->
                                    <td v-html="highlightDiff(para.description, para.new_description)"></td>
                                    <td>{{ formatDate(para.created_at) }}</td>
                                    <td>{{ para.addedby }}</td>
                                    <td>
                                        <label :class="{
                                            'badge bg-success': para.flag === 'A',
                                            'badge bg-info': para.flag === 'N',
                                            'badge bg-warning': para.flag === 'U'
                                        }">
                                            {{
                                                para.flag === 'A' ? 'Approved' :
                                                    para.flag === 'N' ? 'New' :
                                                        para.flag === 'U' ? 'Updated' :
                                                            'Unknown'
                                            }}
                                        </label>

                                    </td>
                                    <td>
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(news.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="para.flag === 'A'"
                                                    :disabled="para.flag === 'A'"
                                                    @change="approveParagraph(para.id, index)">
                                                <span></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();
const paragraphData = ref();
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const stripHtml = (html) => {
    const div = document.createElement("div");
    div.innerHTML = html;
    return div.textContent || div.innerText || "";
};

const highlightDiff = (oldStr, newStr) => {
    if (!newStr) return ''; // If newStr is null or empty, return empty string

    const oldText = stripHtml(oldStr || '');
    const newText = stripHtml(newStr);

    const oldWords = oldText.split(/\s+/);
    const newWords = newText.split(/\s+/);

    return newWords.map((word, index) => {
        if (oldWords[index] !== word) {
            return `<span class="bg-warning text-dark px-1 rounded">${word}</span>`;
        }
        return word;
    }).join(' ');
};



const fetchPageContent = async () => {
    try {
        const response = await axios.get(`/get_page_content_bypublisher`);
        if (response.data) {
            paragraphData.value = response.data;
            console.log("para data", response.data);
            await nextTick(); // Wait for DOM to update
            if ($.fn.dataTable.isDataTable('#paragraphTable')) {
                $('#paragraphTable').DataTable().destroy();
            }
            $('#paragraphTable').DataTable({
                responsive: true,
                pageLength: 10,
            });
        } else {
            paragraphData.value = false;
        }
    } catch (error) {
        console.error("Error fetching page content:", error);
    }
};




const approveParagraph = async (id, index) => {
    try {
        debugger;
        const response = await axios.post('/approved_paragraph', { id });
        if (response.data.success) {
            paragraphData.value[index].flag = 'A'; // update UI immediately
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


onMounted(() => {
    // fetchLatestNews()
    fetchPageContent()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
