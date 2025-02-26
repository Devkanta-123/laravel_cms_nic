<template>
  <div class="container mt-4 scrollable-content">
    <h3 class="mb-3" v-if="!isEditing">Manage Tables</h3>

    <div v-if="!isEditing">
      <div v-if="tables.length === 0" class="alert alert-warning">No tables available.</div>
      <ul class="list-group">
        <li
          v-for="(table, index) in tables"
          :key="index"
          class="list-group-item d-flex justify-content-between align-items-center"
        >
          <div>
            <strong>{{ table.table_title }}</strong><br />
            <small>{{ table.table_description }}</small>
          </div>
          <button class="btn btn-primary btn-sm" @click="editTable(index)">Edit</button>
        </li>
      </ul>
    </div>

    <!-- Edit Table Section -->
    <div v-if="isEditing">
      <h3>Edit Table: {{ editedTable.table_title }}</h3>
      <form @submit.prevent="saveChanges">
        <!-- Edit Table Title and Description -->
        <div class="form-group mb-3">
          <label for="title">Title</label>
          <input v-model="editedTable.table_title" type="text" id="title" class="form-control" />
        </div>
        <div class="form-group mb-3">
          <label for="description">Description</label>
          <input v-model="editedTable.table_description" type="text" id="description" class="form-control" />
        </div>

        <!-- Edit Table Headers -->
        <div class="form-group mb-3">
          <label>Table Headers</label>
          <div v-for="(column, colIndex) in parsedColumns" :key="colIndex" class="mb-2 d-flex">
            <input
              v-model="parsedColumns[colIndex]"
              type="text"
              class="form-control me-2"
              :placeholder="'Column ' + (colIndex + 1)"
            />
            <button class="btn btn-danger" type="button" @click="removeColumn(colIndex)">Remove</button>
          </div>
          <button class="btn btn-outline-primary mt-3" type="button" @click="addColumn">Add Column</button>
        </div>

        <!-- Edit Table Rows -->
        <div class="form-group">
          <label>Rows</label>
          <div v-for="(row, rowIndex) in parsedRows" :key="rowIndex" class="row mb-2 align-items-center">
            <div v-for="(column, colIndex) in parsedColumns" :key="colIndex" class="col">
              <input
                v-model="parsedRows[rowIndex][colIndex]"
                type="text"
                class="form-control"
                :placeholder="parsedColumns[colIndex]"
              />
            </div>
            <div class="col-auto">
              <button class="btn btn-danger" @click.prevent="removeRow(rowIndex)">Delete Row</button>
            </div>
          </div>
          <button class="btn btn-outline-primary mt-3" @click.prevent="addRow">Add Row</button>
        </div>

        <button class="btn btn-success mt-3" >Save</button>
        <button class="btn btn-secondary mt-3" @click="cancelEdit">Cancel</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const tables = ref([]);

// Function to fetch data from the API
const fetchData = async () => {
  try {
    const response = await axios.post('/api/get_tables'); // Replace with your API endpoint
    tables.value = response.data.data; // Assign the fetched data to the tables array
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const isEditing = ref(false);
const editedTable = ref(null);
const parsedColumns = ref([]);
const parsedRows = ref([]);

const editTable = (index) => {
  editedTable.value = JSON.parse(JSON.stringify(tables.value[index])); // Clone the table to edit
  parsedColumns.value = JSON.parse(editedTable.value.table_columns); // Parse the columns from JSON string
  parsedRows.value = JSON.parse(editedTable.value.table_data); // Parse the rows from JSON string
  isEditing.value = true;
};

const saveChanges = async () => {
  // Update the original table data with edited values
  editedTable.value.table_columns = JSON.stringify(parsedColumns.value); // Convert columns to JSON string
  editedTable.value.table_data = JSON.stringify(parsedRows.value); // Convert rows to JSON string

  const tableIndex = tables.value.findIndex(t => t.id === editedTable.value.id);
  if (tableIndex !== -1) {
    tables.value[tableIndex] = editedTable.value;
  }

  // Here you can make an API call to save the changes to the backend
  try {
    await axios.post('/api/update_table', editedTable.value); // Replace with your API endpoint
  } catch (error) {
    console.error('Error saving changes:', error);
  }

  isEditing.value = false;
};

const cancelEdit = () => {
  isEditing.value = false;
  editedTable.value = null;
  parsedColumns.value = [];
  parsedRows.value = [];
};

const addRow = () => {
  const newRow = parsedColumns.value.map(() => '');
  parsedRows.value.push(newRow);
};

const removeRow = (rowIndex) => {
  parsedRows.value.splice(rowIndex, 1);
};

const addColumn = () => {
  parsedColumns.value.push('New Column');
  parsedRows.value.forEach(row => row.push(''));
};

const removeColumn = (colIndex) => {
  parsedColumns.value.splice(colIndex, 1);
  parsedRows.value.forEach(row => row.splice(colIndex, 1));
};

onMounted(() => {
  fetchData();
});

</script>

<style scoped>
.scrollable-content {
  max-height: 400px;
  overflow: auto;
}
</style>
