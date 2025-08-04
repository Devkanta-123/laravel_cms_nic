<template>
    <div class="col-lg-12">
        <div class="contact-map" v-html="iframeHtml"></div>
    </div>
    <FeedBack>
    </FeedBack>
  

</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import axios from 'axios';
import FeedBack from "../components/FeedBack.vue";
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
