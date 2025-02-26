<template>
  <div class="table-edit">
    <h3>Edit Table: {{ table.title }}</h3>
    <p>{{ table.description }}</p>

    <table class="table">
      <thead>
        <tr>
          <th v-for="(col, index) in table.columns" :key="index">{{ col }}</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, rowIndex) in table.rows" :key="rowIndex">
          <td v-for="(col, colIndex) in table.columns" :key="colIndex">
            <input v-model="row[col]" class="form-control" />
          </td>
          <td>
            <button @click="deleteRow(rowIndex)" class="btn btn-danger btn-sm">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <button @click="addRow" class="btn btn-primary mt-2">Add Row</button>
    <button @click="saveTable" class="btn btn-success mt-2">Save Changes</button>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const table = ref({
  title: '',
  description: '',
  columns: [],
  rows: []
});

const addRow = () => {
  const newRow = {};
  table.value.columns.forEach((col) => {
    newRow[col] = '';
  });
  table.value.rows.push(newRow);
};

const deleteRow = (index) => {
  table.value.rows.splice(index, 1);
};

const saveTable = () => {
  // Handle save operation here (e.g., API call to save the table data)
  console.log('Table saved:', table.value);
};

// Assuming `selectedTable` is passed as a prop or injected from parent
watch(() => selectedTable.value, (newTable) => {
  if (newTable) {
    table.value = JSON.parse(JSON.stringify(newTable)); // Deep copy to avoid direct mutation
  }
});
</script>

<style scoped>
.table-edit {
  margin-top: 1em;
}
.table th, .table td {
  padding: 0.5em;
}
</style>
