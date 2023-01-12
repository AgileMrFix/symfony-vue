<template>
  <tr>
    <th scope="row">{{customer.id}}</th>
    <td>{{customer.first_name}}</td>
    <td>{{customer.last_name}}</td>
    <td>{{customer.email}}</td>
    <td>{{customer.address}}</td>
  </tr>
</template>

<script>
export default {
  name: "Table",
  props: {
    customer: {},
  }
}
</script>

<style scoped>

</style>