<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <AdminHeader />

    <!-- Main Layout -->
    <div class="flex-1 flex overflow-hidden ">
      <!-- Sidebar -->
      <AdminSidebar />

      <!-- Content Area -->
      <div class="flex-1 overflow-y-auto p-4">
        <router-view /> <!-- Dynamic Content -->
      </div>
    </div>
  </div>
</template>

<script>
import AdminHeader from '../components/AdminHeader.vue';
import AdminSidebar from '../components/AdminSidebar.vue';

export default {
  components: {
    AdminHeader,
    AdminSidebar,
  },
};
</script>

<style scoped>
.flex-1 {
  overflow-y: auto;
  min-height: calc(100vh - 100px); /* Adjust for header height */
}
</style>
