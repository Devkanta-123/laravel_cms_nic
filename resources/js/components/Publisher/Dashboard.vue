<template>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" />
  <nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <!-- logo -->
    <div class="text-start navbar-brand-wrapper">
      <a class="navbar-brand brand-logo" href="#"><img src="images/logo-dark.png" alt=""></a>
      <a class="navbar-brand brand-logo-mini" href="#"><img
          src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" alt=""></a>
    </div>
    <!-- Top bar left -->
    <ul class="nav navbar-nav me-auto">
      <li class="nav-item">
        <a id="button-toggle" class="button-toggle-nav inline-block ml-20 pull-left" href="javascript:void(0);"><i
            class="zmdi zmdi-menu ti-align-right"></i></a>
      </li>

    </ul>
    <!-- top bar right -->
    <ul class="nav navbar-nav ms-auto">
      
      <li class="nav-item dropdown ">
        <a class="nav-link top-nav" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
          aria-expanded="false">
          <i class="fa fa-bell"></i> </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-big dropdown-notifications">
          <div class="dropdown-header notifications">
            <strong>Notifications</strong>
            <span class="badge bg-warning">{{ activityLogData.length }}</span>
          </div>
          <div class="dropdown-divider"></div>

          <a v-for="(activity, index) in activityLogData.slice(0, 5)" :key="index" href="#" class="dropdown-item">
            {{ activity.remarks }} by {{ activity.user_from_name }}
            <small class="float-end text-muted time">{{ formatRelativeTime(activity.created_at) }}</small>
          </a>
          <router-link class="dropdown-item" :to="{ path: '/app/activitylog' }">
            View All
          </router-link>
        </div>
      </li>
      <li class="nav-item dropdown ">
        <a class="nav-link top-nav" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
          aria-expanded="true"> <i class=" ti-view-grid"></i> </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-big">
          <div class="dropdown-header">
            <strong>Quick Links</strong>
          </div>
          <div class="dropdown-divider"></div>
          <div class="nav-grid">
            <a href="#" class="nav-grid-item"><i class="ti-files text-primary"></i>
              <h5>New Task</h5>
            </a>
            <a href="#" class="nav-grid-item"><i class="ti-check-box text-success"></i>
              <h5>Assign Task</h5>
            </a>
          </div>
          <!-- <div class="nav-grid">
            <a href="#" class="nav-grid-item"><i class="ti-pencil-alt text-warning"></i>
              <h5>Add Orders</h5>
            </a>
            <a href="#" class="nav-grid-item"><i class="ti-truck text-danger "></i>
              <h5>New Orders</h5>
            </a>
          </div> -->
        </div>
      </li>
      <li class="nav-item dropdown mr-30">
        <a class="nav-link nav-pill user-avatar" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
          aria-expanded="false">
          <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" alt="avatar">
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="dropdown-header">
            <h5 class="mt-0 mb-0">{{ name }}</h5>
            <span>{{ email }}</span>
          </div>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#"><i class="text-secondary ti-reload"></i>Activity</a>
          <a class="dropdown-item" href="#"><i class="text-success ti-email"></i>Messages</a>
          <a class="dropdown-item" href="#"><i class="text-warning ti-user"></i>Profile</a>
          <a class="dropdown-item" href="#"><i class="text-dark ti-layers-alt"></i>Projects <span
              class="badge bg-info">6</span> </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#"><i class="text-info ti-settings"></i>Settings</a>
          <a class="dropdown-item" href="#"><i class="text-danger ti-unlock"></i>Logout</a>
        </div>
      </li>
    </ul>
  </nav>

  <div class="container-fluid ms-3" v-if="role == 4">
    <div class="row">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'Carousel' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-success">
                  <i class="fas fa-sliders-h highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Carousel</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.carousel.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.carousel.approved }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'Cards' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-danger">
                  <i class="fa fa-layer-group highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Cards</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.cards.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.cards.approved }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'Latest News' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-warning">
                  <i class="fa fa-newspaper highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Latest News</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.latest_news.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.latest_news.approved }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body"
            @click="openPageSection({ menu_id: 1, page_section_name: 'Notice Board', page_section_id: 6 })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-primary">
                  <i class="fa fa-clipboard highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Notice Board</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.notice_board.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.notice_board.approved }}</p>
                <p class="card-text text-dark">Updated : {{ dashboardData.notice_board.updated }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'Gallery' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-primary">
                  <i class="fa fa-image highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Gallery</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.gallery.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.gallery.approved }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'Paragraph' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-primary">
                  <i class="fa fa-file-text  highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Paragraph</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.paragraph.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.paragraph.approved }}</p>
                <p class="card-text text-dark">Updated : {{ dashboardData.paragraph.updated }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'WhosWho' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-danger">
                  <i class="fa fa-users highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Whos Who</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.whos_who.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.whos_who.approved }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'Logo' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-warning">
                  <i class="fa fa-globe highlight-icon" aria-hidden="true"></i>

                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Logo</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.logo.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.logo.approved }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'Map' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-success">
                  <i class="fa fa-map-marker highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Map</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.map.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.map.approved }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'FAQ' })">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-success">
                  <i class="fa fa-question-circle highlight-icon" aria-hidden="true"></i> FAQs
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">FAQs</p>
                <p class="card-text text-dark">Pending : {{ dashboardData.faqs.pending }}</p>
                <p class="card-text text-dark">Approved : {{ dashboardData.faqs.approved }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router'
const router = useRouter()
const role = ref(null)
const email = ref('')
const name = ref('')
const dashboardData = ref({
  cards: { approved: 0, pending: 0 },
  carousel: { approved: 0, pending: 0 },
  latest_news: { approved: 0, pending: 0 },
  notice_board: { approved: 0, pending: 0 }
})

const activityLogData = ref([]);
const getActivityLog = async () => {
  try {
    const response = await axios.get('/api/get_archivitylog');
    if (response.data && response.data.status === 'success') {
      activityLogData.value = response.data.data;
    }
  } catch (error) {
    toastr.error('Failed to fetch activity log.');
  }
}

const formatRelativeTime = (dateStr) => {
  const now = new Date();
  const then = new Date(dateStr);
  const seconds = Math.floor((now - then) / 1000);

  if (seconds < 60) return 'Just now';
  if (seconds < 3600) return `${Math.floor(seconds / 60)} min${seconds < 120 ? '' : 's'} ago`;
  if (seconds < 86400) return `${Math.floor(seconds / 3600)} hour${seconds < 7200 ? '' : 's'} ago`;
  return then.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
};

const openPageSection = (section) => {
  debugger;
  router.push({
    name: 'PublisherFormsHandler',
    params: { menuId: section.menu_id, menuName: section.page_section_name, page_section_id: section.page_section_id }
  });
}
// Duration in milliseconds (e.g., 15 minutes)
const CACHE_DURATION = 15 * 60 * 1000

const fetchUser = async () => {
  const cachedUser = JSON.parse(sessionStorage.getItem('login_user_cache_data'))
  const now = new Date().getTime()

  // If data exists and is still valid
  if (cachedUser && now - cachedUser.timestamp < CACHE_DURATION) {
    console.log('Loaded from cache')
    role.value = cachedUser.role
    email.value = cachedUser.email
    name.value = cachedUser.name
    return
  }

  try {
    const response = await axios.get('/api/get_user')
    const user = response.data[0]
    // Update refs
    role.value = user.role_id
    email.value = user.email
    name.value = user.name
    // Store in sessionStorage with timestamp
    sessionStorage.setItem(
      'user_data',
      JSON.stringify({
        role: user.role_id,
        email: user.email,
        name: user.name,
        timestamp: now,
      })
    )
  } catch (error) {
    console.error('Failed to fetch user:', error)
  }
}

const getDashboardData = async () => {
  try {
    const response = await axios.get('/getDashboardData')
    console.log('Fetched  DashboardData', response.data)
    dashboardData.value = response.data
  } catch (error) {
    console.error('Failed to fetch DashboardData:', error)
  }
}
onMounted(() => {
  fetchUser();
  getDashboardData();
  getActivityLog();
});

</script>

<style scoped>
@import './assets/css/style.css';
</style>
