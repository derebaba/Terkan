<template>
  <div>
    <span>{{ item.english_name }}</span>
    <i>{{item.name}}</i>
  </div>
</template>

<script>
export default {
  props: {
    item: { required: true },
    searchText: { required: true }
  }
}
</script>