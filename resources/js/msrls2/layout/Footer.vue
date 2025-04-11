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
                  <p v-for="text in footerContent.filter(item => item.type === 'text')" :key="text.id"  class="text-justify">
                    {{ text.content }}
                  </p>
                  <!-- Render social links -->
                  <div class="footer-social">
                    <ul class="list-wrap">
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
                    <li v-for="quicklink in validQuickLinks" :key="quicklink.id">
                      <a :href="quicklink.link" target="_blank" rel="noopener noreferrer">
                        {{ quicklink.link_title }}
                      </a>
                    </li>

                  </ul>

                </div>
              </div>
            </div>

            <!-- Additional dynamic sections can be added here -->
          </div>
          <div class="footer__bottom-two">
					<div class="container">
						<div class="row">
							<div class="col-lg-20">
								<div class="copyright-text-two">
									<p>Copyright © <a href="">NIC Shillong</a> | All Right Reserved</p>
								</div>
							</div>
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
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
const cacheDuration = 1 * 60 * 1000;  // Cache duration (1 minute) in milliseconds

// Footer content data
const footerContent = ref([]);
const loadingFooter = ref(false);
const getImageUrl = (path) => {
  return path ? `/storage/${path.replace("public/", "")}` : "";
};

const fetchFooterContent = async () => {
  debugger;
  const now = new Date().getTime()

  const cachedData = localStorage.getItem("footer")
  const cachedTimestamp = localStorage.getItem("footerTimestamp")

  if (cachedData && cachedTimestamp && now - cachedTimestamp < cacheDuration) {
    console.log("Using cached footer content from localStorage")
    footerContent.value = JSON.parse(cachedData)
    return
  }

  loadingFooter.value = true
  try {
    const response = await axios.get("/get_footer")
    console.log("Fetched Footer Content:", response.data.data)
    footerContent.value = response.data.data
    localStorage.setItem("footer", JSON.stringify(response.data.data))
    localStorage.setItem("footerTimestamp", now.toString())
  } catch (error) {
    console.error("Failed to fetch footer content", error)
  } finally {
    loadingFooter.value = false
  }
}

// Computed to filter valid quick links
const validQuickLinks = computed(() => {
  return footerContent.value.filter(item =>
    item.type === 'quicklink' && item.link && (item.link_title || item.content)
  ).map(item => ({
    ...item,
    link_title: item.link_title || item.content
  }))
})

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