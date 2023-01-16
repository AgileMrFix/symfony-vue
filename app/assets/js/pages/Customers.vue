<template>
  <div class="container-fluid main-container">
    <div class="row content">
      <div class="col-sm-3 p-3 sidenav">
        <h4>Filters</h4>

        <FilterSide @update="onSearch"/>
      </div>

      <div class="col-sm-9 p-3">
        <h4>Customers</h4>
        <Table @update="onSort" :customers="customers" :loading="loading"/>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../components/Table";
import FilterSide from "../components/FilterSide";
import axiosClient from "../../axios-client";

export default {
  name: "Customers",
  components: {FilterSide, Table},
  data() {
    return {
      customers: [],
      params: {
        search: null,
        order: null,
        ['address.country.id']: null,
      },
      loading: false,
    }
  },
  mounted() {
    this.getCustomers();
  },
  methods: {
    onSearch(params) {
      this.params.search = params?.s;
      this.params['address.country.id'] = params?.f;
      this.getCustomers()
    },
    onSort(params) {
      this.params.order = params
      this.getCustomers()
    },
    getCustomers() {
      this.loading = true;
      let params = this.getParamsForQuery();
      axiosClient
          .get('/api/customers', {params: params})
          .then(response => (this.customers = response.data)).finally(() => this.loading = false)
    },
    getParamsForQuery(){
      return Object.keys(this.params)
          .filter((k) => this.params[k] != null && this.params[k] !== '')
          .reduce((a, k) => ({ ...a, [k]: this.params[k] }), {});
    }
  }
}
</script>

<style scoped>

</style>