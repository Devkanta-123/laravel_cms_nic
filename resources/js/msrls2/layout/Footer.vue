<template>
  <footer>

    <div class="footer-area">
      <!-- Footer Top Section -->
      <div class="footer-top" style="margin-top:-6%;height: 510px;">
        <div class="container">
          <div class="row">
            <!-- Logo Section -->
            <div class="col-xl-5 col-lg-4 col-md-9">
              <div class="footer-widget">
                <div class="fw-logo mb-25">
                  <!-- Render all logos -->
                  <img v-for="logo in footerContent.filter(item => item.type === 'logo' && item.content)" :key="logo.id"
                    :src="logo.content" alt="Footer Logo"
                    style="max-height: 100px; margin-bottom: 10px;padding-left:10px;" />
                </div>
                <div class="footer-content">
                  <!-- Render dynamic text -->
                  <p v-for="text in footerContent.filter(item => item.type === 'text')" :key="text.id">
                    {{ text.content }}
                  </p>
                  <!-- Render social links -->
                  <div class="footer-social">
                    <ul class="list-wrap">*
                      <li v-for="social in footerContent.filter(item => item.type === 'link')" :key="social.id">
                        <a :href="social.link" target="_blank" rel="noopener noreferrer" v-if="social.link">
                          <i :class="getSocialIconClass(social.link_title)"></i>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-5 col-lg-4 col-md-6 col-sm-6">
              <div class="footer-widget">
                <h4 class="fw-title">Quick Links</h4>
                <div class="footer-link-list">
                  <ul class="list-wrap">
                    <!-- Loop through 'footerContent' and filter by 'quicklink' -->
                    <li v-for="quicklink in footerContent.filter(item => item.type === 'quicklink')"
                      :key="quicklink.id">
                      <a :href="quicklink.link" target="_blank" rel="noopener noreferrer">{{ quicklink.link_title }}</a>

                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Additional dynamic sections can be added here -->
          </div>
          <div class="row align-items-center">
            <div class="col-lg-5">
              <div class="copyright-text">
                <p>Copyright © <a href="#">NIC</a> | All Right Reserved</p>
                <a href="#">Support Terms &amp; Conditions Privacy Policy.</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Bottom Section -->

    </div>

  </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
const cacheDuration = 1 * 60 * 1000;  // Cache duration (1 minute) in milliseconds

// Footer content data
const footerContent = ref([]);
const loadingFooter = ref(false);
const getImageUrl = (path) => {
  return path ? `/storage/${path.replace("public/", "")}` : "";
};

const fetchFooterContent = async () => {
  const now = new Date().getTime();

  // Check if cached data exists and is valid
  const cachedData = localStorage.getItem("footer");
  const cachedTimestamp = localStorage.getItem("footerTimestamp");

  if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    console.log("Using cached footer content from localStorage");
    footerContent.value = JSON.parse(cachedData);
    return;
  }

  // If cache is invalid or expired, fetch new data from API
  loadingFooter.value = true;
  try {
    debugger;
    const response = await axios.get("/get_footer");
    console.log("Fetched Footer Content:", response.data.data);

    if (response.data && Array.isArray(response.data.data)) {
      // Process the fetched data and apply URL correction only for images
      footerContent.value = response.data.data.map(item => ({
        ...item,
        content: item.type === "logo" ? getImageUrl(item.content) : item.content,
      }));

      // Store new data in localStorage with a timestamp
      localStorage.setItem("footer", JSON.stringify(footerContent.value));
      localStorage.setItem("footerTimestamp", now.toString());
    } else {
      console.error("Invalid footer data format");
    }
  } catch (error) {
    console.error("Failed to fetch footer content:", error);
  } finally {
    loadingFooter.value = false;
  }
};

// Determine social media icon class based on link title
const getSocialIconClass = (linkTitle) => {
  switch (linkTitle?.toLowerCase()) {
    case 'facebook':
      return 'fab fa-facebook-f';
    case 'instagram':
      return 'fab fa-instagram';
    case 'youtube':
      return 'fab fa-youtube';
    case 'twitter':
      return 'fab fa-twitter';
    default:
      return 'fas fa-link';
  }
};

// Load content on mount
onMounted(() => {
  fetchFooterContent();
});
</script>

<style scoped></style>