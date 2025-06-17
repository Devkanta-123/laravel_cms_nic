<template>
  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  crossorigin="anonymous"
/>

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">
  <nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <!-- logo -->
    <div class="text-start navbar-brand-wrapper">
      <a class="navbar-brand brand-logo" href="index.html"><img src="images/logo-dark.png" alt=""></a>
      <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-icon-dark.png" alt=""></a>
    </div>
    <!-- Top bar left -->
    <ul class="nav navbar-nav me-auto">
      <li class="nav-item">
        <a id="button-toggle" class="button-toggle-nav inline-block ml-20 pull-left" href="javascript:void(0);"><i
            class="zmdi zmdi-menu ti-align-right"></i></a>
      </li>
      <li class="nav-item">
        <div class="search">
          <a class="search-btn not_click" href="javascript:void(0);"></a>
          <div class="search-box not-click">
            <input type="text" class="not-click form-control" placeholder="Search" value="" name="search">
            <button class="search-button" type="submit"> <i class="fa fa-search not-click"></i></button>
          </div>
        </div>
      </li>
    </ul>
    <!-- top bar right -->
    <ul class="nav navbar-nav ms-auto">
      <li class="nav-item fullscreen">
        <a id="btnFullscreen" href="#" class="nav-link"><i class="ti-fullscreen"></i></a>
      </li>
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
          <router-link class="dropdown-item" :to="{ path: '/admin/activitylog' }">
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
          <div class="nav-grid">
            <a href="#" class="nav-grid-item"><i class="ti-pencil-alt text-warning"></i>
              <h5>Add Orders</h5>
            </a>
            <a href="#" class="nav-grid-item"><i class="ti-truck text-danger "></i>
              <h5>New Orders</h5>
            </a>
          </div>
        </div>
      </li>
      <li class="nav-item dropdown mr-30">
        <a class="nav-link nav-pill user-avatar" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
          aria-expanded="false">
          <img src="images/profile-avatar.jpg" alt="avatar">
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="dropdown-header">
            <h5 class="mt-0 mb-0">{{name}}</h5>
            <span>{{email}}</span>
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

  <div class="container-fluid">

    <div class="row" v-if="role == 3">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-success">
                  <i class="fa fa-users highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Visitors</p>
                <h4>65,650</h4>
              </div>
            </div>
            <!-- <p class="text-muted pt-3 mb-0 mt-2 border-top">
              <i class="fa fa-exclamation-circle me-1" aria-hidden="true"></i> 81% lower growth
            </p> -->
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-danger">
                  <i class="fa fa-clipboard-list highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">UnPublish Data </p>
                <h4>10</h4>
              </div>
            </div>
            <!-- <p class="text-muted pt-3 mb-0 mt-2 border-top">
              <i class="fa fa-bookmark-o me-1" aria-hidden="true"></i> Total sales
            </p> -->
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-warning">
                  <i class="fa fa-exclamation-circle highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark">Missing Content Alerts</p>
                <h4>65</h4>
              </div>
            </div>
            <!-- <p class="text-muted pt-3 mb-0 mt-2 border-top">
              <i class="fa fa-calendar me-1" aria-hidden="true"></i> Sales Per Week
            </p> -->
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-primary">
                  <i class="fa fa-envelope highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-dark"> Contact Form Submissions</p>
                <h4>50+</h4>
              </div>
            </div>
            <!-- <p class="text-muted pt-3 mb-0 mt-2 border-top">
              <i class="fa fa-repeat me-1" aria-hidden="true"></i> Just Updated
            </p> -->
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="row">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
        <div class="card card-statistics h-100 bg-danger">
          <div class="card-body">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-white">
                  <i class="fa fa-bar-chart-o highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-white">Visitors</p>
                <h4 class="text-white">65,650</h4>
              </div>
            </div>
            <p class="mt-3 text-white pt-3 border-top border-white">
              <i class="fa fa-exclamation-circle me-1" aria-hidden="true"></i> 81% lower growth
            </p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
        <div class="card card-statistics h-100 bg-primary">
          <div class="card-body">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-white">
                  <i class="fa fa-shopping-cart highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-white">Orders</p>
                <h4 class="text-white">656</h4>
              </div>
            </div>
            <p class="text-white mt-3 pt-3 border-top border-white">
              <i class="fa fa-bookmark-o me-1" aria-hidden="true"></i> Total sales
            </p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
        <div class="card card-statistics h-100 bg-warning">
          <div class="card-body">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-white">
                  <i class="fa fa-dollar highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-white">Revenue</p>
                <h4 class="text-white">$65656</h4>
              </div>
            </div>
            <p class="text-white mt-3 pt-3 border-top border-white">
              <i class="fa fa-calendar me-1" aria-hidden="true"></i> Sales Per Week
            </p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
        <div class="card card-statistics h-100 bg-dark">
          <div class="card-body">
            <div class="clearfix">
              <div class="float-start">
                <span class="text-white">
                  <i class="fa fa-twitter highlight-icon" aria-hidden="true"></i>
                </span>
              </div>
              <div class="float-end text-end">
                <p class="card-text text-white">Followers</p>
                <h4 class="text-white">62,500+</h4>
              </div>
            </div>
            <p class="text-white mt-3 pt-3 border-top border-white">
              <i class="fa fa-repeat me-1" aria-hidden="true"></i> Just Updated
            </p>
          </div>
        </div>
      </div>
    </div> -->
  </div>
</template>

<script setup>
import { reactive, toRefs, onMounted, ref } from 'vue';
import axios from 'axios';
const role = ref(null)
const email = ref('')
const name = ref('')
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

    console.log('Fetched from API', user)

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
const formatRelativeTime = (dateStr) => {
  const now = new Date();
  const then = new Date(dateStr);
  const seconds = Math.floor((now - then) / 1000);

  if (seconds < 60) return 'Just now';
  if (seconds < 3600) return `${Math.floor(seconds / 60)} min${seconds < 120 ? '' : 's'} ago`;
  if (seconds < 86400) return `${Math.floor(seconds / 3600)} hour${seconds < 7200 ? '' : 's'} ago`;
  return then.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
};
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
onMounted(() => {
  fetchUser();
  getActivityLog();
});

</script>

<style scoped>
@import './assets/css/style.css';
</style>
