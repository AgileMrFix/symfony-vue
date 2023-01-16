<template>
  <div>
    <div class="d-grid gap-2 my-3">
        <label for="country-filter" class="form-label">Filter by Country</label>
        <select v-model="filter" class="form-select" aria-label="country-filter">
          <option :value="null" selected>Filter...</option>
          <option v-for="option in countries" :value='option.id'>{{ option.name }}</option>
        </select>
    </div>

    <input v-model="search" type="text" class="form-control mb-3" placeholder="Search">

    <div class="d-grid gap-2">
      <button @click="updateList" class="btn btn-primary mb-3" type="button"> Prepare</button>
    </div>
  </div>
</template>

<script>
import axiosClient from "../../axios-client";

export default {
  name: "FilterSide",
  props: {
  },
  data(){
    return {
      search: '',
      filter: null,
      countries: {}
    }
  },
  mounted() {
    this.getCountries();
  },
  methods: {
    updateList(){
      this.$emit('update', {s: this.search, f: this.filter})
    },
    getCountries() {
      axiosClient
          .get('/api/countries')
          .then(response => (this.countries = response.data))
    },

  }

}
</script>

<style scoped>

</style>