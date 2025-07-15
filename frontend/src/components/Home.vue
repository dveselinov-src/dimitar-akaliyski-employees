<template>
  <div>
    <h1>Employees cooperation analysis</h1>
    <hr>
    <p><b>Task: Pair of employees who have worked together</b></p>
    <p>Who are the two employees who cooperated the longest in this dataset?</p>
    <form @submit.prevent="handleSubmit">
      <input type="file" accept=".csv" @change="handleFileChange"/>
      <button type="submit" :disabled="!file">Upload .csv file</button>
    </form>
    <p v-if="error" style="color: red;">{{ error }}</p>
    <div v-if="result">
      <h2>Result</h2>
      <p>First Employee ID: {{ result['emp1'] }}</p>
      <p>Second Employee ID: {{ result['emp2'] }}</p>
      <p>Days working together on common projects: {{ result['days'] }}</p>
      <br/>
      <br/>
      <hr>
      <p><b>Bonus Task: Common Projects for employees {{ result['emp1'] }} and {{ result['emp2'] }}</b></p>
      <DataGrid :data="commonProjects"/>
    </div>
  </div>
</template>

<script>
import DataGrid from './DataGrid.vue';

export default {
  components: {DataGrid},
  data() {
    return {
      file: null,
      error: null,
      result: null,
      commonProjects: [],
    };
  },
  methods: {
    handleFileChange(event) {
      this.file = event.target.files[0];
      this.error = null;
    },
    async handleSubmit() {
      if (!this.file) {
        this.error = 'No file uploaded, or corrupt file!';
        return;
      }
      const formData = new FormData();
      formData.append('emp_coop_file', this.file);
      try {
        const response = await fetch('/api/index.php', {
          method: 'POST',
          body: formData,
        });
        const data = await response.json();
        if (response.ok) {
          this.result = data.result;
          this.commonProjects = data.result.commonProjects;
          this.error = null;
        } else {
          this.error = data.error;
          this.result = null;
          this.commonProjects = [];
        }
      } catch (error) {
        this.error = 'An error occurred while processing the file';
        this.result = null;
        this.commonProjects = [];
      }
    },
  },
};
</script>

<style scoped>
form {
  margin: 20px 0;
}

button {
  padding: 10px;
  background-color: blue;
  color: white;
  border: none;
  cursor: pointer;
}

button:disabled {
  background-color: red;
  cursor: not-allowed;
}
</style>