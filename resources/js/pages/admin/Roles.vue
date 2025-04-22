<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Roles</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <button type="button" class="btn btn-primary mb-2" @click="addUser">
                <i class="fa fa-plus-circle mr-1"></i>
                Add New Role
            </button>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>ROLE NAME</td>
                                <td>STATUS</td>
                                <td>EDIT</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(role, index) in roles" :key="role.id">
                                <td>
                                    {{ ++index }}
                                </td>
                                <td>
                                    {{ role.role_name }}
                                </td>
                                <td></td>
                                <td>
                                    <a href="#" @click.prevent="editRole(role)"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="userFormModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">

                        <span v-if="editing"> Edit Role</span>
                        <span v-else> Add New Role</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="createRoleschema" v-slot="{ errors }"
                    :initial-values="formValues">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="name"> Role Name</label>

                            <Field type="text" class="form-control" v-model="formValues.name" name="name" id="name"
                                aria-describedby="nameHelp" placeholder="Enter full name"
                                :class="{ 'is-invalid': errors.name }" />

                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
    <!-- END MODAL -->



</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import axios from 'axios';
import * as yup from 'yup';
import { Form, Field, useSetFieldError } from 'vee-validate';
import { useToastr } from '../../toaster.js';
import AdminItem from "./AdminItem.vue";


const toastr = useToastr();
const roles = ref([]);
const editing = ref(false);
const formValues = ref({ name: '', });

const form = ref(null);


const createRoleschema = yup.object({
    name: yup.string().required(),

});

const getRoles = () => {

    axios.get('/api/get_roles')
        .then((response) => {
            roles.value = response.data;
            console.log(roles.value);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

const handleSubmit = (values, actions) => {

    if (editing.value) {
        console.log("update");
        updateRole(values.actions);
    }
    else {
        console.log("create");
        createRole(values, actions);
    }
};



const createRole = (values, { resetForm, setErrors }) => {
    axios.post('/api/roles', values)
        .then((response) => {
            roles.value.unshift(response.data);
            resetForm();
            $('#userFormModal').modal('hide');
            toastr.success('Role created successfully');
        }).catch((error) => {
            if (error.response.data.errors)
                setErrors(error.response.data.errors);
        });
};

const updateRole = (values) => {
    console.log("UPDATE VALUES: " + formValues.value);
    axios.post('/api/update_roles/' + formValues.value.id, formValues.value)
        .then((response) => {
            const index = roles.value.findIndex(user => user.id === response.data.id);
            roles.value[index] = response.data;
            $('#userFormModal').modal('hide');
            toastr.success('Role updated successfully');
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

const editRole = (role) => {
    editing.value = true;
    // form.value.resetForm();
    console.log("ROle info" + role.name);
    formValues.value = {
        id: role.id,
        name: role.role_name,
    };
    $('#userFormModal').modal('show');

}

onMounted(() => {
    getRoles();

});
</script>