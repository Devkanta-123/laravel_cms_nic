<template>
  <!-- <footer>

    <div class="footer-area">
      <div class="footer-top" style="margin-top:-6%;height: 510px;">
        <div class="container">
          <div class="row">
            <div class="col-xl-5 col-lg-4 col-md-9">
              <div class="footer-widget">
                <div class="fw-logo mb-25">
                  <img v-for="logo in footerContent.filter(item => item.type === 'logo' && item.content)" :key="logo.id"
                    :src="logo.content" alt="Footer Logo"
                    style="max-height: 100px; margin-bottom: 10px;padding-left:10px;" />
                </div>
                <div class="footer-content">
                  <p v-for="text in footerContent.filter(item => item.type === 'text')" :key="text.id"  class="text-justify">
                    {{ text.content }}
                  </p>
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
    </div>
  </footer> -->
  <footer>
    <div class="footer-area">
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <!-- Social Media + Footer Text -->
            <div class="col-xl-3 col-lg-4 col-md-6" v-if="socialLinks.length">
              <div class="footer-widget">
                <div class="footer-content">
                  <p v-if="footerText">{{ footerText.content }}</p>
                  <div class="footer-social">
                    <ul class="list-wrap">
                      <li v-for="item in socialLinks" :key="item.id">
                        <a :href="item.link" target="_blank">
                          <i :class="getSocialIconClass(item.content)"></i>
                        </a>
                      </li>
                    </ul>
                  </div>
                  <br>
                  <p class="mb-0 me-2">Total Visit : {{ visitorCount }}</p>
                  <a href="#"> Last Updated on : </a>
                </div>
              </div>
            </div>

            <!-- Quick Links -->
            <!-- <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6" v-if="validQuickLinks.length">
              <div class="footer-widget">
                <h4 class="fw-title">Top Links</h4>
                <div class="footer-link-list">
                  <ul class="list-wrap">
                    <li v-for="item in validQuickLinks" :key="item.id">
                      <a :href="router.resolve({
                        name: 'Page',
                        params: { id: encrypt(item.id ?? 1) }
                      }).href" target="_blank" rel="noopener noreferrer">
                        {{ item.link_title }}
                      </a>
                    </li>

                  </ul>
                </div>
              </div>
            </div> -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6" v-if="validQuickLinks.length">
              <div class="footer-widget">
                <h4 class="fw-title">Top Links</h4>
                <div class="footer-link-list">
                  <ul class="list-wrap">
                    <li v-for="item in validQuickLinks" :key="item.id">
                      <router-link :to="{
                        name: 'Page',
                        params: { id: encrypt(item.id ?? 1) },
                        query: { page_name: encrypt(item.menu_name ?? '') }
                      }">
                        {{ item.link_title }}
                      </router-link>
                    </li>
                  </ul>
                </div>
              </div>
            </div>


            <!-- Logos -->
            <div class="col-xl-3 col-lg-4 col-md-6" v-if="logos.length">
              <div class="footer-widget">
                <h4 class="fw-title">Logo</h4>
                <div class="footer-instagram">
                  <ul class="list-unstyled d-flex flex-wrap gap-2 align-items-center">
                    <li v-for="item in logos" :key="item.id">
                      <a href="javascript:void(0)">
                        <img :src="getImageUrl(item.link)" :alt="item.content" class="img-fluid"
                          style="max-width: 50px;" />
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="footer-shape">
        <img class="dark-opacity aos-init aos-animate" src="assets/img/images/footer_shape01.png" alt="Apexa"
          data-aos="fade-right" data-aos-delay="400">
        <img class="dark-opacity aos-init aos-animate" src="assets/img/images/footer_shape02.png" alt="Apexa"
          data-aos="fade-left" data-aos-delay="400">
        <img src="assets/img/images/footer_shape03.png" alt="Apexa"
          data-parallax="{&quot;x&quot; : 100 , &quot;y&quot; : -100 }"
          style="transform:translate3d(0.029px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0.029px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { encrypt } from '../assets/js/cryptoUtil.js'
import { useRouter } from 'vue-router'
const router = useRouter()
const cacheDuration = 1 * 60 * 1000 // 1 minute
const visitorCount = ref();
// Footer content state
const footerContent = ref([])
const loadingFooter = ref(false)

// Get full image path for logo type
const getImageUrl = (filename) => {
  return filename ? `/storage/msrls2/footer/${filename.trim()}` : "";
};


const getTotalVisitCounts = async () => {
  try {
    const response = await axios.get('/getVisitorCount')
    if (response.data.status) {
      const originalCount = response.data.visit_count;
      visitorCount.value = originalCount === 2
        ? originalCount - 1
        : originalCount - 2;
    } else {
      visitorCount.value = 'N/A'
    }
  } catch (error) {
    console.error('Failed to fetch visit count:', error)
    visitorCount.value = 'Error'
  }
}


// Fetch footer content from cache or API
const fetchFooterContent = async () => {
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

// ✅ Computed for "text" type (e.g., copyright)
const footerText = computed(() => {
  return footerContent.value.find(item => item.type === 'text')
})

// ✅ Computed for "quicklink" type
const validQuickLinks = computed(() => {
  return footerContent.value
    .filter(item => item.type === 'quicklink' && item.link && (item.link_title || item.content))
    .map(item => ({
      ...item,
      link_title: item.link_title || item.content
    }))
})

// ✅ Computed for "link" type (social links)
const socialLinks = computed(() => {
  return footerContent.value
    .filter(item => item.type === 'link' && item.link && item.content)
})

// ✅ Computed for "logo" type (footer logos)
const logos = computed(() => {
  return footerContent.value.filter(item => item.type === 'logo' && item.link)
})

// Map content name to font-awesome icon class
const getSocialIconClass = (linkTitle) => {
  switch (linkTitle?.toLowerCase()) {
    case 'facebook': return 'fab fa-facebook-f'
    case 'instagram': return 'fab fa-instagram'
    case 'youtube': return 'fab fa-youtube'
    case 'twitter': return 'fab fa-twitter'
    case 'linkedin': return 'fab fa-linkedin'
    default: return 'fas fa-link'
  }
}

// Load footer on mount
onMounted(() => {
  fetchFooterContent()
  getTotalVisitCounts()
})
</script>


<style scoped></style>