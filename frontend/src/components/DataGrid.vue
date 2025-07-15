<template>
  <p>Solution 1: HTML table</p>
  <table>
    <thead>
    <tr>
      <th>First Employee ID</th>
      <th>Second Employee ID</th>
      <th>Project ID</th>
      <th>Days Worked</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(row, index) in data" :key="index">
      <td>{{ row[0] }}</td>
      <td>{{ row[1] }}</td>
      <td>{{ row[2] }}</td>
      <td>{{ row[3] }}</td>
    </tr>
    </tbody>
  </table>
  <div>
    <p>Solution 2: AG DataGrid plugin (can order by column)</p>
    <ag-grid-vue
        :rowData="rowData"
        :columnDefs="colDefs"
        style="height: 500px"
    >
    </ag-grid-vue>
  </div>
</template>

<script>
import {AgGridVue} from "ag-grid-vue3";
import {ref} from "vue"; // Vue Data Grid Component
export default {
  props: {
    data: {
      type: Array,
      required: true,
    },
  },
  name: "App",
  components: {
    AgGridVue, // Add Vue Data Grid component
  },
  setup(props) {
    const rowData = [];
    props.data.forEach((item) => {
      rowData.push({emp1: item[0], emp2: item[1], project: item[2], days: item[3]});
    });

    // Column Definitions: Defines the columns to be displayed.
    const colDefs = ref([
      {field: "emp1"},
      {field: "emp2"},
      {field: "project"},
      {field: "days"}
    ]);
    return {
      rowData,
      colDefs,
    };
  }
};
</script>

<style scoped>
table {
  width: 100%;
}

th, td {
  border: 1px solid black;
  padding: 10px;
  text-align: left;
}

th {
  background-color: gray;
}
</style>