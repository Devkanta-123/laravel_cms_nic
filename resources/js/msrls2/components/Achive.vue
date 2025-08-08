<template>
  <div class="container" v-if="archive.length">
    <!-- Search Box -->
    <div class="footer__newsletter-three mb-3">
      <form @submit.prevent>
        <input
          type="text"
          v-model="searchQuery"
          class="form-control"
          placeholder="Search..."
        />
      </form>
    </div>

    <!-- Archive List -->
    <div class="row justify-content-center gy-4">
      <div class="about__content-inner-four">
        <div class="about__list-box">
          <ul class="list-wrap">
            <h5 class="m-0">{{ pageName }}</h5>
            <br />
            <li
              v-for="item in paginatedFilteredArchive"
              :key="item.id"
              class="w-100 d-flex align-items-center mb-2 ms-1"
            >
              <i class="fa fa-arrow-right me-2"></i>
              <a
                href="#"
                class="text-dark"
                @click.prevent="openfile(item)"
              >
                {{ item.title }} ({{ formatDate(item.created_at) }})
              </a>
            </li>
            <li v-if="paginatedFilteredArchive.length === 0" class="text-muted">
              No items found.
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <nav v-if="totalPages > 1" class="mt-3">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" href="#" @click.prevent="goToPage(currentPage - 1)">
            <i class="fas fa-angle-double-left"></i>
          </a>
        </li>

        <li
          class="page-item"
          v-for="page in totalPages"
          :key="page"
          :class="{ active: currentPage === page }"
        >
          <a class="page-link" href="#" @click.prevent="goToPage(page)">
            {{ page }}
          </a>
        </li>

        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <a class="page-link" href="#" @click.prevent="goToPage(currentPage + 1)">
            <i class="fas fa-angle-double-right"></i>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, watch } from 'vue';

const props = defineProps({
  archive: {
    type: Array,
    default: () => []
  }
});

const pageName = 'Archive';
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;

// Reset page when search changes
watch(searchQuery, () => {
  currentPage.value = 1;
});

// Filter by search
const filteredArchive = computed(() => {
  return props.archive.filter(item =>
    item.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Paginate filtered
const paginatedFilteredArchive = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredArchive.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() => {
  return Math.ceil(filteredArchive.value.length / itemsPerPage);
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

// Open file or link
const openfile = (item) => {
  if (item.type === 'file' && item.file) {
    const filePath = '/storage/' + item.file.replace('public/', '');
    window.open(filePath, '_blank');
  } else if (item.type === 'link' && item.link) {
    window.open(item.link, '_blank');
  }
};

// Format date
const formatDate = (dateString) => {
  if (!dateString) return 'Unknown Date';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-GB', options);
};
</script>

<style scoped>
@keyframes blink {
  0% {
    opacity: 1;
  }

  50% {
    opacity: 0.2;
  }

  100% {
    opacity: 1;
  }
}

.new-badge {
  background: linear-gradient(90deg, #c8ff00, #ee0979);
  color: white;
  font-size: 12px;
  font-weight: bold;
  padding: 2px 6px;
  border-radius: 5px;
  margin-left: 8px;
  animation: blink 1s infinite;
}
</style>
