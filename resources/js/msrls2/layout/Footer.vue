<template>
  <footer>

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
  </footer>
  <!-- <footer>
    <div class="footer-area">
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="footer-widget">
                <div class="fw-logo mb-25">
                  <a href="index.html"><img src="assets/img/logo/logo.png" alt="Apexa"></a>
                </div>
                <div class="footer-content">
                  <p>Copyright © 2025 - All Rights Reserved - Official website of Meghalaya State Rural Livelihoods
                    Society (MSRLS), Government of Meghalaya
                    Note: Content on this website is published and managed by Meghalaya State Rural Livelihoods Society
                    (MSRLS), Government of Meghalaya
                    For any query regarding this website, please contact the Project Manager - Knowledge Management &
                    Communications (KMC)
                    E-mail ID: pmkmc[dot]msrls[at]gmail[dot]com</p>
                  <div class="footer-social">
                    <ul class="list-wrap">
                      <li>
                        <a href="javascript:void(0)"><i class="fab fa-facebook-f"></i></a>
                      </li>
                      <li>
                        <a href="javascript:void(0)"><i class="fab fa-twitter"></i></a>
                      </li>
                      <li>
                        <a href="javascript:void(0)"><i class="fab fa-instagram"></i></a>
                      </li>
                      <li>
                        <a href="javascript:void(0)"><i class="fab fa-pinterest-p"></i></a>
                      </li>
                      <li>
                        <a href="javascript:void(0)"><i class="fab fa-youtube"></i></a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
              <div class="footer-widget">
                <h4 class="fw-title">Information</h4>
                <div class="footer-info-list">
                  <ul class="list-wrap">
                    <li>
                      <div class="icon">
                        <i class="flaticon-phone-call"></i>
                      </div>
                      <div class="content">
                        <a href="tel:0123456789">+123 888 9999</a>
                      </div>
                    </li>
                    <li>
                      <div class="icon">
                        <i class="flaticon-envelope"></i>
                      </div>
                      <div class="content">
                        <a href="mailto:info@apexa.com">info@apexa.com</a>
                      </div>
                    </li>
                    <li>
                      <div class="icon">
                        <i class="flaticon-pin"></i>
                      </div>
                      <div class="content">
                        <p>Sydney Harbour Bridge Circular City of Sydney, Australia.</p>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
              <div class="footer-widget">
                <h4 class="fw-title">Top Links</h4>
                <div class="footer-link-list">
                  <ul class="list-wrap">
                    <li><a href="about.html">How it’s Work</a></li>
                    <li><a href="contact.html">Partners</a></li>
                    <li><a href="contact.html">Testimonials</a></li>
                    <li><a href="contact.html">Case Studies</a></li>
                    <li><a href="contact.html">Pricing</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="footer-widget">
                <h4 class="fw-title">Social Media</h4>
                <div class="footer-instagram">
                  <ul class="list-wrap">
                    <li>
                      <a href="javascript:void(0)"><img src="assets/img/images/footer_insta01.jpg" alt="Apexa"></a>
                    </li>
                    <li>
                      <a href="javascript:void(0)"><img src="assets/img/images/footer_insta02.jpg" alt="Apexa"></a>
                    </li>
                    <li>
                      <a href="javascript:void(0)"><img src="assets/img/images/footer_insta03.jpg" alt="Apexa"></a>
                    </li>
                    <li>
                      <a href="javascript:void(0)"><img src="assets/img/images/footer_insta04.jpg" alt="Apexa"></a>
                    </li>
                    <li>
                      <a href="javascript:void(0)"><img src="assets/img/images/footer_insta05.jpg" alt="Apexa"></a>
                    </li>
                    <li>
                      <a href="javascript:void(0)"><img src="assets/img/images/footer_insta06.jpg" alt="Apexa"></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-7 order-0 order-lg-2">
              <div class="footer-newsletter">
                <h4 class="title">Newsletter SignUp!</h4>
                <form action="#">
                  <input type="text" placeholder="e-mail Type . . .">
                  <button class="btn btn-two" type="submit">Subscribe</button>
                </form>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="copyright-text">
                <p>Copyright © <a href="index.html">MSRLS</a> | All Right Reserved</p>
                <a href="#">Support Terms &amp; Conditions Privacy Policy.</a>
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
  </footer> -->
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