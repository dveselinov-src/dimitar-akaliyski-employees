<template>
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
</template>

<script>
export default {
  props: {
    data: {
      type: Array,
      required: true,
    },
  },
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