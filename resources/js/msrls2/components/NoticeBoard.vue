<template>
  <div class="container">
    <!-- Search Bar -->
    <div class="footer__newsletter-three mb-3">
      <form @submit.prevent>
        <input
          type="text"
          v-model="searchQuery"
          class="form-control"
          placeholder="Search ...."
        />
      </form>
    </div>

    <!-- Notices -->
    <div class="row justify-content-center gy-4">
      <template v-if="hasNoticeboard">
        <div class="about__content-inner-four">
          <div class="about__list-box">
            <ul class="list-wrap">
              <h5 class="m-0">{{ pageName }}</h5>
              <br />
              <li
                v-for="notice in paginatedFilteredNoticeboard"
                :key="notice.id"
                class="w-100 d-flex align-items-center mb-2 ms-1"
              >
                <i class="fa fa-arrow-right"></i>
                <a
                  href="#"
                  style="color: #2A3335"
                  @click.prevent="openfile(notice)"
                >
                  {{ notice.title }} ({{ formatDate(notice.date) }})
                </a>
                <span
                  v-if="isNewNotice(notice.date)"
                  class="new-badge ms-2 text-danger small fw-bold"
                >
                  New
                </span>
              </li>
              <li v-if="paginatedFilteredNoticeboard.length === 0" class="text-muted">
                No notices found.
              </li>
            </ul>
          </div>
        </div>
      </template>

      <!-- Notifications -->
      <template v-else-if="hasNotification">
        <div class="about__content-inner-four">
          <div class="about__list-box">
            <ul class="list-wrap">
              <h5 class="m-0">{{ pageName }}</h5>
              <br />
              <li
                v-for="notify in paginatedFilteredNotification"
                :key="notify.id"
                class="w-100 d-flex align-items-center mb-2 ms-1"
              >
                <i class="fa fa-arrow-right"></i>
                <a
                  href="#"
                  style="color: #2A3335"
                  @click.prevent="openfile(notify)"
                >
                  {{ notify.title }} ({{ formatDate(notify.date) }})
                </a>
                <span
                  v-if="isNewNotice(notify.date)"
                  class="new-badge ms-2 text-danger small fw-bold"
                >
                  New
                </span>
              </li>
              <li v-if="paginatedFilteredNotification.length === 0" class="text-muted">
                No notifications found.
              </li>
            </ul>
          </div>
        </div>
      </template>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation example" v-if="totalPages > 1">
      <ul class="pagination justify-content-center mt-3">
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
  noticeboard: { type: Array, default: () => [] },
  notification: { type: Array, default: () => [] },
});

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;
const pageName = 'Notices';

// Reset page on search
watch(searchQuery, () => {
  currentPage.value = 1;
});

// Determine active data set
const hasNoticeboard = computed(() => props.noticeboard?.length > 0);
const hasNotification = computed(() => !hasNoticeboard.value && props.notification?.length > 0);

// Filtered data
const filteredNoticeboard = computed(() => {
  return props.noticeboard.filter((n) =>
    n.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
const filteredNotification = computed(() => {
  return props.notification.filter((n) =>
    n.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Paginated + Filtered
const paginatedFilteredNoticeboard = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredNoticeboard.value.slice(start, start + itemsPerPage);
});
const paginatedFilteredNotification = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredNotification.value.slice(start, start + itemsPerPage);
});

const totalPages = computed(() => {
  const list = hasNoticeboard.value
    ? filteredNoticeboard.value
    : filteredNotification.value;
  return Math.ceil(list.length / itemsPerPage);
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const openfile = (item) => {
  const filePath = '/storage/' + item.file.replace('public/', '');
  window.open(filePath, '_blank');
};

const formatDate = (dateString) => {
  if (!dateString) return 'Unknown Date';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-GB', options);
};

const isNewNotice = (noticeDate) => {
  const today = new Date();
  const noticeDateObj = new Date(noticeDate);
  const diffDays = (today - noticeDateObj) / (1000 * 60 * 60 * 24);
  return diffDays <= 10;
};
</script>

<style scoped>
.footer__newsletter-three input {
  max-width: 400px;
}
.new-badge {
  background: #ffc107;
  padding: 2px 6px;
  border-radius: 4px;
  text-decoration-color:white;
}
</style>
