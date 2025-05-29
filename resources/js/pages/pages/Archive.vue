<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Archive</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Archive</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Archive Data</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <table class="table center-aligned-table mb-0" id="archiveTable">
                                                <thead>
                                                    <tr class="text-dark">
                                                        <th>Title/Link</th>
                                                        <th>Archived On</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(news, index) in archiveData" :key="index">
                                                        <td>
                                                            <a v-if="news.type === 'file'"
                                                                :href="`/storage/${news.file}`" target="_blank"
                                                                class="text-primary">
                                                                {{ news.title }}
                                                            </a>
                                                            <a v-else-if="news.type === 'link'" :href="news.link"
                                                                target="_blank" class="text-primary">
                                                                {{ news.title }}
                                                            </a>
                                                            <span v-else>{{ news.title }}</span>
                                                        </td>
                                                        <td>{{ formatDate(news.created_at) }}</td>
                                                        <!-- <td>
                                                            <label
                                                                :class="news.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                                                                {{ news.flag === 'A' ? 'Approved' : 'Pending' }}
                                                            </label>
                                                        </td> -->
                                                        <!-- <td>
                                                            <i class="fas fa-trash-alt text-danger"
                                                                @click="deleteSlide(news.id)"></i>
                                                        </td> -->
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

        </div>
    </div>

</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import { useToastr } from '../../toaster.js';
const archiveData = ref();
const toastr = useToastr();
const getArchiveData = async () => {
    try {
        debugger;
        const response = await axios.get('/get_archivedata');
        if (response.data) {
            archiveData.value = response.data;
            await nextTick(); // Wait for DOM to render rows

            $('#archiveTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        }
    } catch (error) {
        toastr.error('Failed to fetch archive settings.');
    }
}

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
onMounted(() => {
    getArchiveData()
})

</script>
<style scoped></style>
