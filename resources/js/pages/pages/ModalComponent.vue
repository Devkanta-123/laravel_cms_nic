<!-- ModalComponent.vue -->
<template>
  <div class="modal" tabindex="-1" role="dialog" v-if="isOpen" id="modalComponent">
    <div class="modal-dialog custom-modal-width " role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ title }}</h5>
          <button type="button" class="close" @click="closeModal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <component :is="component" :section="section" :menu="menu"  :menuName="menuName"></component>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  isOpen: Boolean,
  title: String,
  component: Object,
  section: Object,
  menu: String,
  menuName:String
});

const emit = defineEmits(['close']);

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
.modal {
  display: block;
  background: rgba(0, 0, 0, 0.5);
}


.custom-modal-width {
  max-width: 90%;
  /* You can adjust the width as needed */
  width: 800px;
  /* Or use a specific pixel value */
}
</style>