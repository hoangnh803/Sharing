<template>
    <div class="admin-layout">
      <AdminHeader />
      <div class="flex">
        <AdminSidebar />
        <div class="content flex-1 p-4">
          <router-view />
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
  .admin-layout {
    display: flex;
    flex-direction: column;
    height: 100vh;
  }
  
  .content {
    overflow-y: auto;
  }
  </style>