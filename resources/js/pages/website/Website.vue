<template>
     <Loader v-if="isLoading" />
        <div class="content-header">
             <div class="container-fluid">
                 <div class="row mb-2">
                     <div class="col-sm-6">
                         <h1 class="m-0">Admin</h1>
                     </div>
                     <div class="col-sm-6">
                         <ol class="breadcrumb float-sm-right">
                             <li class="breadcrumb-item"><a href="#">Home</a></li>
                             <li class="breadcrumb-item active">Website</li>
                         </ol>
                     </div>
                 </div>
             </div>
        </div>
        <div class="content">
             <div class="container-fluid">      
               
                <button type="button" class="btn btn-primary mb-2" @click="openModal">
                    <i class="fa fa-plus-circle mr-1"></i>
                   Add Website Details
                </button>
                <div class="card">
                    <div class="card-body">
                           
                <table class="table table-bordered">
                <thead>
                    <tr>
                    <th>Name</th>
                    <th>Short Form</th>
                    <th>Logo</th>
                    <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="website in websites" :key="website.id">
                    <td>{{ website.title }}</td>
                    <td>{{ website.website_short }}</td>
                    <td>{{ website.logo }}</td>
                    <td>  <a href="#" @click.prevent="openEditModal(website)"><i class="fa fa-edit"></i></a></td>
                    </tr>
                </tbody>
                </table>
                    </div>
                </div>


    <div class="modal fade"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="userFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">

                            <span v-if="modalOpen">  Edit Website</span>
                            <span v-else> Add Website</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <Form ref="form" @submit="handleSubmit" :validation-schema="currentSchema" v-slot="{ errors }" :initial-values="editedWebsite">
                        <div class="modal-body">
                            
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    
                                    <Field type="text" class="form-control" v-model="editedWebsite.title"  name="name" id="name" aria-describedby="nameHelp" placeholder="Enter full name" :class="{ 'is-invalid': errors.name }" />
                                    
                                    <span class="invalid-feedback">{{ errors.name }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="short_form">Short Form</label>
                                    <Field  type="short_form" class="form-control " name="short_form" id="short_form"  v-model="editedWebsite.website_short" aria-describedby="nameHelp" placeholder="Enter short_form" :class="{'is-invalid': errors.website_short}"  />
                                    <span class="invalid-feedback">{{ errors.website_short }}</span>
                                </div>
                           

                            <div class="form-group">
                                <label for="logo">Logo</label>
                                <Field type="text" class="form-control " name="logo"  v-model="editedWebsite.logo"  id="logo" aria-describedby="nameHelp" placeholder="Enter logo" :class="{'is-invalid': errors.logo}" />
                                <span class="invalid-feedback">{{ errors.logo }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" >Save</button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>



        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import {Form, Field, useSetFieldError} from 'vee-validate';
import Loader from '../../components/Loader.vue';

const websites = ref([]);
const modalOpen = ref(false);
const editedWebsite = ref({});
const isLoading = ref(true);

const openEditModal = (website) => {
  
  editedWebsite.value = { ...website }; // Clone the website object
  modalOpen.value = true;
  $('.modal').modal('show');
};

const closeModal = () => {
  modalOpen.value = false;
};

const saveChanges = () => {
  // Code to save changes to the edited website
};

onMounted(async () => {
    setTimeout(() => {
    isLoading.value = false; 
  }, 500); 
  
  try {
    const response = await axios.get('/get_website_details');
    websites.value = response.data;
 
  } catch (error) {
    console.error('Failed to fetch website details:', error);
  }
});
</script>