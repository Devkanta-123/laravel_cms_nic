<template>
    <br>
    <div>
        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">Cards </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <table class="table center-aligned-table mb-0" id="noticaboardTable">
                            <thead>
                                <tr class="text-dark">
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Added By</th>
                                    <th>Added On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cards, index) in cardaData" :key="index">
                                    <td> <img class="img-fluid avatar-small" :src="`/storage/${cards.image}`"
                                            alt="Slide Image" @click="openModal(`/storage/${cards.image}`)"
                                            style="cursor: pointer;" />
                                    </td>
                                    <td>{{ cards.card_title }}</td>
                                    <td>{{ cards.addedby }}</td>
                                    <td>{{ formatDate(cards.created_at) }}</td>
                                    <td>
                                        <label :class="cards.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                            {{ cards.flag === 'A' ? 'Approved' : 'Pending' }}
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

const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
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
        debugger;
        const response = await axios.get("/get_cards");
        cardaData.value = response.data.data;
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
        debugger;
        const response = await axios.put('/approved_cards', { id });
        if (response.data.success) {
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
