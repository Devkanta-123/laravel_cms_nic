<template>
  <!-- <div class="col-lg-3 col-md-4 col-sm-6">
    <div class="team__item-two shine-animate-item" :style="{ width: '200px', height: '300px', padding: '10px' }">
      <div class="team__thumb-two shine-animate" :style="{ width: '100%', height: '150px', overflow: 'hidden' }">
        <img :src="imageUrl" :alt="person.name" :style="{ width: '100%', height: '100%' }" />
      </div>
      <div class="team__content-two" :style="{ fontSize: '14px', textAlign: 'center' }">
        <h4 class="title contactus">
          <a href="#" :style="{ fontSize: '16px' }">
            {{ person.name }}
          </a>
        </h4>
        <span style="color: #014E4E;">{{ person.designation }}</span>
        <span v-if="person.district_name || person.block_name" style="color: #014E4E;">
          {{ person.district_name || person.block_name }}
        </span>
        <span v-if="person.email" style="color: #014E4E;">
          {{ formattedEmail }}
        </span>
        <span v-if="person.contact" style="color: #014E4E;">
          {{ person.contact }}
        </span>
      </div>
    </div>
  </div> -->
  <div class="col-lg-3 col-md-6 col-sm-8">
    <div class="team__item-two shine-animate-item">
      <div class="team__thumb-two shine-animate">
        <img :src="imageUrl" :alt="person.name" @click="openModal(imageUrl)">
      </div>
      <div class="team__content-two">
        <span> {{ person.name }} <span>{{person.designation }}</span></span>
        <span v-if="person.district_name || person.block_name" style="color: #014E4E;">
          {{ person.district_name || person.block_name }}
        </span>
        <div class="team__social-two">
          <li v-if="person.email">
            <a :href="`mailto:${person.email}`" style="color: #014E4E;">{{ person.email }}</a>
          </li>
          <li v-if="person.contact">
            <a :href="`tel:${person.contact}`" style="color: #014E4E;">{{ person.contact }}</a>
          </li>
        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="viewImage" tabindex="-1" role="dialog" aria-hidden="true" :class="{ show: showModal }"
    :style="{ display: showModal ? 'block' : 'none' }">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header align-items-start">
          <div class="modal-title">
            <div class="mb-30">
              <div class="blog-box blog-2">
                <img class="img-fluid w-100" :src="modalImage" alt="Modal Image" />
                <div class="blog-info pt-10"></div>
              </div>
            </div>
          </div>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
      </div>
    </div>
  </div>

</template>


<script setup>
import { computed, ref } from "vue";
const showModal = ref(false);
const modalImage = ref('');
const openModal = (imageSrc) => {
  modalImage.value = imageSrc;
  showModal.value = true;
}
const closeModal = () => {
  showModal.value = false;
};
const props = defineProps(["person"]);

const imageUrl = computed(() => {
  return "/storage/" + props.person.profile_image.replace("public/", "");
});

const formattedEmail = computed(() => {
  return props.person.email ? props.person.email.replace("[dot]", ".").replace("[at]", "@") : "";
});
</script>