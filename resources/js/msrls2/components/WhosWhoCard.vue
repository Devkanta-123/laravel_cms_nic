<template>
  <div class="col-lg-3 col-md-4 col-sm-6">
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
  </div>
</template>


<script setup>
import { computed } from "vue";

const props = defineProps(["person"]);

const imageUrl = computed(() => {
  return "/storage/" + props.person.profile_image.replace("public/", "");
});

const formattedEmail = computed(() => {
  return props.person.email ? props.person.email.replace("[dot]", ".").replace("[at]", "@") : "";
});
</script>