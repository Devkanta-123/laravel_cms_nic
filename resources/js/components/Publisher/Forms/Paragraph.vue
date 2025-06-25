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
                                       <label v-if="para.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="para.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>

                                        <div v-else-if="para.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ para.rejected_remarks }}
                                            </div>
                                        </div>

                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>
                                    <td>
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(news.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="para.flag === 'A'"
                                                    :disabled="para.flag === 'A'"
                                                    @change="approveContent(para.id, index)">
                                                <span></span>
                                            </label>
                                              <i class="fas fa-times text-danger"
                                                v-if="para.flag !== 'A' && para.flag !== 'R'" data-toggle="modal"
                                                data-target="#rejectedModal" @click="rejectedModal(para)"></i>
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

        <div class="modal fade" id="rejectedModal" tabindex="-1" role="dialog" aria-labelledby="rejectedModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejected Remarks <span class="text-danger">*</span></label>
                        <textarea v-model="rejectedRemarks" class="form-control" placeholder="Remarks...."></textarea>
                        <span v-if="rejectedRemarksError" class="text-danger">Remarks are required</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" @click="rejected">Rejected
                    </button>
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
import { useRoute } from 'vue-router';
const route = useRoute();
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedPara = ref({}) // To store the clicked notice
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

// Called when clicking reject icon
const rejectedModal = (para) => {
    selectedPara.value = para;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};


const approveContent = async (id, index) => {
    try {
        const response = await axios.post('/approved_paragraph', { id, menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id,});
        if (response) {
            paragraphData.value[index].flag = 'A'; // update UI immediately
            fetchPageContent();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};
const rejected = async () => {
    if (!rejectedRemarks.value.trim()) {
        rejectedRemarksError.value = true;
        return;
    }

    rejectedRemarksError.value = false;
    try {
        const response = await axios.post('/api/rejected_paragraph', {
            id: selectedPara.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });

        if (response.data.success) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
            fetchPageContent();
            toastr.success('Paragraph rejected successfully');
        } else {
            alert('Failed to reject the Paragraph');
        }
    } catch (error) {
        console.error(error);
        alert('Something went wrong');
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
