<template>
    <div class="col-lg-3 col-md-4 col-sm-6">
      <div class="team__item-two shine-animate-item">
        <div class="team__thumb-two shine-animate">
          <img :src="imageUrl" :alt="person.name" />
        </div>
        <div class="team__content-two">
          <h4 class="title"><a href="team-details">{{ person.name }}</a></h4>
          <span>{{ person.designation }}</span>
          <span v-if="person.district_name || person.block_name">
            {{ person.district_name || person.block_name }}
          </span>
          <span v-if="person.email">
            {{ formattedEmail }}
          </span>
          <span v-if="person.contact">
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
  