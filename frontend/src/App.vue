<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters(['isLoggedIn'])
  },
  methods: {
    ...mapActions(['logout'])
  }
}
</script>

<style>
/* Add your styles here */
</style>