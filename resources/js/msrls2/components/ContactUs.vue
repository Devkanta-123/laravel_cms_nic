<template>
    <div class="col-lg-12">
        <div class="contact-map" v-html="iframeHtml"></div>
    </div>
    <div class="row align-items-center">
        <div class="col-lg-5">
            <div class="contact__content">
                <div class="section-title mb-35">
                    <h2 class="title contactus">How can we help you?</h2>
                </div>
                <!-- <div class="contact__info">
                    <ul class="list-wrap">
                        <li>
                            <div class="icon">
                                <i class="flaticon-pin"></i>
                            </div>
                            <div class="content">
                                <h4 class="title contactus">Address</h4>
                                <p>{{ address }}</p>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <i class="flaticon-phone-call"></i>
                            </div>
                            <div class="content">
                                <h4 class="title contactus">Phone</h4>
                                <a :href="'tel:' + phone">{{ phone }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <i class="flaticon-mail"></i>
                            </div>
                            <div class="content">
                                <h4 class="title contactus">E-mail</h4>
                                <a :href="'mailto:' + email">{{ email }}</a>
                            </div>
                        </li>
                    </ul>
                </div> -->
            </div>
        </div>
        <div class="col-lg-7">
            <div class="contact__form-wrap">
                <h2 class="title contactus">Give Us a Message</h2>
                <p>Your email address will not be published. Required fields are marked *</p>
                <form>
                    <div class="form-grp">
                        <textarea name="message" placeholder="Message"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="text" name="name" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="email" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-grp">
                                <input type="number" name="phone" placeholder="Phone">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn">Submit post</button>
                </form>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import axios from 'axios';

const address = ref('');
const phone = ref('');
const email = ref('');
const iframeHtml = ref('');

const props = defineProps({
  data: Object
});

console.log('ContactUs data:', props.data);

// Parse HTML content from props
const parseContactContent = (htmlContent) => {
  const parser = new DOMParser();
  const doc = parser.parseFromString(htmlContent, 'text/html');

  // Extract Address
  const addressValue = doc.querySelector('table thead tr td');
  address.value = addressValue?.textContent?.trim() || '';

  // Extract Email
  const emailRow = doc.querySelector('tbody tr:nth-child(1) td');
  email.value = emailRow?.textContent?.replace('[at]', '@').replace('[dot]', '.') || '';

  // Extract Phone
  const phoneRow = doc.querySelector('tbody tr:nth-child(2) td');
  phone.value = phoneRow?.textContent?.trim() || '';
};

// Fetch iframe or other contact info via API
const fetchContactUs = async () => {
  try {
    const response = await axios.get("/get_contactus", { params: { flag: 'A' } });
    if (response.data && response.data[0]?.iframe) {
      iframeHtml.value = response.data[0].iframe;
    } else {
      console.error("Invalid data format from /get_contactus");
    }
  } catch (error) {
    console.error("Failed to fetch contact details:", error);
  }
};

// Run both operations on mount
onMounted(async () => {
  if (props.data?.content) {
    parseContactContent(props.data.content);
  }
  await fetchContactUs(); // Always fetch iframe too
});
</script>


<style scoped></style>
