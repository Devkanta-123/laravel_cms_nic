<template>
    <br />
    <br />
    <br />
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
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Paragraph</h5>
                    <div class="table-responsive">
                           <div class="fc-toolbar fc-header-toolbar">
                            <div class="fc-right mb-3">
                               
                            </div>
                        </div>
                        <table class="table center-aligned-table mb-0" id="paragraphTable"
                            v-if="paragraphData.length > 0">
                            <thead>
                                <tr class="text-dark">
                                    <th>New Content</th>
                                    <th>Added On</th>
                                    <th>Added By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(para, index) in paragraphData" :key="para.id">
                                    <!--  Content Column -->
                                    <td>
                                        <!--  Preview shown only when not expanded -->
                                        <div v-if="expandedIndex !== index">
                                            <div v-html="para.content.slice(0, 100) + ' .......' "></div>
                                        </div>

                                        <!--  Full content shown only when expanded -->
                                        <div class="mt-2">
                                            <button class="badge bg-primary" @click="toggleContent(index)">
                                                {{ expandedIndex === index ? 'Show Less' : 'Read More' }}
                                            </button>
                                        </div>

                                        <div v-if="expandedIndex === index" class="mt-2 border p-2 bg-light">
                                            <div v-html="para.content"></div>
                                        </div>
                                    </td>

                                    <!-- Date -->
                                    <td>{{ formatDate(para.created_at) }}</td>

                                    <!-- Added By -->
                                    <td>{{ para.addedby || 'N/A' }}</td>

                                    <!-- Status -->
                                    <td>
                                        <label v-if="para.flag === 'A'" class="badge bg-success">Approved</label>
                                        <label v-else-if="para.flag === 'U'" class="badge bg-warning">Pending</label>
                                        <div v-else-if="para.flag === 'R'">
                                            <label class="badge bg-danger">Rejected</label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ para.rejected_remarks }}
                                            </div>
                                        </div>
                                        <label v-else class="badge bg-warning">Pending</label>
                                    </td>

                                    <!-- Action -->
                                    <td>
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="para.flag === 'A'"
                                                    :disabled="para.flag === 'A'"
                                                    @change="approveContent(para.id, index)" />
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

                        <p v-else class="text-muted p-3">No content found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-danger" @click="rejected">
                        Reject
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useRoute,useRouter } from 'vue-router';
import { useToastr } from '../../../toaster.js';

const route = useRoute();
const router = useRouter();
const toastr = useToastr();

const paragraphData = ref([]);
const expandedIndex = ref(null);
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedPara = ref({});

const stripHtml = (html) => {
    if (!html) return '';
    return html.replace(/<[^>]+>/g, '');
};

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
	const onBack = () => {
    if (window.history.length > 1) {
        router.back();
    } else {
        router.push('/publisher/pages-form/1/Home/0')
    }
}
	
const fetchPageContent = async () => {
    try {
        const response = await axios.get(`/get_page_content/${route.params.menuId}`);
        if (response.data) {
            paragraphData.value = [response.data]; // wrap in array
            await nextTick();
            if ($.fn.dataTable.isDataTable('#paragraphTable')) {
                $('#paragraphTable').DataTable().destroy();
            }
            $('#paragraphTable').DataTable({
                responsive: true,
                pageLength: 10,
            });
        } else {
            paragraphData.value = [];
        }
    } catch (error) {
        console.error('Error fetching page content:', error);
    }
};



const toggleContent = (index) => {
    expandedIndex.value = expandedIndex.value === index ? null : index;
};

const approveContent = async (id, index) => {
    try {
        const response = await axios.post('/approved_paragraph', {
            id,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id,
        });
        if (response) {
            paragraphData.value[index].flag = 'A';
            fetchPageContent();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};

const rejectedModal = (para) => {
    selectedPara.value = para;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
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
            page_section_master_id: route.params.page_section_id,
        });

        if (response.data.success) {
            $('#rejectedModal').modal('hide');
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
    fetchPageContent();
});
</script>

<style scoped>
@import '../assets/css/style.css';
</style>
