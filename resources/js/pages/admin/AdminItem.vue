<template>
    <tr>
            <td>
                {{ index + 1}}
            </td>
            <td>
                {{ user.name}}
            </td>
            <td>
                {{ user.email}}
            </td>
            <td>
                        {{ user.roles.role_name }}
            </td>
            <td> 
                 
              
                   <label for="active" v-if="user.is_enabled==1 "> Active</label>

                   <label for="active" v-else> Disabled</label>
                               
            </td>

            <td>
                <a href="#" @click.prevent="$emit('editUser', user)"><i class="fa fa-edit"></i></a>

                <a href="#" @click.prevent="confirmUserDeletion(user)"><i class="fa fa-trash text-danger ml-2"></i></a>
            </td>
    </tr>

      <!-- DeleteModal -->
      <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">
                             Delete User
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

                            <button type="button" @click.prevent="deleteUser" class="btn btn-primary" >Delete User</button>
                        </div>
                   
                </div>
            </div>
        </div>
</template>
<style>
/* Hide the default checkbox */
.toggle-checkbox {
    display: none;
}

/* Style the toggle switch */
.toggle-switch {
    width: 60px;
    height: 30px;
    background-color: #ccc;
    border-radius: 15px;
    position: relative;
    cursor: pointer;
}

/* Style the switch handle */
.toggle-handle {
    width: 30px;
    height: 30px;
    background-color: white;
    border-radius: 50%;
    position: absolute;
    top: 0;
    left: 0;
    transition: transform 0.3s;
}

/* Move the handle to the right when the checkbox is checked */
.toggle-checkbox:checked + .toggle-switch .toggle-handle {
    transform: translateX(30px);
}

/* Style the labels for on and off states */
.toggle-label {
    font-size: 12px;
    color: white;
    line-height: 30px;
    text-align: center;
    position: absolute;
    top: 0;
    width: 100%;
}

.toggle-label.on {
    left: 0;
}

.toggle-label.off {
    right: 0;
}
</style>

<script setup>
import { routeLocationKey } from 'vue-router';

import { formatDate } from '../../helper';
import { ref } from 'vue';
import { useToastr } from '../../toaster.js';
import axios from 'axios';


    defineProps({
        user: Object,
        index: Number
    })

    const toastr = useToastr();
    const userIdBeingDeleted = ref(null);
    const emit = defineEmits(['userDeleted', 'editUser']);

    const confirmUserDeletion = (user) => {
        userIdBeingDeleted.value = user.id;
        $('#deleteUserModal').modal('show');
     }

    const deleteUser = (user) => {
        // axios.post('http://10.179.2.187:8000/api/delete_user/'+ userIdBeingDeleted.value)
        axios.post('/api/delete_user/'+ userIdBeingDeleted.value)
        .then(() => {
            $('#deleteUserModal').modal('hide');
            // users.value = users.value.filter(user => user.id !== userIdBeingDeleted.value);
            toastr.success("User Deleted Successfully");
            emit('userDeleted', userIdBeingDeleted.value);
        })
    }
        
    const roles = ref([
        {
            name: 'ADMIN',
            value: 1 
        },
        {
            name: 'USER',
            value: 2 
        }
    ]);

    const changeRole = (user, role) =>{
            
            axios.post("/api/change_role/"+user.id,{
                role: role
            })
            .then(() => {
                toastr.success('Role changed successfully!')
            })
    }

</script>