<template>
  <div class="table-responsive-md">
    <table class="table table-striped">
      <thead>
      <tr>
        <th class="active-cursor" v-on:click="sortBy('id')">ID <i
            class="bi" :class="arrowClass('id')"></i>
        </th>
        <th class="active-cursor" v-on:click="sortBy('first_name')" scope="col">First Name <i
            class="bi" :class="arrowClass('first_name')"></i>
        </th>
        <th class="active-cursor" v-on:click="sortBy('last_name')" scope="col">Last Name <i
            class="bi" :class="arrowClass('last_name')"></i>
        </th>
        <th class="active-cursor" v-on:click="sortBy('email')" scope="col">Email <i
            class="bi" :class="arrowClass('email')"></i>
        </th>
        <th scope="col">Address</th>
      </tr>
      </thead>
      <div v-if="loading">Loading...</div>
      <tbody v-if="!loading">
      <Row v-for="customer in customers" :customer="customer" :key="customer.id"/>
      </tbody>
    </table>

  </div>
</template>

<script>

import Row from "./Row";

export default {
  name: "Table",
  components: {Row},
  props: {
    customers: [],
    loading: false,
    // config: {},
  },
  data() {
    return {
      activeSortingBy: null,
      sortByAsc: true
    }
  },
  methods: {
    arrowClass(column) {
      if (this.sortByAsc && this.activeSortingBy === column) {
        return 'bi-arrow-down'
      } else if (this.activeSortingBy === column) {
        return 'bi-arrow-up'
      } else {
        return 'bi-arrow-down-up'
      }
    },
    sortBy(column) {
      if (this.activeSortingBy !== column) {
        this.sortByAsc = true;
        this.activeSortingBy = column
      } else {
        this.sortByAsc = !this.sortByAsc;
      }

      this.updateList(column, this.sortByAsc ? 'asc' : 'desc')

    },
    updateList(column, order) {
      this.$emit('update', {[column]: order});
    }
  }
}
</script>

<style scoped>

</style>