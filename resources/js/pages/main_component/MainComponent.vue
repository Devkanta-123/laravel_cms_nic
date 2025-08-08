<template>
    <br>
    <div class="content ml-4 mr-4">
        <div class="container-fluid ">
            <div class="row page-titles mx-0 mb-3">
                <div class="col-sm-6 p-0">
                    <div class="welcome-text">
                        <h4 class="text-primary">Main Components</h4>
                        <span>All Main Components</span>
                    </div>
                </div>
                <div class="col-sm-6 p-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="">
                        <li class="breadcrumb-item"><a href="">Main Components</a></li>
                        <li class="breadcrumb-item active "><a class="text-primary">All Main Components</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content ml-4 mr-4">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h4>Main Components</h4>

                </div>
                <div class="card-body">
                    <table class="table table-responsive-lg mb-0">
                        <thead>
                            <tr>
                                <th> <strong> S.No. </strong> </th>
                                <th> <strong> Component <i class="fa fa-sort asc"></i> </strong> </th>
                                <th class="text-center"> <strong> Actions </strong> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Header</td>
                                <td class="text-center">
                                    <a href="#" @click.prevent="editComponent(1)"
                                        class="btn btn-primary shadow btn-xs  me-1"> <i
                                            class="fas fa-pencil-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Footer</td>
                                <td class="text-center">
                                    <a href="#" @click.prevent="editComponent(2)"
                                        class="btn btn-primary shadow btn-xs  me-1"> <i
                                            class="fas fa-pencil-alt"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>
    <br>
    <div class="content ml-4 mr-4" id="footer_content" v-if="showFooterContent">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Footer Items</h4>
                    <div align="right">

                        <button type="button" class="btn btn-primary mb-2 p-2" @click="openFooterModal">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Add Footer Item
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-responsive-lg mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Content</th>
                                <th class="text-center">Order</th>
                                <th>URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in footerValues" :key="index">
                                <td width="10%">{{ item.type }}</td>
                                <td width="20%">{{ item.content }}</td>
                                <td width="10%" class="text-center">{{ item.order }}</td>
                                <td width="35%">{{ item.link }}</td>
                                <td width="15%">
                                    <a href="#" @click.prevent="editSavedValue(index)"
                                        class="btn btn-primary shadow btn-xs  me-1"><i
                                            class="fas fa-pencil-alt"></i></a>
                                    <a href="#" @click.prevent="deleteSavedValue(index)"
                                        class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash "></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Header  MODAL -->
    <div class="modal fade" id="headerFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="headerFormModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">

                        <span v-if="editing"> Edit Header Details</span>
                        <span v-else> Add Header Details</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" v-slot="{ errors }" :initial-values="formValues">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="title">Title</label>

                            <Field type="text" class="form-control" v-model="formValues.title" name="title" id="title"
                                aria-describedby="titleHelp" placeholder="Enter website title"
                                :class="{ 'is-invalid': errors.title }" />

                            <span class="invalid-feedback">{{ errors.title }}</span>
                        </div>

                        <div class="form-group">
                            <label for="short_form">Website short form</label>

                            <Field type="text" class="form-control" v-model="formValues.short_form" name="short_form"
                                id="short_form" aria-describedby="titleHelp" placeholder="Enter website short-form"
                                :class="{ 'is-invalid': errors.short_form }" />

                            <span class="invalid-feedback">{{ errors.short_form }}</span>
                        </div>

                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <input type="file" class="form-control" id="logo" @change="handleFileChange"
                                accept=".jpeg,.jpg,.png,.ico,image/jpeg,image/png,image/x-icon"
                                :class="{ 'is-invalid': errors.logo }" />
                            <span class="invalid-feedback">{{ errors.logo }}</span>
                        </div>

                        <div class="form-group" v-if="formValues.logoUrl">
                            <img :src="formValues.logoUrl" alt="Logo Preview" style="max-width: 100%; height: auto;">
                        </div>

                        <!-- Favicon Upload -->
                        <div class="form-group">
                            <label for="favicon">Favicon</label>
                            <input type="file" class="form-control" id="favicon" @change="handleFaviconChange"
                                accept=".ico,.png,.jpg,.jpeg,image/x-icon,image/png,image/jpeg"
                                :class="{ 'is-invalid': errors.favicon }" />
                            <span class="invalid-feedback">{{ errors.favicon }}</span>
                        </div>

                        <!-- Favicon Preview -->
                        <!-- Inside Modal -->
   <!-- <div v-show="formValues.faviconUrl">
  <label for="favicon">Favicon Preview</label><br />
  <p>Path: {{ formValues.faviconUrl }}</p>
  <img :src="formValues.faviconUrl" style="width: 48px; height: 48px;" />
</div> -->


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
    <!-- END HEADER MODAL -->



    <!-- FOOTER MODAL -->

    <div class="modal fade" id="footerFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="footerFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="footerFormModalLabel">Edit Footer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleFooterSubmit" v-slot="{ errors }" :initial-values="formFooterValues">
                    <div class="modal-body">
                        <!-- Type Selection -->
                        <div class="form-group">
                            <label for="footerType">Footer Type</label>
                            <select v-model="formFooterValues.type" class="form-control" id="footerType">
                                <option value="text">Text</option>
                                <option value="link">Link</option>
                                <option value="logo">Logo</option>
                                <option value="quicklink">Quick Link</option>
                            </select>
                        </div>

                        <!-- Text Field -->
                        <div v-if="formFooterValues.type === 'text'" class="form-group">
                            <label for="content">Content</label>
                            <Field type="text" class="form-control" v-model="formFooterValues.content" name="content"
                                id="content" placeholder="Enter content" :class="{ 'is-invalid': errors.content }" />
                            <span class="invalid-feedback">{{ errors.content }}</span>
                        </div>

                        <!-- Link Fields -->
                        <div v-if="formFooterValues.type === 'link'" class="form-group">
                            <label for="link">Link Text</label>
                            <Field type="text" class="form-control" v-model="formFooterValues.content" name="content"
                                id="content" placeholder="Enter link" :class="{ 'is-invalid': errors.content }" />
                            <span class="invalid-feedback">{{ errors.content }}</span>

                            <label for="url">Link URL</label>
                            <Field type="text" class="form-control" v-model="formFooterValues.link" name="url" id="url"
                                placeholder="Enter URL for external link" :class="{ 'is-invalid': errors.url }" />
                            <span class="invalid-feedback">{{ errors.url }}</span>
                        </div>

                        <!-- Logo Field -->
                        <div v-if="formFooterValues.type === 'logo'" class="form-group">
                            <label for="content">Logo Text</label>
                            <Field type="text" class="form-control" v-model="formFooterValues.content" name="content"
                                id="content" placeholder="Enter Logo Text" :class="{ 'is-invalid': errors.content }" />
                            <span class="invalid-feedback">{{ errors.content }}</span>
                        </div>

                        <!-- Quick Link -->

                        <div v-if="formFooterValues.type === 'quicklink'" class="form-group">
                            <label for="link">Quick Link Text</label>
                            <Field type="text" class="form-control" v-model="formFooterValues.content" name="content"
                                id="content" placeholder="Enter link" :class="{ 'is-invalid': errors.content }" />
                            <span class="invalid-feedback">{{ errors.content }}</span>
                            <label for="quicklink">Link </label>
                            <Field type="text" class="form-control" v-model="formFooterValues.quicklink" name="link"
                                id="link" placeholder="Enter Quick Link" :class="{ 'is-invalid': errors.quicklink }" />
                            <span class="invalid-feedback">{{ errors.quicklink }}</span>

                        </div>

                        <div class="form-group">
                            <label for="order">Order</label>

                            <Field type="text" class="form-control" v-model="formFooterValues.order" name="order"
                                id="order" aria-describedby="titleHelp" placeholder="Enter order"
                                :class="{ 'is-invalid': errors.order }" />

                            <span class="invalid-feedback">{{ errors.order }}</span>
                        </div>

                        <!-- Logo Field -->
                        <div v-if="formFooterValues.type === 'logo'" class="form-group">
                            <label>Logo</label>
                            <input type="file" class="form-control" @change="handleLogoChange"
                                accept="image/jpeg, image/png" :class="{ 'is-invalid': errors.logo }" />
                            <span class="invalid-feedback">{{ errors.logo }}</span>
                            <br>
                            <div v-if="formFooterValues.logoUrl">
                                <img :src="formFooterValues.logoUrl" alt="Logo Preview"
                                    style="max-width: 100%; height: auto;">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>



                    </div>

                </Form>
            </div>
        </div>
    </div>

</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import axios from 'axios';
import * as yup from 'yup';
import { Form, Field, useSetFieldError } from 'vee-validate';
import { useToastr } from '../../toaster.js';
import Swal from 'sweetalert2';

const toastr = useToastr();
const form = ref(null);
const header = ref([]);
const footer = ref([]);
const formValues = ref({
    title: '',
    short_form: '',
    logoUrl: null,
    logo: null,
    faviconUrl: null,
    favicon: null
});
const editing = ref(false);
const footerValues = ref([]);
const formFooterValues = ref({ id: '', type: '', content: '', link: '', logoUrl: null, order: '', quicklink: '' });
const showFooterContent = ref(false);
const editingFooterItem = ref(false);

const getComponentsDetails = () => {
    axios.get('/api/getComponentsDetails')
        .then((response) => {
            const website = response.data.website || {};  // NOT an array
            const footers = response.data.footer || [];

            if (!website || Object.keys(website).length === 0) {
                console.warn('No website data found');
                return;
            }

            header.value = {
                ...website,
                favicon: website.favicon || null,
            };

            formValues.value = {
                id: website.id || null,
                title: website.title || '',
                short_form: website.website_short || '',
                logo: website.logo || '',
                quicklink: website.quicklink || '',
                logoUrl: website.logo ? `/storage/${website.logo}` : null,
                favicon: website.favicon || '',
                faviconUrl: website.favicon ? `/storage/${website.favicon}` : null,
            };

            footerValues.value = footers;
        })
        .catch((error) => {
            console.error('Error fetching component details:', error);
        });
};


const editComponent = (component) => {
    editing.value = true;
    if (component == 1) {
        formValues.value = {
            id: header.value.id,
            title: header.value.title,
            short_form: header.value.website_short,
            logo: header.value.logo,
            logoUrl: header.value.logo ? `/storage/${header.value.logo}` : null,
        };

        $('#headerFormModal').modal('show');
    }
    else {
        showFooterContent.value = !showFooterContent.value;
    }
}

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        formValues.value.logo = file;
        formValues.value.logoUrl = URL.createObjectURL(file);
    }
}


const handleFaviconChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        formValues.value.favicon = file;
        formValues.value.faviconUrl = URL.createObjectURL(file);
    }
}

const handleLogoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        formFooterValues.value.link = file;
        formFooterValues.value.logoUrl = URL.createObjectURL(file);
    }
}

const handleSubmit = async (values, actions) => {
    const formData = new FormData();
    formData.append('title', formValues.value.title);
    formData.append('short_form', formValues.value.short_form);
    if (formValues.value.logo) {
        formData.append('logo', formValues.value.logo);
    }
    if (formValues.value.favicon) {
        formData.append('favicon', formValues.value.favicon);
    }

    try {
        await axios.post('/api/update_component/1', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        $('#headerFormModal').modal('hide');
        toastr.success('Header saved successfully');
    } catch (error) {
        console.error(error);
    } finally {
        actions.resetForm();
    }
};;

const handleFooterSubmit = (values, actions) => {

    axios.post('/api/update_component/' + 2, formFooterValues.value, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then((response) => {
            getComponentsDetails();
            $('#footerFormModal').modal('hide');
            toastr.success('Footer saved successfully');
        }).catch((errors) => {
            console.log(errors);
        })
        .finally(() => {
            form.value.resetForm();
        });

};

const openFooterModal = () => {
    editingFooterItem.value = false;
    formFooterValues.value = { id: '', type: '', content: '', link: '', logoUrl: null, order: '' };
    $('#footerFormModal').modal('show');
};

const editSavedValue = (index) => {
    editingFooterItem.value = true;
    formFooterValues.value = {
        id: footerValues.value[index].id,
        type: footerValues.value[index].type,
        content: footerValues.value[index].content,
        link: footerValues.value[index].link,
        order: footerValues.value[index].order,
        logoUrl: footerValues.value[index].link ? footerValues.value[index].link : null,
    };
    $('#footerFormModal').modal('show');
};

const deleteSavedValue = (index) => {
    axios.post('/api/delete_component/', footerValues.value[index])
        .then((response) => {
            getComponentsDetails();
            toastr.success('Footer deleted successfully');
        }).catch((errors) => {
            console.log(errors);
        })
        .finally(() => {
            form.value.resetForm();
        });
};

onMounted(() => {
    getComponentsDetails();
});

</script>
<style scoped>
.table th:last-child,
.table td:last-child {
    width: 150px;
    /* Set a fixed width for the last column */
    text-align: center;
}
</style>