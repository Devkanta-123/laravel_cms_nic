<template>
    <div class="container mt-4 scrollable-content">
      <!-- Step 1: Enter Table Title and Description -->
      <div v-if="step === 1">
        <div class="mb-3">
          <label for="tableTitle" class="form-label">Table Title:</label>
          <input 
            type="text" 
            v-model="tableTitle" 
            class="form-control" 
            id="tableTitle" 
            placeholder="Enter Table Title" 
          />
        </div>
        <div class="mb-3">
          <label for="tableDescription" class="form-label">Table Description:</label>
          <textarea 
            v-model="tableDescription" 
            class="form-control" 
            id="tableDescription" 
            rows="3" 
            placeholder="Enter Table Description">
          </textarea>
        </div>
        <div class="mb-3">
          <label for="hindiTitle" class="form-label">hindi Title:</label>
          <input 
            type="text" 
            v-model="hindiTitle" 
            class="form-control" 
            id="hindiTitle" 
            placeholder="Enter hindi Title" 
          />
        </div>
        <div class="mb-3">
          <label for="khasiTitle" class="form-label">khasi Title:</label>
          <input 
            type="text" 
            v-model="khasiTitle" 
            class="form-control" 
            id="khasiTitle" 
            placeholder="Enter khasi Title" 
          />
        </div>
        <div class="mb-3">
          <label for="otherTitle" class="form-label">other Title:</label>
          <input 
            type="text" 
            v-model="otherTitle" 
            class="form-control" 
            id="otherTitle" 
            placeholder="Enter other Title" 
          />
        </div>
        <div class="mb-3">
          <label for="hindiDescription" class="form-label">hindi Description:</label>
          <textarea 
            v-model="hindiDescription" 
            class="form-control" 
            id="hindiDescription" 
            rows="3" 
            placeholder="Enter hindi Description">
          </textarea>
        </div>
        <div class="mb-3">
          <label for="khasiDescription" class="form-label">khasi Description:</label>
          <textarea 
            v-model="khasiDescription" 
            class="form-control" 
            id="khasiDescription" 
            rows="3" 
            placeholder="Enter khasi Description">
          </textarea>
        </div>
        <div class="mb-3">
          <label for="otherDescription" class="form-label">other Description:</label>
          <textarea 
            v-model="otherDescription" 
            class="form-control" 
            id="otherDescription" 
            rows="3" 
            placeholder="Enter other Description">
          </textarea>
        </div>

        <button class="btn btn-primary" @click="step = 2">Next: Define Columns</button>
      </div>
  
      <!-- Step 2: Enter Number of Columns -->
      <div v-if="step === 2">
        <div class="mb-3">
          <label for="columnCount" class="form-label">Enter number of columns:</label>
          <input 
            type="number" 
            v-model="columnCount" 
            min="1" 
            class="form-control" 
            id="columnCount" 
            @input="initializeColumns" 
          />
          <div v-if="errors.columnCount" class="text-danger">{{ errors.columnCount }}</div>
        </div>
        <button class="btn btn-secondary" @click="step = 1">Back</button>
        <button class="btn btn-primary" @click="validateStep2">Next</button>
      </div>
  
      <!-- Step 3: Enter Column Names -->
      <div v-if="step === 3">
        <h3 class="mb-3">Enter Column Names</h3>
        <div v-for="(column, index) in columns" :key="index" class="mb-3">
          <input
            v-model="columns[index].name"
            :placeholder="'Column ' + (index + 1) + ' name'"
            type="text"
            class="form-control"
          />
        </div>
        <div class="d-flex justify-content-between">
          <button class="btn btn-secondary" @click="step = 2">Back</button>
          <button class="btn btn-primary" @click="step = 4">Next: Add Rows</button>
        </div>
      </div>
  
      <!-- Step 4: Add and Manage Rows -->
      <div v-if="step === 4">
        <h3 class="mb-3">Add Rows</h3>
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th v-for="(column, index) in columns" :key="index">
                {{ column.name }}
              </th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
              <td v-for="(column, colIndex) in columns" :key="colIndex">
                <input v-model="row[colIndex]" type="text" class="form-control" />
              </td>
              <td>
                <button class="btn btn-danger" @click="deleteRow(rowIndex)">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="d-flex justify-content-between">
          <button class="btn btn-secondary" @click="step = 3">Back</button>
          <button class="btn btn-success" @click="addRow">Add Row</button>
        </div>
        <button class="btn btn-primary mt-3" @click="submitTable">Submit Table</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const step = ref(1);
  const tableTitle = ref('');
  const hindiTitle = ref('');
  const khasiTitle = ref('');
  const otherTitle = ref('');
  const tableDescription = ref('');
  const hindiDescription = ref('');
  const khasiDescription = ref('');
  const otherDescription = ref('');
  const columnCount = ref(0);
  const columns = ref([]);
  const rows = ref([]);
  const errors=ref({})
  
  const initializeColumns = () => {
    if (columnCount.value > 0) {
      columns.value = Array.from({ length: columnCount.value }, () => ({ name: '' }));
      rows.value = []; // Reset rows if column count changes
      step.value = 3;
    }
  };
  
  const addRow = () => {
    rows.value.push(Array(columnCount.value).fill(''));
  };
  
  const deleteRow = (rowIndex) => {
    rows.value.splice(rowIndex, 1);
  };
  
  const submitTable = () => {
    const tableData = {
      title: tableTitle.value,
      hindititle: hindiTitle.value,
      khasititle: khasiTitle.value,
      othertitle: otherTitle.value,
      description: tableDescription.value,
      hindidescription: hindiDescription.value,
      khasidescription: khasiDescription.value,
      otherdescription: otherDescription.value,
      columns: columns.value.map(column => column.name),
      rows: rows.value,
    };
  
    // Pass the tableData as JSON to your controller
    console.log(JSON.stringify(tableData));
  
    // Example: you can use an Axios POST request to send data to the server
    axios.post('/api/save_table_info', tableData).then(response => {
      // handle success
    }).catch(error => {
      // handle error
    });
  };

  const validateStep2=()=>{
    if(columnCount.value<1){
        errors.value.columnCount='No. of Column cannot be 0 or less';
    }
    else{
        errors.value.columnCount='';
        step.value=3;
    }
  }
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
  }
  
  .table {
    margin-top: 1em;
  }
  
  .table-bordered th,
  .table-bordered td {
    text-align: center;
    vertical-align: middle;
  }
  
  .btn {
    margin-top: 1em;
  }

  .scrollable-content {
    max-height: 400px;
    overflow: auto;
  }
  </style>
  