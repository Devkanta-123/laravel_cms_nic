<template>
  <br>
  <div class="content ml-4 mr-4">
    <div class="row">
      <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
          <div class="card-body">
            <div class="table-responsive mt-15">

              <table id="menuTable" class="table display center-aligned-table mb-0">
                <thead>
                  <tr class="text-dark">
                    <th>Sl .No</th>
                    <th>Menu Name</th>
                    <th>Menu Type</th>
                    <th>Order</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(menu, index) in hierarchicalMenus" :key="menu.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ menu.menu_name }}</td>
                    <td>{{ menu.menu_type }}</td>
                    <td>{{ menu.order }}</td>
                    <td class="">
                      <a href="#" @click.prevent="editMenu(menu)" class="btn btn-primary shadow btn-xs me-1">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
</template>
<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router';
const router = useRouter();

const menus = ref([])

// 👇 Process and flatten menu hierarchy
const hierarchicalMenus = computed(() => {
  const mainMenus = menus.value
    .filter(menu => menu.parent === 0)
    .sort((a, b) => a.order - b.order)

  const allMenus = []

  mainMenus.forEach(main => {
    allMenus.push({
      ...main,
      menu_type: main.menu_master?.menu_name || 'Main Menu'
    })

    // Add submenus of the current main menu
    const subMenus = menus.value
      .filter(sub => sub.parent === main.id)
      .sort((a, b) => a.order - b.order)

    subMenus.forEach(sub => {
      allMenus.push({
        ...sub,
        menu_type: `${main.menu_name} → ${sub.menu_name}`
      })
    })
  })

  return allMenus
})

const getPageSection = async () => {
  try {
    const response = await axios.get('/api/get_menus')
    menus.value = response.data[0] || []
    setTimeout(() => {
      $('#menuTable').DataTable({
        destroy: true,
        paging: true,
        searching: true,
        ordering: true
      })
    }, 100) // short delay for DOM update
  } catch (error) {
    console.error('Error fetching menus:', error)
  }
}
const editMenu = (menu) => {
  router.push({
    name: 'CCPagesForm',
    params: { menuId: menu.id, menuName: menu.menu_name }
  });
};


onMounted(() => {
  getPageSection()
})// Reinitialize DataTable when data changes


</script>