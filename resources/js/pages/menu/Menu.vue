<template>
    <!-- <Loader v-if="isLoading" /> -->
    <br>
    <div class="content ml-4 mr-4">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Menus</h4>
                        <span>All Menus</span>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="">
                        <li class="breadcrumb-item"><a href="">Menus</a></li>
                        <li class="breadcrumb-item active "><a class="text-primary">All Menus</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content ml-4 mr-4">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header row">
                    <div class="col-10">
                        <h4 class=" mt-1">Menu</h4>
                    </div>
                    <div class="col-2" align="right">
                        <button type="button" class="btn btn-primary mb-2 p-2" @click="addMenu">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Add New Menu
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="menuTable" class="table table-responsive-lg mb-0">
                        <thead>
                            <tr>
                                <th><strong>S.NO.</strong></th>
                                <th><strong>MENU NAME</strong></th>
                                <th><strong>MENU TYPE</strong></th>
                                <th><strong>ORDER</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(menu, index) in menus" :key="menu.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ menu.menu_name }}</td>
                                <td>
                                    {{ menu.menu_master.menu_name }}
                                    <span v-if="menu.parent_name">-> {{ menu.parent_name }}</span>
                                </td>
                                <td>{{ menu.order }}</td>
                                <td>
                                    <a href="#" @click.prevent="editMenu(menu)"
                                        class="btn btn-primary shadow btn-xs me-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="#" @click.prevent="confirmMenuDeletion(menu)"
                                        class="btn btn-danger shadow btn-xs sharp"
                                        v-if="menu.id != 1 && menu.id != 2 && menu.id != 3">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

    <!--Add EDIT  MODAL -->
    <div class="modal fade" id="menuFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="menuFormModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">

                        <span v-if="editing"> Edit Menu</span>
                        <span v-else> Add New Menu</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="currentSchema" v-slot="{ errors }"
                    :initial-values="formValues">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="menu_name">Name</label>

                            <Field type="text" class="form-control" v-model="formValues.menu_name" name="menu_name"
                                id="menu_name" aria-describedby="menu_nameHelp" placeholder="Enter full menu_name"
                                :class="{ 'is-invalid': errors.menu_name }"
                                :readonly="(formValues.id === 1) || (formValues.id === 2) || (formValues.id === 3) ? true : false" />

                            <span class="invalid-feedback">{{ errors.menu_name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="hindi_name">Hindi Name</label>

                            <Field type="text" class="form-control" v-model="formValues.hindi_name" name="hindi_name"
                                id="hindi_name" aria-describedby="hindi_nameHelp" placeholder="Enter full hindi_name" />
                        </div>

                        <div class="form-group">
                            <label for="khasi_name">Khasi Name</label>

                            <Field type="text" class="form-control" v-model="formValues.khasi_name" name="khasi_name"
                                id="khasi_name" aria-describedby="khasi_nameHelp" placeholder="Enter full khasi_name" />

                            <span class="invalid-feedback">{{ errors.khasi_name }}</span>
                        </div>



                        <div class="form-group">
                            <label for="menu_type">Menu Type</label>
                            <Field as="select" class="form-control" v-model="formValues.menu_type" name="menu_type"
                                id="menu_type" :class="{ 'is-invalid': errors.menu_type }"
                                :disabled="(formValues.id === 1) || (formValues.id === 2) || (formValues.id === 3) ? true : false">
                                <option value="" disabled>Select Menu Type</option>
                                <option v-for="menuType in menuTypes" :key="menuType.id" :value="menuType.id">{{
                                    menuType.menu_name }}</option>
                            </Field>
                            <span class="invalid-feedback">{{ errors.menu_type }}</span>
                        </div>

                        <div class="form-group">
                            <label for="order">Order</label>

                            <Field type="number" class="form-control" v-model="formValues.order" name="order" id="order"
                                aria-describedby="orderHelp" placeholder="Enter menu order" min="1"
                                :class="{ 'is-invalid': errors.order }" />
                            <span class="invalid-feedback">{{ errors.order }}</span>
                        </div>

                        <div class="form-group">
                            <label for="parent">Parent Menu </label>



                            <Field as="select" class="form-control" v-model="formValues.parent" name="parent"
                                id="parent" :class="{ 'is-invalid': errors.parent }">
                                <option value="" disabled>Select Parent Menu</option>
                                <option value="0">Root</option>
                                <template v-for="menu in sub_menus" :key="menu.id">
                                    <option :value="menu.id">{{ menu.menu_name }}</option>
                                    <template v-if="menu.submenus && menu.submenus.length > 0">
                                        <template v-for="subMenu in menu.submenus" :key="subMenu.id">
                                            <option :value="subMenu.id">{{ '&nbsp;&nbsp;&nbsp;&nbsp;> ' +
                                                subMenu.menu_name }}</option>
                                            <template v-if="subMenu.submenus && subMenu.submenus.length > 0">
                                                <template v-for="thirdMenu in subMenu.submenus" :key="thirdMenu.id">
                                                    <option :value="thirdMenu.id">{{
                                                        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;> ' +
                                                        thirdMenu.menu_name }}</option>
                                                </template>
                                            </template>
                                        </template>
                                    </template>
                                </template>
                            </Field>
                            <span class="invalid-feedback">{{ errors.parent }}</span>
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

    <!-- DeleteModal -->
    <div class="modal fade" id="deleteMenuModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="deleteMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Delete Menu
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5>Are you sure you want to delete this user?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                    <button type="button" @click.prevent="deleteMenu" class="btn btn-primary">Delete Menu</button>
                </div>

            </div>
        </div>
    </div>

</template>
<script setup>
import { ref, onMounted, computed,nextTick } from "vue";
import axios from 'axios';
import * as yup from 'yup';
import { Form, Field, useSetFieldError } from 'vee-validate';
import { useToastr } from '../../toaster.js';
import Swal from 'sweetalert2';
import Loader from '../../components/Loader.vue';

const toastr = useToastr();
const menus = ref([]);
const sub_menus = ref([]);
const menuTypes = ref([]);
const editing = ref(false);
const formValues = ref({ menu_name: '', menu_type: '', id: '', });
const menuIdBeingDeleted = ref(null);
const form = ref(null);
const isLoading = ref(true);

const createMenuschema = yup.object({
    menu_name: yup.string().required(),
    menu_type: yup.string().required(),
    order: yup.number().required(),
    parent: yup.string().required(),
    hindi_name: yup.string(),
    khasi_name: yup.string(),

});

const editMenuschema = yup.object({
    menu_name: yup.string().required(),
    menu_type: yup.string().required(),
    order: yup.number().required(),
    parent: yup.string().required(),
    hindi_name: yup.string(),
    khasi_name: yup.string(),
});

const currentSchema = computed(() => editing.value ? editMenuschema : createMenuschema);
const getMenus = async () => {
    try {
        // Destroy old DataTable before data changes
        if ($.fn.DataTable.isDataTable('#menuTable')) {
            $('#menuTable').DataTable().destroy()
        }

        const response = await axios.get('/api/get_menus')
        menus.value = response.data[0]
        sub_menus.value = response.data[1]

        // Wait for the table DOM to update
        await nextTick()

        // Initialize DataTable again
        $('#menuTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true
        })

    } catch (error) {
        console.error('Error loading menus:', error)
    }
}

const fetchMenuTypes = () => {
    axios.get('/api/menu_types')
        .then(response => {
            menuTypes.value = response.data;
        })
        .catch(error => {
            console.error('Error fetching menu types:', error);
        });
};


const filteredMenus = computed(() => {
    if (!formValues.value.id) {
        return menus.value;
    } else {
        return menus.value.filter(menu => menu.id !== formValues.value.id);

    }
});


const addMenu = () => {
    editing.value = false;
    form.value.resetForm();
    $('#menuFormModal').modal('show');
}

const editUser = (menu) => {
    editing.value = true;

    console.log("Menu info" + menu.menu_name);
    formValues.value = {
        id: menu.id,
        menu_name: menu.menu_name,
        menu_type: menu.menu_master.id
    };
    $('#menuFormModal').modal('show');

}

const handleSubmit = (values, actions) => {
    debugger;
    if (editing.value) {
        console.log("update");
        updateMenu(values.actions);
    }
    else {
        console.log("create");
        createMenu(values, actions);
    }
};


const createMenu = async (values, { resetForm, setErrors }) => {
    try {
        const response = await axios.post('/api/menus', values)
        console.log("Response after saving:", response.data)
        // Await the refresh to ensure DataTables is updated cleanly
        await getMenus()
        resetForm()
        $('#menuFormModal').modal('hide')
        toastr.success('Menu added successfully')
    } catch (error) {
        console.log("Errors:", error.response?.data)
        if (error.response?.data?.errors) {
            setErrors(error.response.data.errors)
        }
    }
}


const editMenu = (menu) => {
    editing.value = true;
    // form.value.resetForm();
    console.log("Menu info" + menu.id + menu.menu_master.menu_name);
    formValues.value = {
        id: menu.id,
        menu_name: menu.menu_name,
        hindi_name: menu.hindi_name ?? 'N/A',
        khasi_name: menu.khasi_name ?? 'N/A',
        menu_type: menu.menu_master.id,
        order: menu.order,
        parent: menu.parent
    };
    $('#menuFormModal').modal('show');

}


const updateMenu = async (values) => {
    try {
        const response = await axios.post('/api/update_menu/' + formValues.value.id, formValues.value)
        await getMenus() // This will reinit DataTable after DOM update
        $('#menuFormModal').modal('hide')
        toastr.success('Menu updated successfully')
    } catch (error) {
        console.log(error)
    } finally {
        form.value.resetForm()
    }
}

const confirmMenuDeletion = (menu) => {
    menuIdBeingDeleted.value = menu.id;
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('/api/delete_menu/' + menuIdBeingDeleted.value)
                .then(() => {
                    getMenus();
                    // menus.value = menus.value.filter(menu => menu.id !== menuIdBeingDeleted.value);

                    Swal.fire({
                        title: "Deleted!",
                        text: "Menu has been deleted.",
                        icon: "success"
                    });
                })

        }
    });
    // $('#deleteMenuModal').modal('show');
}

// const deleteMenu = (menu) => {
//     axios.post('/api/delete_menu/' + menuIdBeingDeleted.value)
//         .then(() => {
//             $('#deleteMenuModal').modal('hide');
//             menus.value = menus.value.filter(menu => menu.id !== menuIdBeingDeleted.value);
//             // sub_menus.value = sub_menus.value.filter(menu => menu.id !== menuIdBeingDeleted.value);
//             toastr.success("Menu Deleted Successfully");
//             // emit('menuDeleted', userIdBeingDeleted.value);
//         })
// }



onMounted(() => {
    setTimeout(() => {
        isLoading.value = false;
    }, 500);
    getMenus();
    fetchMenuTypes();

});

</script>
<style scoped>
.table th:last-child,
.table td:last-child {
    width: 150px;
    /* Set a fixed width for the last column */

}
</style>