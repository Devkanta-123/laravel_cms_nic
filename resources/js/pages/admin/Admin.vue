
<template>
        <div class="content-header">
             <div class="container-fluid">
                 <div class="row mb-2">
                     <div class="col-sm-6">
                         <h1 class="m-0">Admin</h1>
                     </div>
                     <div class="col-sm-6">
                         <ol class="breadcrumb float-sm-right">
                             <li class="breadcrumb-item"><a href="#">Home</a></li>
                             <li class="breadcrumb-item active">Admin</li>
                         </ol>
                     </div>
                 </div>
             </div>
         </div>

         <div class="content">
             <div class="container-fluid">              
                <button type="button" class="btn btn-primary mb-2" @click="addUser">
                    <i class="fa fa-plus-circle mr-1"></i>
                   Add New User
                </button>

                <div class="card">
                    <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Role</td>
                                <td>Enable/Disable</td>
                                <!-- <td>Role</td> -->
                                <td>Options</td>
                            </tr>
                        </thead>
                        <tbody>
                            <AdminItem v-for="(user, index) in users" 
                                :key="user.id" 
                                :user=user 
                                :roles="roles"
                                :index=index  
                                @edit-user ="editUser"
                                @user-deleted="userDeleted" 
                            />
                    </tbody>
                    </table>
                 </div>
                </div>
             </div>
         </div> 

         <!-- MODAL -->
         <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="userFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">

                            <span v-if="editing">  Edit User</span>
                            <span v-else> Add New User</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <Form ref="form" @submit="handleSubmit" :validation-schema="currentSchema" v-slot="{ errors }" :initial-values="formValues">
                        <div class="modal-body">
                            
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    
                                    <Field type="text" class="form-control" v-model="formValues.name"  name="name" id="name" aria-describedby="nameHelp" placeholder="Enter full name" :class="{ 'is-invalid': errors.name }" />
                                    
                                    <span class="invalid-feedback">{{ errors.name }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="roles">Roles</label>
                                    <select v-model="formValues.roles" class="form-control" >
                                        <option value="" selected>--Select a role --</option>
                                        <option v-for="role in availableRoles" :key="role.id" :value="role.id">{{ role.role_name }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <Field  type="email" class="form-control " name="email" id="email"  v-model="formValues.email" aria-describedby="nameHelp" placeholder="Enter email" :class="{'is-invalid': errors.email}"  />
                                    <span class="invalid-feedback">{{ errors.email }}</span>
                                </div>
                           

                            <div class="form-group">
                                <label for="email">Password</label>
                                <Field type="password" class="form-control " name="password"  v-model="formValues.password"  id="password" aria-describedby="nameHelp" placeholder="Enter password" :class="{'is-invalid': errors.password}" />
                                <span class="invalid-feedback">{{ errors.password }}</span>
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
        <!-- END MODAL -->

      

</template>
<script setup>
import { ref, onMounted, computed  } from "vue";
import axios from 'axios';
import * as yup from 'yup';
import {Form, Field, useSetFieldError} from 'vee-validate';
import { useToastr } from '../../toaster.js';
import AdminItem from "./AdminItem.vue";


    const toastr = useToastr();
    const users = ref([]);
    const availableRoles = ref([]);
    const editing = ref(false);
    const formValues = ref({ name: '', email: '', id: '', password:'', roles: [] });

    const form = ref(null);
   

    const createUserschema = yup.object({
        name: yup.string().required(),
        email : yup.string().email().required(),
        password: yup.string().required().min(8),
        roles: yup.array().required()
    });

    const editUserschema = yup.object({
        name: yup.string().required(),
        email : yup.string().email().required(),
    });

    const currentSchema = computed(() => editing.value ? editUserschema : createUserschema);

    const getUsers = () =>{
        axios.get('/api/get_users')  
        .then((response) => {
            users.value = response.data;
            console.log('USERS');
            console.log(users.value);
        })  
        .catch((error) => {
          console.error('Error:', error);
        });
    }

    const getRoles = () => {
        axios.get('/api/get_roles')  
        .then((response) => {
            availableRoles.value = response.data;
            console.log('ROLES');
            console.log(availableRoles.value);
        })  
        .catch((error) => {
        console.error('Error:', error);
        });
    };
    
    const handleSubmit = (values, actions) => {
        
        if (editing.value) {
            console.log("update");
             updateUser(values. actions);
        }
        else{
            console.log("create");
             createUser(values, actions);
        }
     };

   

    const createUser = (values, {resetForm, setErrors}) => {

            // axios.post('http://10.179.2.187:8000/api/users', values)
            axios.post('/api/users', values)
                   .then((response) => {
                        users.value.unshift(response.data);
                        resetForm();
                        $('#userFormModal').modal('hide');
                        toastr.success('User created successfully');
                    }).catch((error) => {
                        if(error.response.data.errors)
                            setErrors(error.response.data.errors);
                    });
    };

    const updateUser = (values) =>{
        axios.post('/api/update_users/'+ formValues.value.id, formValues.value)
        .then((response) => {
            const index = users.value.findIndex(user =>user.id === response.data.id);
            users.value[index] = response.data;
            $('#userFormModal').modal('hide');
            toastr.success('User updated successfully');

        }).catch((errors) => {
            console.log(errors);
        })
        .finally(() => {
            form.value.resetForm();
            
        });
    }

    const addUser = () => {
        editing.value = false;
        form.value.resetForm();
        $('#userFormModal').modal('show');
    }

    const editUser = (user) => {
        editing.value = true;
        console.log("User info"+ user.name);
        formValues.value ={
            id: user.id,
            name : user.name,
            email : user.email,
            roles: user.role_id
        };
        $('#userFormModal').modal('show');
        
    }

    const userDeleted = (userId) =>{
        users.value = users.value.filter(user => user.id !== userId);
    }
    
    onMounted(() => {
        getUsers();
        getRoles();
    });
</script>