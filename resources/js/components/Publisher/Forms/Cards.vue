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
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Cards </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <div class="fc-toolbar fc-header-toolbar">
                            <div class="fc-right mb-3">
                                <div class="fc-button-group">
                                    <button type="button" :class="[
                                        'fc-month-button fc-button fc-state-default fc-corner-left',
                                        activeFlag === 'ALL' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('ALL')">All</button>

                                    <button type="button" :class="[
                                        'fc-month-button fc-button fc-state-default fc-corner-left',
                                        activeFlag === 'A' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('A')">Approved</button>

                                    <button type="button" :class="[
                                        'fc-agendaWeek-button fc-button fc-state-default',
                                        activeFlag === 'R' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('R')">Rejected</button>

                                    <button type="button" :class="[
                                        'fc-agendaDay-button fc-button fc-state-default fc-corner-right',
                                        activeFlag === 'PENDING' ? 'fc-state-active' : ''
                                    ]" @click="filterByFlag('PENDING')">Pending</button>

                                </div>
                            </div>
                        </div>
                        <table class="table center-aligned-table mb-0" id="noticaboardTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>SL.NO</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Added By</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cards, index) in filteredCardData" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td> <img class="img-fluid avatar-small" :src="`/storage/${cards.image}`"
                                            alt="Slide Image" @click="openModal(`/storage/${cards.image}`)"
                                            style="cursor: pointer;" />
                                    </td>
                                    <td>{{ cards.card_title }}</td>
                                    <td>{{ cards.addedby }}</td>
                                    <td>{{ formatDate(cards.created_at) }}</td>
                                    <td>
                                        <label v-if="cards.flag === 'A'" class="badge bg-success">
                                            Approved
                                        </label>
                                        <label v-else-if="cards.flag === 'U'" class="badge bg-primary">
                                            Updated
                                        </label>
                                        <div v-else-if="cards.flag === 'R'">
                                            <label class="badge bg-danger">
                                                Rejected
                                            </label>
                                            <div class="mt-1 text-muted">
                                                Remarks: {{ cards.rejected_remarks }}
                                            </div>
                                        </div>

                                        <label v-else class="badge bg-warning">
                                            Pending
                                        </label>
                                    </td>
                                    <td>
                                        <!-- <i class="fas fa-trash-alt text-danger" @click="deleteSlide(cards.id)"></i> -->
                                        <div class="checkbox checbox-switch switch-success">
                                            <label>
                                                <input type="checkbox" :checked="cards.flag === 'A'"
                                                    :disabled="cards.flag === 'A'"
                                                    @change="approveCards(cards.id, index)">
                                                <span></span>
                                            </label>
                                            &nbsp;&nbsp;
                                            <!-- Show icon only if flag is not 'A' -->
                                            <i class="fas fa-times text-danger"
                                                v-if="cards.flag !== 'A' && cards.flag !== 'R'" data-toggle="modal"
                                                data-target="#rejectedModal" @click="rejectedModal(cards)"></i>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Modal -->
                        <div class="modal fade" id="viewImage" tabindex="-1" role="dialog" aria-hidden="true"
                            :class="{ show: showModal }" :style="{ display: showModal ? 'block' : 'none' }">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header align-items-start">
                                        <div class="modal-title">
                                            <div class="mb-30">
                                                <div class="blog-box blog-2">
                                                    <img class="img-fluid w-100" :src="modalImage" alt="Modal Image" />
                                                    <div class="blog-info pt-10"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" @click="closeModal"></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="rejectedModal" tabindex="-1" role="dialog"
                            aria-labelledby="rejectedModalTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Rejected Remarks <span class="text-danger">*</span></label>
                                            <textarea v-model="rejectedRemarks" class="form-control"
                                                placeholder="Remarks...."></textarea>
                                            <span v-if="rejectedRemarksError" class="text-danger">Remarks are
                                                required</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-danger" @click="rejected">Rejected
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
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
const cardaData = ref();
const showModal = ref(false);
const modalImage = ref('');
const rejectedRemarks = ref('');
const rejectedRemarksError = ref(false);
const selectedCard = ref({})
import { useRoute, useRouter } from 'vue-router';
const route = useRoute();
const router = useRouter();
const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};
const filteredCardData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'

const rejectedModal = (card) => {
    selectedCard.value = card;
    rejectedRemarks.value = '';
    rejectedRemarksError.value = false;
};
// const onBack = () => {
//     router.push('/publisher/pages-form/1/Home/0')
// }


const onBack = () => {
    if (window.history.length > 1) {
        router.back();
    } else {
        router.push('/publisher/pages-form/1/Home/0')
    }
};

const rejected = async () => {
    if (!rejectedRemarks.value.trim()) {
        rejectedRemarksError.value = true;
        return;
    }
    rejectedRemarksError.value = false;
    try {
        const response = await axios.post('/rejected_card', {
            id: selectedCard.value.id,
            remarks: rejectedRemarks.value,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response) {
            $('#rejectedModal').modal('hide');
            // Optionally reload or update the UI
            getCards();
            toastr.success('Card has been rejected ');
        } else {
            alert('Failed to reject the notice');
            toastr.error('Failed to reject the  carousel');

        }
    } catch (error) {
        console.error(error);
        alert('Something went wrong');
    }
};

// Filter handler
const filterByFlag = async (flag) => {
    activeFlag.value = flag; // Update active button state

    // Destroy existing DataTable
    if ($.fn.dataTable.isDataTable('#latestNewsTable')) {
        $('#latestNewsTable').DataTable().destroy();
    }

    // Filter logic
    if (flag === 'ALL') {
        filteredCardData.value = [...cardaData.value];
    } else if (flag === 'PENDING') {
        // Both 'U' (Unapproved) and 'N' (New) are considered pending
        filteredCardData.value = cardaData.value.filter(
            item => item.flag === 'U' || item.flag === 'N'
        );
    } else {
        filteredCardData.value = cardaData.value.filter(item => item.flag === flag);
    }

    await nextTick();
    initDataTable();

};


const initDataTable = () => {
    // Destroy if already exists
    if ($.fn.dataTable.isDataTable('#latestNewsTable')) {
        $('#latestNewsTable').DataTable().destroy();
    }

    nextTick(() => {
        $('#latestNewsTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
};

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};



const getCards = async () => {
    try {
        const response = await axios.get("/get_cards");
        cardaData.value = response.data.data;
        filteredCardData.value = response.data.data;
        await nextTick(); // Wait for DOM to update
        // Destroy and reinitialize DataTable
        if ($.fn.dataTable.isDataTable('#noticaboardTable')) {
            $('#noticaboardTable').DataTable().destroy();
        }
        $('#noticaboardTable').DataTable({
            responsive: true,
            pageLength: 10,
        });

        console.log('Latest News data', response.data);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const approveCards = async (id, index) => {
    try {
        const response = await axios.put('/approved_cards', {
            id,
            menu_id: route.params.menuId,
            page_section_master_id: route.params.page_section_id
        });
        if (response) {
            cardaData.value[index].flag = 'A'; // update UI immediately
            getCards();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};

onMounted(() => {
    getCards()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
