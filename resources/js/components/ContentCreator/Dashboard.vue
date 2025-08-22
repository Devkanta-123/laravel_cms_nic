<template>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    crossorigin="anonymous" />

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">
  <nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <!-- logo -->
    <div class="text-start navbar-brand-wrapper">
      <a class="navbar-brand brand-logo" href="index.html"><img src="images/logo-dark.png" alt=""></a>
      <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-icon-dark.png" alt=""></a>
    </div>
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
          <router-link v-for="(activity, index) in activityLogData.slice(0, 5)" :key="index"
            :to="{ path: '/app/activitylog', query: { id: activity.id } }" class="dropdown-item">
            {{ activity.remarks.slice(0, 50) }}... by {{ activity.user_from_name }}
            <small class="float-end text-muted time">{{ formatRelativeTime(activity.created_at) }}</small>
          </router-link>
          <router-link class="dropdown-item" :to="{ path: '/app/activitylog' }">
            View All
          </router-link>
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
      <!-- <div class="card">
        <div class="card-header">
          <h3 class="card-title">Component Trends</h3>
        </div>
        <div>
          <apexchart type="area" height="350" :options="ccTrendOptions" :series="ccTrendSeries" />
        </div>
      </div> -->
      <div class="card-body">
          <apexchart type="area" height="350" :options="ccTrendOptions" :series="ccTrendSeries" />
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
  <!-- Bootstrap Modal Structure -->
  <div class="modal fade show d-block" v-if="showModal" id="notificationModal" tabindex="-1" role="dialog"
    aria-labelledby="notificationModalLabel" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
          <button type="button" class="btn-close btn-close-white" @click="closeModal"></button>
        </div>
        <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
          <div v-for="(activity, index) in activityLogData.slice(0, 5)" :key="index"
            class="dropdown-item d-flex flex-column border-bottom pb-2 mb-2"
            @click.prevent="goToActivityLog(activity.id)">
            <div class="w-100 text-truncate">
              {{ activity.remarks.slice(0, 50) }}...
              <span class="fw-bold">by {{ activity.user_from_name
              }}</span>
            </div>
            <small class="text-muted align-self-end mt-1">
              {{ formatRelativeTime(activity.created_at) }}
            </small>
          </div>
          <router-link class="dropdown-item text-center fw-semibold" :to="{ path: '/app/activitylog' }">
            View All
          </router-link>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, toRefs, onMounted, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import VueApexCharts from 'vue3-apexcharts'

const router = useRouter()
const role = ref(null)
const email = ref('')
const name = ref('')
const showModal = ref(false)
const activityLogData = ref([])

// ✅ Register ApexCharts locally for <script setup>
const apexchart = VueApexCharts

// Chart series & options
const ccTrendSeries = ref([])
const ccTrendOptions = ref({
  chart: {
    type: 'area',
    height: 350,
    toolbar: { show: false },
    zoom: { enabled: false }
  },
  colors: [
    '#FF5733', // Red-Orange
    '#33C1FF', // Sky Blue
    '#28A745', // Green
    '#FFC300', // Yellow
    '#9B59B6', // Purple
    '#E67E22', // Orange
    '#1ABC9C', // Teal
    '#E74C3C', // Red
    '#3498DB', // Blue
    '#F1C40F', // Gold
    '#2ECC71', // Light Green
    '#8E44AD'  // Dark Purple
  ],
  dataLabels: { enabled: false },
  stroke: { curve: 'smooth', width: 2 },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.4,
      opacityTo: 0,
      stops: [0, 90, 100]
    }
  },
  xaxis: {
    categories: [],
    labels: { rotate: -45 }
  },
  tooltip: {
    x: { format: 'yyyy-MM' }
  },
  legend: { position: 'bottom' }
})


const fetchUser = async () => {
  const cachedUser = JSON.parse(sessionStorage.getItem('login_user_cache_data'))
  const now = new Date().getTime()
  const CACHE_DURATION = 15 * 60 * 1000 // 15 minutes

  if (cachedUser && now - cachedUser.timestamp < CACHE_DURATION) {
    role.value = cachedUser.role
    email.value = cachedUser.email
    name.value = cachedUser.name
    return cachedUser.email
  }

  try {
    const response = await axios.get('/api/get_user')
    const user = response.data[0]

    role.value = user.role_id
    email.value = user.email
    name.value = user.name

    const userData = {
      role: user.role_id,
      email: user.email,
      name: user.name,
      timestamp: now
    }

    sessionStorage.setItem('user_data', JSON.stringify(userData))
    sessionStorage.setItem('login_user_cache_data', JSON.stringify(userData))
    return user.email
  } catch (error) {
    console.error('Failed to fetch user:', error)
    return null
  }
}

function goToActivityLog(id) {
  router.push({ path: '/app/activitylog', query: { id } })
}

const formatRelativeTime = (dateStr) => {
  const now = new Date()
  const then = new Date(dateStr)
  const seconds = Math.floor((now - then) / 1000)

  if (seconds < 60) return 'Just now'
  if (seconds < 3600) return `${Math.floor(seconds / 60)} min${seconds < 120 ? '' : 's'} ago`
  if (seconds < 86400) return `${Math.floor(seconds / 3600)} hour${seconds < 7200 ? '' : 's'} ago`
  return then.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
}

const getActivityLog = async () => {
  try {
    const response = await axios.get('/api/get_archivitylog')
    if (response.data && response.data.status === 'success') {
      activityLogData.value = response.data.data
    }
  } catch (error) {
    toastr.error('Failed to fetch activity log.')
  }
}

const getCCDashboardStatistics = async () => {
  try {
    const response = await axios.get('/getCCDashboardStatistics')
    const data = response.data
    console.log("CCData", data)
    loadCCMonthlyTrends(data)
  } catch (err) {
    console.error('Error loading dashboard data:', err)
  }
}

const loadCCMonthlyTrends = (trendData) => {
  if (!trendData || typeof trendData !== 'object') return

  // Get all months across all components
  const allMonths = [
    ...new Set(
      Object.values(trendData)
        .flatMap(arr => Array.isArray(arr) ? arr.map(item => item.month) : [])
    )
  ].sort()

  const series = Object.keys(trendData).map(component => {
    const dataArr = Array.isArray(trendData[component]) ? trendData[component] : []
    const counts = allMonths.map(month => {
      const record = dataArr.find(item => item.month === month)
      return record ? record.count : 0
    })
    return { name: component, data: counts }
  })

  ccTrendSeries.value = series
  ccTrendOptions.value = {
    ...ccTrendOptions.value,
    xaxis: { categories: allMonths }
  }
}

function closeModal() {
  showModal.value = false
}

onMounted(async () => {
  fetchUser()
  getActivityLog()
  getCCDashboardStatistics()

  const userEmail = await fetchUser()
  if (!userEmail) return

  const shownKey = `notificationShown_${userEmail}`
  const hasShown = sessionStorage.getItem(shownKey)

  if (!hasShown) {
    showModal.value = true
    sessionStorage.setItem(shownKey, 'true')
  }
})
</script>



<style scoped>
@import './assets/css/style.css';
</style>
