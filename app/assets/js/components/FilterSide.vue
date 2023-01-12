<template>
  <div>
    <div v-for="filter in config.filters" class="d-grid gap-2 my-3">
      <div v-show="filter.type === 'select'" >
        <label :for="filter.name" class="form-label">{{ filter.name }}</label>
        <select v-model="filters[filter.key]" class="form-select" :aria-label="filter.name">
          <option :value="null" selected>Filter...</option>
          <option v-for="option in filter.options" :value='option'>{{ option }}</option>
        </select>
      </div>
    </div>

    <input v-model="search" type="text" class="form-control mb-3" placeholder="Search">

    <div class="d-grid gap-2">
      <button @click="updateList" class="btn btn-primary mb-3" type="button"> Prepare</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "FilterSide",
  props: {
    config: {}
  },
  data(){
    return {
      search: '',
      filters: {}
    }
  },
  methods: {
    updateList(){
      console.log(this.getFilterData())
      this.$emit('update', {s: this.search, f: this.getFilterData()})
    },
    getFilterData(){
      return Object.keys(this.filters)
          .filter((k) => this.filters[k] != null)
          .reduce((a, k) => ({ ...a, [k]: this.filters[k] }), {});
    }

  }

}
</script>

<style scoped>

</style>