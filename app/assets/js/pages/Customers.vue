<template>
  <div class="container-fluid main-container">
    <div class="row content">
      <div class="col-sm-3 p-3 sidenav">
        <h4>Filters</h4>

        <FilterSide @update="onSearch" :config="config"/>
      </div>

      <div class="col-sm-9 p-3">
        <h4>Customers</h4>
        <Table @update="onSort" :customers="customers" :loading="loading" :config="config"/>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../components/Table";
import axios from "axios"
import FilterSide from "../components/FilterSide";

export default {
  name: "Customers",
  components: {FilterSide, Table},
  data() {
    return {
      config: {
        sort: {
          id: 'c.id',
          first_name: 'c.first_name',
          last_name: 'c.last_name',
          email: 'c.email',
          value: {}
        },
        filters: [
          {
            name: 'Filter by Country',
            type: 'select',
            key: 'country.name',
            options: [
              'Canada',
              'USA'
            ],
            value: null
          }
        ],
        search: {
          value: ''
        }
      }, //todo need config from backend
      customers: [],
      params: {
        s: null,
        o: null,
        f: null,
      },
      loading: false,
    }
  },
  mounted() {
    this.getCustomers();
  },
  methods: {
    onSearch(params) {
      this.params.s = params?.s;
      this.params.f = params?.f;
      this.getCustomers()
    },
    onSort(params) {
      this.params.o = params
      this.getCustomers()
    },
    getCustomers() {
      this.loading = true;
      let params = this.getParamsForQuery();
      console.log(params)
      axios
          .get('/api/customer', {params: params})
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