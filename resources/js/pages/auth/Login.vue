<template>
    <div class="col-md-4">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h5"><b>Admin</b>Login</a>
            </div>
            <div class="card-body ">
                <p class="login-box-msg">Sign in </p>
                <div v-if="errorMessage" class="alert alert-danger" role="alert">
                    {{ errorMessage }}
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="input-group mb-3">
                        <input v-model="form.email" type="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input v-model="form.password" type="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                <div v-if="loading" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span v-else>Sign In</span>
                            </button>
                        </div>

                    </div>
                </form>

                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
            </div>

        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
// import { useAuthUserStore } from '../../stores/AuthUserStore';

// const authUserStore = useAuthUserStore();
const router = useRouter();
const form = reactive({
    email: '',
    password: '',
});

const loading = ref(false);

const errorMessage = ref('');

// const handleSubmit = () => {
//     loading.value = true;
//     errorMessage.value = '';
//     axios.post('/login', form)

//         .then(() => {
//             window.location.href = "/admin/dashboard";
//             // router.push('/admin/dashboard');
//         })
//         .catch((error) => {
//             errorMessage.value = error.response.data.message;
//         })
//         .finally(() => {
//             loading.value = false;
//         });
// };

const handleSubmit = () => {
    loading.value = true;
    errorMessage.value = '';
    axios.post('/login', form)

        .then((response) => {
            console.log('Login response:', response);
            fetchUser();

        })
        .catch((error) => {
            errorMessage.value = error.response.data.message;
        })
        .finally(() => {
            loading.value = false;
        });
};

const fetchUser = async () => {
    try {
        await axios.get('/api/get_user')
            .then((response) => {
                const user = response.data[0];
                const profile = response.data[1][0];

                console.log("Login User Data", user);

                // Redirect based on role_id
                if (user.role_id === 1 || user.role_id === 2) {
                    window.location.href = "/admin/dashboard";
                } else if (user.role_id === 3) { //content creator
                    window.location.href = "/contentcreator/dashboard";
                }else if (user.role_id === 4) { //publisher
                    window.location.href = "/publisher/dashboard";
                }
                
            })
            .catch((error) => {
                console.error('Error:', error);
            });

    } catch (error) {
        console.error('Failed to fetch user:', error);
    }
};

// const handleSubmit = () => {
//     debugger;
//     loading.value = true;
//     errorMessage.value = '';

//     axios.post('/login', form)
//         .then((response) => {
//             console.log('Login response:', response); 
//             const roleId = response.data.role_id;

//             if (roleId === 1 || roleId === 2) {
//                 window.location.href = "/admin/dashboard";
//                 // router.push('/admin/dashboard');
//             } else if (roleId === 3 || roleId === 4) {
//                 window.location.href = "/contentcreator/dashboard";
//                 // router.push('/content/dashboard');
//             } else {
//                 errorMessage.value = "Unautorized Access.";
//                 loading.value = false;
//             }
//         })
//         .catch((error) => {
//             errorMessage.value = error.response?.data?.message || "Login failed.";
//         })
//         .finally(() => {
//             loading.value = false;
//         });
// };

</script>
