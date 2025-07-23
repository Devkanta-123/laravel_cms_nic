<template>
  <div class="container mt-3">
    <div class="tabs">
      <div class="tab-item" :class="{ active: selectedTab === 'add' }" @click="selectedTab = 'add'">
        Add Card
      </div>
      <div class="tab-item" :class="{ active: selectedTab === 'manage' }" @click="selectedTab = 'manage'">
        Manage Cards
      </div>

    </div>

    <div class="tab-content">
      <div v-show="selectedTab === 'add'">
        <div class="card p-4 shadow-sm">
          <!-- <h4 class="mb-4 text-primary">Add New Card</h4> -->
          <form @submit.prevent="addCard" enctype="multipart/form-data">
            <div class="row">
              <!-- Left Column: Basic Info -->
              <div class="col-md-7">
                <div class="mb-3">
                  <label class="form-label">Card Title <span class="text-danger">*</span></label>
                  <input type="text" v-model="newCard.card_title" class="form-control" placeholder="Enter Card Title"
                    required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Card Description</label>
                  <textarea v-model="newCard.card_description" class="form-control" rows="2"
                    placeholder="Enter Card Description"></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Card Logo</label>
                  <input type="file" @change="handleFileChange" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                  <label class="form-label">Link to another page</label>
                  <select class="form-control" v-model="newCard.more_link">
                    <option value="" disabled selected>Select the page menu</option>
                    <option v-for="pagemenu in pagemenudata" :key="pagemenu.id" :value="pagemenu.id">
                      {{ pagemenu.menu_name }}
                    </option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">External Link</label>
                  <input type="text" v-model="newCard.ex_link" class="form-control" placeholder="Enter Link (optional)">
                </div>
                <div class="mb-3">
                  <label class="form-label">Card Sort Order<span class="text-danger">*</span></label>
                  <input type="number" v-model="newCard.order" class="form-control" placeholder="Enter Order Number"
                    required>
                </div>
              </div>

              <!-- Right Column: Advanced Info (Toggle) -->
              <div class="col-md-5">
                <p>More Languages</p>
                <div class="advanced-fields ">
                  <div class="mb-3">
                    <label class="form-label">Hindi Title</label>
                    <input type="text" v-model="newCard.hindi_title" class="form-control" placeholder="Hindi Title">
                    <textarea v-model="newCard.hindi_description" class="form-control mt-2" rows="2"
                      placeholder="Hindi Description"></textarea>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Khasi Title</label>
                    <input type="text" v-model="newCard.khasi_title" class="form-control" placeholder="Khasi Title">
                    <textarea v-model="newCard.khasi_description" class="form-control mt-2" rows="2"
                      placeholder="Khasi Description"></textarea>
                  </div>


                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4 w-100">Add Card</button>
          </form>
        </div>
      </div>
      <div v-show="selectedTab === 'manage'">

        <table ref="dataTable" v-if="items.length" class="table table-striped display" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Description</th>
              <th>Status</th>
              <th>Added By</th>
              <th>Logo</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td>{{ item.card_title }}</td>
              <td>{{ item.card_description }}</td>
              <td>
                <label :class="item.flag === 'A' ? 'badge bg-success' : 'badge bg-warning'">
                  {{ item.flag === 'A' ? 'Approved' : 'Pending' }}
                </label>
              </td>
              <td>{{ item.addedby }}</td>
              <td>
                <img class="direct-chat-img" :src="`/storage/${item.image}`" alt="Slide Image"
                  @click="openModal(`/storage/${item.image}`)" style="cursor: pointer;" />
              </td>
              <td>
                <a href="#" @click.prevent="deleteCard(item.id)">
                  <i class="fa fa-trash text-danger"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="modal fade" id="viewImage" tabindex="-1" role="dialog" aria-hidden="true"
          :class="{ show: showModal }" :style="{ display: showModal ? 'block' : 'none' }">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header align-items-start">
                <div class="modal-title">
                  <div class="mb-30">
                    <div class="blog-box blog-2">
                      <img class="img-fluid w-100" :src="modalImage" alt="Modal Image" />
                      <div class="blog-info pt-10"></div>
                    </div>
                  </div>
                </div>
                <button data-v-73c5a58b="" type="button" class="close" @click="closeModal">×</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>





<script setup>
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';
const route = useRoute();
import { ref, defineProps, onMounted,nextTick} from 'vue';
import { useToastr } from '../../toaster.js';
import axios from 'axios';
const pagemenudata = ref([]); // Store fetched categories
const props = defineProps({
  menu: String,
  section: Number,
});
const showModal = ref(false);
const modalImage = ref('');
const openModal = (imageSrc) => {
  modalImage.value = imageSrc;
  showModal.value = true;
}
const closeModal = () => {
  showModal.value = false;
};
const dataTable = ref(null);
const items = ref([]); // Initialize an empty array to hold the data
const toastr = useToastr();
import $ from 'jquery';
import 'datatables.net'; // Make sure jQuery and DataTables are installed
let dtInstance = null;
const newCard = ref({
  card_title: '',
  card_description: '',
  image: null,
  more_link: '',
  ex_link: '',
  order: '',
  hindi_title: '',
  khasi_title: '',
  other_title: '',
  hindi_description: '',
  khasi_description: '',
  other_description: ''
});

const handleFileChange = (event) => {
  newCard.value.image = event.target.files[0];
};

const addCard = async () => {
  const formData = new FormData();
  for (const key in newCard.value) {
    formData.append(key, newCard.value[key]);
  }
  formData.append("menu", props.menu);
  formData.append("page_section", props.section.page_section_id);

  try {
    const response = await axios.post('/api/save_card', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    toastr.success(response.data.message);

    // Reset form
    newCard.value = {
      card_title: '',
      card_description: '',
      image: null,
      more_link: '',
      ex_link: '',
      order: '',
      hindi_title: '',
      khasi_title: '',
      other_title: '',
      hindi_description: '',
      khasi_description: '',
      other_description: ''
    };

    // Re-fetch items to update table
    await fetchData();
  } catch (error) {
    toastr.error('An error occurred while saving the card.');
  }
};
const getAllPageMenu = async () => {
  try {
    const response = await axios.get('/get_allpagemenu');
    pagemenudata.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error.response || error);
    toastr.error("Failed to load categories.");
  }
};
const fetchData = async () => {
  try {
    const response = await axios.get('/get_cards');
    items.value = response.data.data;

    // Re-draw DataTable after data update
    refreshDataTable();
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
function refreshDataTable() {
  // Destroy existing table if needed
  if (dtInstance) {
    dtInstance.destroy();
    dtInstance = null;
  }

  nextTick(() => {
    dtInstance = $(dataTable.value).DataTable({
      paging: true,
      searching: true,
      destroy: true
    });
  });
}
const deleteCard = (id) => {
  if (confirm('Are you sure you want to delete this item?')) {
    axios.post('/api/deleted_card', {
      id: id,
      menu_id: route.params.menuId,
      page_section_master_id: props.section.page_section_id
    })
      .then(response => {
        console.log('Cards deleted successfully:', response.data.message);
        toastr.success(response.data.message);

        items.value = response.data.data; // Update the items list with updated response
        refreshDataTable(); // Re-render DataTable with new data
      })
      .catch(error => {
        console.error('Error deleting news:', error);
        toastr.error('An error occurred while deleting the news.');
      });
  }
};

onMounted(() => {
  getAllPageMenu();
  fetchData()
})

const selectedTab = ref('add');
</script>

<style scoped>
.tabs {
  display: flex;
  margin-bottom: 1em;
}

.tab-item {
  margin-right: 1em;
  padding: 0.5em 1em;
  cursor: pointer;
  background-color: #eee;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.tab-item.active {
  background-color: #42b983;
  color: white;
}

.tab-item:hover {
  background-color: #ddd;
}

.tab-content {
  border: 1px solid #eee;
  padding: 1em;
}
</style>