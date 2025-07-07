<template>
    <div>
        <!-- <Loader v-if="isLoading" /> -->
        <nav class="main-header navbar navbar_admin navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown" style="margin-right:230px;">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-wrap"
                            v-for="(activity, index) in activityLogData.slice(0, 5)" :key="index"
                            @click.prevent="goToActivityLog(activity.id)">
                <li class="mb-1">
                    {{ activity.remarks.slice(0, 50) }}... by {{ activity.user_from_name }}
                </li>
                <span class="float-right text-muted text-sm">{{ formatRelativeTime(activity.created_at) }}</span>
                </a>


                <div class="dropdown-divider"></div>
                <!-- <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
                <router-link class="dropdown-item dropdown-footer" :to="{ path: '/app/activitylog' }">
                    See All Notifications
                </router-link>
    </div>
    </li>
    </ul>
    </nav>
    <aside class="main-sidebar  elevation-1">

        <a href="index3.html" class="brand-link">

            <span class="brand-text font-weight-light"> &nbsp; &nbsp; &nbsp;{{ website }}</span>
        </a>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ user && user.name ? user.name : '' }}
                        <br>
                        <span>
                            (
                            {{ user && user.role_id === 1 ? 'Super Admin' :
                                user && user.role_id === 2 ? 'Admin' :
                                    user && user.role_id === 3 ? 'Content Creator' :
                                        user && user.role_id === 4 ? 'Publisher' : '' }}
                            )
                        </span>
                    </a>
                </div>
            </div>


            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">


                    <li class="nav-item" v-if="role == 1">
                        <router-link to="/admin/dashboard" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard

                            </p>
                        </router-link>
                    </li>



                    <li class="nav-item" v-if="role == 1">
                        <router-link to="/admin/manage" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Admin Management

                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/manage" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                User Management

                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="role == 1">
                        <router-link to="/admin/roles" active-class="" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Roles

                            </p>
                        </router-link>
                    </li>

                    <!-- <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/website" active-class="active"  class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                            Website Info                                  
                            </p>
                        </router-link>
                    </li> -->
                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/theme" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Themes
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/menu" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Menu
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/main_components" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Main Components
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/page_section_master" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Component Master </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/category_master" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Category Master </p>
                        </router-link>
                    </li>

                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/level_master" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Level Master </p>
                        </router-link>
                    </li>






                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/pages" :class="$route.path.startsWith('/admin/pages') ? 'active' : ''"
                            class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Pages
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/app/archive" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-folder-open"></i>
                            <p>Archive Data</p>
                        </router-link>
                    </li>

                    <li class="nav-item" v-if="role == 2">
                        <router-link to="/admin/settings" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Settings

                            </p>
                        </router-link>
                    </li>


                    <!-- Content Creator -->
                    <li class="nav-item" v-if="role == 3">
                        <router-link to="/contentcreator/dashboard" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard

                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="role == 3">
                        <router-link to="/contentcreator/pages"
                            :class="$route.path.startsWith('/contentcreator/pages') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Pages
                            </p>
                        </router-link>
                    </li>
                    <!-- <li class="nav-item" v-if="role == 3">
                            <router-link to="/contentcreator/menu"
                                :class="$route.path.startsWith('/contentcreator/menu') ? 'active' : ''"
                                class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Menu
                                </p>
                            </router-link>
                        </li> -->

                    <!-- Publisher -->
                    <li class="nav-item" v-if="role == 4">
                        <router-link to="/publisher/dashboard"
                            :class="$route.path.startsWith('/publisher/dashboard') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="role == 4">
                        <router-link to="/publisher/pages"
                            :class="$route.path.startsWith('/publisher/pages') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Pages
                            </p>
                        </router-link>
                    </li>
                     <li class="nav-item"v-if="[1, 2].includes(role)"   >
                        <router-link to="/app/auditlogs" active-class="active" class="nav-link">
                            <i class="nav-iconfas fas fa-user-shield"></i>
                            <p>
                                Audit Log   
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/app/activitylog" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                Activity Log
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <!-- <form method="POST" action="{{route('logout')}}" class="nav-link"> -->

                        <a class="nav-link" @click="logout">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                        <!-- </form> -->
                    </li>
                </ul>
            </nav>

        </div>

    </aside>

    <div class="content-wrapper">
        <router-view></router-view>
    </div>

    <!-- <aside class="control-sidebar control-sidebar-dark">

            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside> -->

    <footer class="main-footer">

        <div class="float-right d-none d-sm-inline">
            NIC, Meghalaya
        </div>

        <strong>Copyright &copy; NIC, Meghalaya <a href="https://meg.nic.in/" target="_blank">NIC
                Meghalaya</a>.</strong> All rights
        reserved.
    </footer>
    </div>

</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import useUser from '../user';
import Loader from './Loader.vue';
import { useRouter } from 'vue-router'
const router = useRouter()
const { user, role, website } = useUser();
const computedRole = computed(() => role.value);
const isLoading = ref(true);
const activityLogData = ref([])
const logout = () => {
    axios.post('/logout')
        .then(response => {
            sessionStorage.clear(); // Clear all sessionStorage items
            window.location.href = '/';
        })
        .catch(error => {
            console.error('Logout failed:', error);

        });
};
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

function goToActivityLog(id) {
    router.push({ path: '/app/activitylog', query: { id } })
}
onMounted(() => {
    getActivityLog();
    setTimeout(() => {
        isLoading.value = false;
    }, 500);
});
</script>