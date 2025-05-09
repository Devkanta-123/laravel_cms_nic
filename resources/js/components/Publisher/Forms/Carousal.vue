<template>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/Kfw5nqKx1pG2eU6R7tZLz1kcfk5iZ3Vf0dUSbNjs2a2g/Og" crossorigin="anonymous">
    <br>
    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <h5 class="card-title pb-0 border-0">Carousel</h5>
                <!-- action group -->
                <div class="table-responsive">
                    <table id="slidesTable" class="table center-aligned-table mb-0 display">
                        <thead>
                            <tr class="text-dark">
                                <th>#</th>
                                <th>Image</th>
                                <th>Added by</th>
                                <th>Added On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(slide, index) in slides" :key="slide.id">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <img class="img-fluid avatar-small" :src="`/storage/${slide.image}`"
                                        alt="Slide Image">
                                </td>
                                <td>{{ slide.addedby }}</td>
                                <td>{{ formatDate(slide.addedon) }}</td>
                                <td>
                                    <label :class="slide.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                        {{ slide.flag === 'A' ? 'Approved' : 'Pending' }}
                                    </label>
                                </td>
                                <td>
                                    <div class="checkbox checbox-switch switch-success">
                                        <label>
                                            <input type="checkbox" :checked="slide.flag === 'A'"
                                                :disabled="slide.flag === 'A'" @change="approveSlide(slide.id, index)">
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
</template>
<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../../toaster.js';
const toastr = useToastr();

const images = ref([]);
const isDragging = ref(false);
// fileInput.value = null;
const slides = ref([]);
const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};



const deleteDBImage = (slide, index) => {

    axios.post('/api/delete_slide', { id: slide.id })
        .then(response => {
            console.log('Slide deleted successfully:', response.data);
            toastr.success('Slide deleted successfully');
            slides.value.splice(index, 1);
        })
        .catch(error => {
            console.error('Error deleting slide:', error);
        });
};


const fetchSlides = async () => {
    try {
        const response = await axios.get('/get_carousel', {
            params: { flag: 4 }
        })
        slides.value = response.data

        await nextTick() // wait until DOM is updated
        if ($.fn.dataTable.isDataTable('#slidesTable')) {
            $('#slidesTable').DataTable().destroy()
        }
        $('#slidesTable').DataTable()
    } catch (error) {
        console.error('Failed to fetch slides:', error)
    }
}
const approveSlide = async (id, index) => {
    try {
        debugger;
        const response = await axios.post('/approve_carousel', { id });
        if (response.data.success) {
            slides.value[index].flag = 'A'; // update UI immediately
            fetchSlides();
            toastr.success('Approved successfully');
        }
    } catch (error) {
        console.error('Approval failed:', error);
    }
};


onMounted(() => {
    fetchSlides();
});

</script>
<style scoped>
@import '../assets/css/style.css';
</style>
