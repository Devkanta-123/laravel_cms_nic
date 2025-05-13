<template>
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
                                        <i class="fas fa-trash-alt text-danger" @click="deleteSlide(cards.id)"></i>
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
const props = defineProps({
    menuId: String,
    section: Number,
});
const toastr = useToastr();
const cardaData = ref();
const pagemenudata = ref([]); // Store fetched categories
const selectedImage = ref('');
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

const showModal = ref(false);
const modalImage = ref('');

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
    for (const key in newCard.value) {
        formData.append(key, newCard.value[key]);
    }
    formData.append("menu", props.menuId);
    formData.append("page_section", props.menuId);
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


onMounted(() => {
    getCards()
    getAllPageMenu()
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
