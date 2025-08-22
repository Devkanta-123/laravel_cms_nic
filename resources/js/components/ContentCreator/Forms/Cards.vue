<template>
    <br>  <br>
  <br>
    <div>
        <div class="col-xl-12 mb-30">
            <!-- First Card (Carousel) -->
            <div class="card card-statistics h-100">
                <form @submit.prevent="addCard" enctype="multipart/form-data">
                    <div class="card-body">
                        <h5 class="card-title">Cards</h5>
                        <div id="example-basic" role="application" class="wizard clearfix">
                            <div class="content clearfix">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">English Card Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" v-model="newCard.card_title" class="form-control"
                                            placeholder="Enter Card Title">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">English Card Description</label>
                                        <textarea v-model="newCard.card_description" class="form-control" rows="2"
                                            placeholder="Enter Card Description"></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Card Logo</label>
                                        <input type="file" @change="handleFileChange" class="form-control"
                                            accept="image/*">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Link to another page</label>
                                        <select class="form-select my-1 me-sm-2" v-model="newCard.more_link">
                                            <option value="" disabled selected>Select the page menu</option>
                                            <option v-for="pagemenu in pagemenudata" :key="pagemenu.id"
                                                :value="pagemenu.id">
                                                {{ pagemenu.menu_name }}
                                            </option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row">
                                    <p>More Languages</p>
                                    <div class="col-md-3">
                                        <label class="form-label">Hindi Title</label>
                                        <input type="text" v-model="newCard.hindi_title" class="form-control"
                                            placeholder="Hindi Title">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Hindi Description</label>
                                        <textarea v-model="newCard.hindi_description" class="form-control mt-2" rows="2"
                                            placeholder="Hindi Description"></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Khasi Title</label>
                                        <input type="text" v-model="newCard.khasi_title" class="form-control"
                                            placeholder="Khasi Title">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Khasi Description</label>
                                        <textarea v-model="newCard.khasi_description" class="form-control mt-2" rows="2"
                                            placeholder="Khasi Description"></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label my-1 me-2" for="inlineFormSelectPref">Publisher <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select my-1 me-sm-2" v-model="selectedPublisher">
                                        <option value="" disabled>Select the Publisher</option>
                                        <option v-for="publisher in publisherData" :key="publisher.id"
                                            :value="publisher.id">
                                            {{ publisher.name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="actions clearfix mt-3">
                                    <ul role="menu" aria-label="Pagination">
                                        <li>
                                            <button type="submit" class="btn btn-success" role="menuitem">
                                                Save
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <div class="col-xl-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title pb-0 border-0">List </h5>
                    <!-- action group -->
                    <div class="table-responsive">
                        <div class="fc-toolbar fc-header-toolbar">
                            <div class="fc-right mb-3">
                                <div class="fc-button-group">
                                    <button type="button"
                                        class="fc-month-button fc-button fc-state-default fc-corner-left fc-state-active"
                                        @click="onBack()"> Back</button>
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
                                        <i class="fas fa-trash-alt text-danger" v-if="cards.flag !== 'A'"
                                            @click="deleteCard(cards.id)"></i>
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
import { useToastr} from '../../../toaster.js';
const props = defineProps({
    menuId: String,
    section: Number,
});
const router = useRouter();
import { useRoute,useRouter} from 'vue-router';
import Swal from 'sweetalert2';
const route = useRoute();
const selectedPublisher = ref("");
const publisherData = ref([]); // Store publisher categories
const toastr = useToastr();
const cardaData = ref();
const pagemenudata = ref([]); // Store fetched categories
const selectedImage = ref('');
const filteredCardData = ref([]);
const activeFlag = ref('ALL'); // Default to 'ALL'
const newCard = ref({
    card_title: '',
    card_description: '',
    image: null,
    more_link: '',
    ex_link: '',
    order: '',
    hindi_title: '',
    khasi_title: '',
    other_title: '',
    hindi_description: '',
    khasi_description: '',
    other_description: ''
});
const handleFileChange = (event) => {
    newCard.value.image = event.target.files[0];
};
const onBack = () => {
    router.push('/contentcreator/pages-form/1/Home')
}
const showModal = ref(false);
const modalImage = ref('');
const getAllPublisher = async () => {
    try {
        const response = await axios.get('/api/get_allpublisher');
        publisherData.value = response.data.data;
        // Auto-select if only one publisher exists
        if (publisherData.value.length === 1) {
            selectedPublisher.value = publisherData.value[0].id;
        }
    } catch (error) {
        console.error('Error fetching publishers:', error.response || error);
        toastr.error("Failed to load publishers.");
    }
};

const openModal = (imageSrc) => {
    modalImage.value = imageSrc;
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};

const addCard = () => {
    const formData = new FormData();
    if (!newCard.value.card_title.trim()) {
        toastr.error('Card title is required');
        return;
    }
    if (!newCard.value.card_description.trim()) {
        toastr.error('Card description is required');
        return;
    }
    if (!newCard.value.more_link) {
        toastr.error('Please select a page link');
        return;
    }
    if (!selectedPublisher.value) {
        toastr.error("Please select a publisher.");
        return false;
    }
    for (const key in newCard.value) {
        formData.append(key, newCard.value[key]);
    }
    formData.append("menu", route.params.menuId);
    formData.append("page_section", route.params.page_section_id);
    formData.append("publisher_id", selectedPublisher.value);
    console.log(formData);
    try {
        axios.post('/api/save_card', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
            .then(response => {
                toastr.success(response.data.message);
            })
            .catch(error => {
                toastr.error('An error occurred while saving the card.');
            });

        newCard.value = {
            card_title: '',
            card_description: '',
            image: null,
            more_link: '',
            ex_link: '',
            order: '',
            hindi_title: '',
            khasi_title: '',
            other_title: '',
            hindi_description: '',
            khasi_description: '',
            other_description: ''
        };
    } catch (error) {
        toastr.error('Error adding card.');
    }
};



const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const getAllPageMenu = async () => {
    try {
        const response = await axios.get('/get_allpagemenu');
        pagemenudata.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error.response || error);
        toastr.error("Failed to load categories.");
    }
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


const deleteCard = async (id) => {
    const result = await Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this card? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await axios.post('/api/deleted_card', {
                id: id,
                menu_id: route.params.menuId,
                page_section_master_id: route.params.page_section_id
            });
            await nextTick();
            await getCards();
            Swal.fire('Deleted!', response.data.message || 'Card has been deleted.', 'success');
        } catch (error) {
            console.error('Error deleting card:', error);
            Swal.fire('Error!', 'An error occurred during deletion.', 'error');
        }
    }
};


onMounted(() => {
    getCards()
    getAllPageMenu()
    getAllPublisher()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
