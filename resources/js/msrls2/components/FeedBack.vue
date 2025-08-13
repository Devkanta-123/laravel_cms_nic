<template>
    <div class="row align-items-center">
        <div class="col-lg-5">
            <div class="contact__content">
                <div class="section-title mb-35">
                    <h2 class="title contactus">How can we help you?</h2>
                </div>

            </div>
        </div>
        <div class="col-lg-7">
            <div class="contact__form-wrap">
                <h2 class="title contactus">Give Us a Message</h2>
                <p>Your email address will not be published. Required fields are marked *</p>

                <form @submit.prevent="submitForm">
                    <div class="form-grp"> 
                        <textarea v-model="form.message" placeholder="Message"></textarea>
                        <small v-if="errors.message" class="error">{{ errors.message }}</small>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="text" v-model="form.name" placeholder="Name" />
                                <small v-if="errors.name" class="error">{{ errors.name }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="email" v-model="form.email" placeholder="Email" />
                                <small v-if="errors.email" class="error">{{ errors.email }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="number" v-model="form.phone" placeholder="Phone" />
                                <small v-if="errors.phone" class="error">{{ errors.phone }}</small>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn" :disabled="loading">
                        {{ loading ? "Sending..." : "Submit post" }}
                    </button>
                </form>
            </div>
        </div>
    </div>

</template>
<script setup>
import { reactive, ref } from "vue";
import axios from "axios";
import Swal from "sweetalert2";

const form = reactive({
    name: "",
    email: "",
    phone: "",
    message: "",
});

const errors = reactive({});
const loading = ref(false);

const validate = () => {
    let valid = true;
    errors.name = form.name ? "" : "Name is required.";
    errors.email = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)
        ? ""
        : "Invalid email address.";
    errors.phone = /^\d{10}$/.test(form.phone)
        ? ""
        : "Phone must be exactly 10 digits.";
    errors.message = form.message ? "" : "Message is required.";

    for (let key in errors) {
        if (errors[key]) valid = false;
    }
    return valid;
};

const submitForm = async () => {
  if (!validate()) {
    Swal.fire({
      icon: "error",
      title: "Validation Error",
      text: "Please fill all required fields correctly.",
    });
    return;
  }

  loading.value = true;
  try {
    debugger;
    let response = await axios.post("/addFeedback", form); // replace API URL
    if(response){
Swal.fire({
  icon: "success",
  title: "Message Sent",
  html: "<p>Your message has been sent successfully!</p><p>We will get back to you as soon as possible. Thank you for contacting us.</p>",
  timer: 2500,
  showConfirmButton: false,
});}


    Object.keys(form).forEach((key) => (form[key] = "")); // reset form
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Something went wrong. Please try again.",
    });
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.error {
    color: red;
    font-size: 0.85rem;
}
</style>
