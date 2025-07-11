<template>
  <div class="col-md-4">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h5"><b>Admin</b>Login</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in</p>

        <div v-if="errorMessage" class="alert alert-danger" role="alert">
          {{ errorMessage }}
        </div>

        <form @submit.prevent="handleSubmit" class="login-form">
          <!-- Email -->
          <div class="input-group mb-3">
            <input v-model="form.email" type="email" class="form-control" placeholder="Email" required />
          </div>

          <!-- Password -->
          <div class="input-group mb-3">
            <input v-model="form.password" type="password" class="form-control" placeholder="Password" required />
          </div>

          <!-- CAPTCHA -->
          <!-- <div class="mb-3">
            <img :src="captchaImage" @click="loadCaptcha"
              style="cursor: pointer; border: 1px solid #ccc; border-radius: 4px; display: block; max-width: 100%; height: 50px;"
              alt="CAPTCHA" />
            <small class="form-text text-muted d-block">Click image to refresh CAPTCHA</small>
            <input v-model="form.captcha" type="text" class="form-control mt-2" placeholder="Enter CAPTCHA"
              required />
            <div v-if="captchaError" class="text-danger mt-1">{{ captchaError }}</div>
          </div> -->

          <!-- Submit -->
          <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
            <span v-if="loading">Signing in...</span>
            <span v-else>Sign In</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watchEffect } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  email: '',
  password: '',
  captcha: '',
  captcha_key: ''
});

const captchaImage = ref('');
const loading = ref(false);
const errorMessage = ref('');
const captchaError = ref('');

// Debug Watch
watchEffect(() => {
  console.log('🧪 Form state:', JSON.stringify(form.value, null, 2));
});

const loadCaptcha = async () => {
  try {
    const res = await axios.get('/generateCaptcha');
    captchaImage.value = res.data.captcha_img;
    form.value.captcha_key = res.data.captcha_key;
    console.log('🔁 CAPTCHA loaded:', form.value.captcha_key);
  } catch (err) {
    console.error('Error loading CAPTCHA:', err);
  }
};

const handleSubmit = async () => {
  loading.value = true;
  errorMessage.value = '';
  captchaError.value = '';

  const payload = {
    email: form.value.email,
    password: form.value.password,
    captcha: form.value.captcha,
    captcha_key: form.value.captcha_key
  };

  console.log('🚀 Submitting payload:', JSON.stringify(payload, null, 2));

  try {
    const response = await axios.post('/login', payload);
    console.log('✅ Login response:', response);
    fetchUser();
  } catch (error) {
    console.error('❌ Login error:', error.response);
    if (error.response?.status === 422) {
      const errors = error.response.data.errors;
      if (errors?.captcha) {
        captchaError.value = errors.captcha[0];
        loadCaptcha(); // Refresh captcha
      } else {
        errorMessage.value = error.response.data.message || 'Login failed';
      }
    } else {
      errorMessage.value = 'An unexpected error occurred.';
    }
  } finally {
    loading.value = false;
  }
};

const fetchUser = async () => {
  try {
    const response = await axios.get('/api/get_user');
    const user = response.data[0];
    const profile = response.data[1][0];

    console.log("👤 Login User Data:", user);

    if (user.role_id === 1 || user.role_id === 2) {
      window.location.href = "/admin/dashboard";
    } else if (user.role_id === 3) {
      window.location.href = "/contentcreator/dashboard";
    } else if (user.role_id === 4) {
      window.location.href = "/publisher/dashboard";
    }
  } catch (error) {
    console.error('Failed to fetch user:', error);
  }
};

onMounted(() => {
  loadCaptcha();
});
</script>
