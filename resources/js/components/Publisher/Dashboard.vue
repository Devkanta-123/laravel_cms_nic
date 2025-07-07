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
        <a class="nav-link top-nav">
          <i class="fa fa-bell"></i>
        </a>
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
            See All Notifications </router-link>
        </div>
      </li>
    </ul>
  </nav>

  <div class="container-fluid ms-3" v-if="role == 4">
    <div class="row">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body"
            @click="openPageSection({ menu_id: 1, page_section_name: 'Carousel', page_section_id: 1 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.carousel.rejected }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body"
            @click="openPageSection({ menu_id: 1, page_section_name: 'Cards', page_section_id: 4 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.cards.rejected }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body"
            @click="openPageSection({ menu_id: 1, page_section_name: 'Latest News', page_section_id: 2 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.latest_news.rejected }}</p>

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
                <p class="card-text text-dark">Rejected : {{ dashboardData.notice_board.rejected }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body"
            @click="openPageSection({ menu_id: 1, page_section_name: 'Gallery', page_section_id: 5 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.gallery.rejected }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body"
            @click="openPageSection({ menu_id: 1, page_section_name: 'Paragraph', page_section_id: 7 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.paragraph.rejected}}</p>
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
            @click="openPageSection({ menu_id: 1, page_section_name: 'WhosWho', page_section_id: 9 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.whos_who.rejected }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body"
            @click="openPageSection({ menu_id: 1, page_section_name: 'Logo', page_section_id: 11 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.logo.rejected }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'Map', page_section_id: 10 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.map.rejected }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-3 col-lg-6 col-md-6 mb-20">
        <div class="card card-statistics h-100">
          <div class="card-body" @click="openPageSection({ menu_id: 1, page_section_name: 'FAQ', page_section_id: 8 })">
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
                <p class="card-text text-dark">Rejected : {{ dashboardData.faqs.rejected }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="modal fade show d-block top-right" v-if="showModal" tabindex="-1" role="dialog"
      aria-labelledby="notificationModalLabel" aria-modal="true">
      <div class="modal-dialog">
        <div class="modal-content shadow">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="notificationModalLabel">Recent Activities</h5>
            <button type="button" class="btn-close btn-close-white" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div v-for="(activity, index) in activityLogData.slice(0, 5)" :key="index"
              class="dropdown-item d-flex justify-content-between align-items-start flex-column border-bottom pb-2 mb-2">
              <div class="w-100 text-truncate">
                {{ activity.remarks.slice(0, 50) }}...
                <span class="fw-bold">by {{ activity.user_from_name }}</span>
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
            <button class="btn btn-sm btn-light" @click="closeModal">
              Dismiss
            </button>
          </div>
        </div>
      </div>
    </div> -->
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
                <span class="fw-bold">by {{ activity.user_from_name }}</span>
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
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
const router = useRouter()
const role = ref(null)
const email = ref('')
const name = ref('')
const showModal = ref(false)

const dashboardData = ref({
  cards: { approved: 0, pending: 0 },
  carousel: { approved: 0, pending: 0 },
  latest_news: { approved: 0, pending: 0 },
  notice_board: { approved: 0, pending: 0 }
})

const activityLogData = ref([])

const CACHE_DURATION = 15 * 60 * 1000 // 15 minutes

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

const formatRelativeTime = (dateStr) => {
  const now = new Date()
  const then = new Date(dateStr)
  const seconds = Math.floor((now - then) / 1000)

  if (seconds < 60) return 'Just now'
  if (seconds < 3600) return `${Math.floor(seconds / 60)} min${seconds < 120 ? '' : 's'} ago`
  if (seconds < 86400) return `${Math.floor(seconds / 3600)} hour${seconds < 7200 ? '' : 's'} ago`
  return then.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
}

const openPageSection = (section) => {
  router.push({
    name: 'PublisherFormsHandler',
    params: {
      menuId: section.menu_id,
      menuName: section.page_section_name,
      page_section_id: section.page_section_id
    }
  })
}

const fetchUser = async () => {
  const cachedUser = JSON.parse(sessionStorage.getItem('login_user_cache_data'))
  const now = new Date().getTime()

  // Use cached user if valid
  if (cachedUser && now - cachedUser.timestamp < CACHE_DURATION) {
    console.log('Loaded user from cache')
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

const getDashboardData = async () => {
  try {
    debugger;
    const response = await axios.get('/getDashboardData')
    dashboardData.value = response.data
    console.log(response.data);
  } catch (error) {
    console.error('Failed to fetch DashboardData:', error)
  }
}

function closeModal() {
  showModal.value = false
}
function goToActivityLog(id) {
  router.push({ path: '/app/activitylog', query: { id } })
}
onMounted(async () => {
  getDashboardData()
  getActivityLog()
  const userEmail = await fetchUser() // wait for fetchUser to complete
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

.modal.top-right .modal-dialog {
  position: fixed;
  top: 0;
  right: 0;
  margin: 1rem;
  width: 350px;
  transform: none !important;
}

.modal.fade {
  transition: opacity 0.3s linear;
}

.modal.show {
  display: block;
  opacity: 1;
}

.modal.d-block {
  display: block !important;
}
</style>
