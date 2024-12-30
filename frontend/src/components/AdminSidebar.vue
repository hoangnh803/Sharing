<template>
  <aside class="w-64 bg-gray-700 text-white top-16 min-h-screen">
    <nav class="p-4 space-y-1">
      <router-link
        to="/admin/dashboard"
        :class="{
          'bg-emerald-500 text-white': isActiveRoute('/admin/dashboard'),
          'text-gray-300 hover:bg-gray-600': !isActiveRoute('/admin/dashboard'),
        }"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg"
      >
        <i class="fas fa-tachometer-alt w-5 h-5"></i>
        <span>Dashboard</span>
      </router-link>

      <router-link
        to="/admin/users"
        :class="{
          'bg-emerald-500 text-white': isActiveRoute('/admin/users'),
          'text-gray-300 hover:bg-gray-600': !isActiveRoute('/admin/users'),
        }"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg"
      >
        <i class="fas fa-users w-5 h-5"></i>
        <span>Users</span>
      </router-link>

      <router-link
        to="/admin/documents"
        :class="{
          'bg-emerald-500 text-white': isActiveRoute('/admin/documents'),
          'text-gray-300 hover:bg-gray-600': !isActiveRoute('/admin/documents'),
        }"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg"
      >
        <i class="fas fa-file-alt w-5 h-5"></i>
        <span>Documents</span>
      </router-link>

      <router-link
        to="/admin/notifications"
        :class="{
          'bg-emerald-500 text-white': isActiveRoute('/admin/notifications'),
          'text-gray-300 hover:bg-gray-600': !isActiveRoute('/admin/notifications'),
        }"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg"
      >
        <i class="fas fa-bell w-5 h-5"></i>
        <span>Notifications</span>
      </router-link>
      <router-link
        to="/admin/university"
        :class="{
          'bg-emerald-500 text-white': isActiveRoute('/admin/university'),
          'text-gray-300 hover:bg-gray-600': !isActiveRoute('/admin/university'),
        }"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg"
      >
        <i class="fas fa-bell w-5 h-5"></i>
        <span>University</span>
      </router-link>
      <router-link
        to="/admin/class"
        :class="{
          'bg-emerald-500 text-white': isActiveRoute('/admin/class'),
          'text-gray-300 hover:bg-gray-600': !isActiveRoute('/admin/class'),
        }"
        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg"
      >
        <i class="fas fa-bell w-5 h-5"></i>
        <span>Class</span>
      </router-link>
    </nav>
  </aside>
</template>

<script>
export default {
  methods: {
    isActiveRoute(route) {
      return this.$route.path === route;
    },
  },
};
</script>

<style scoped>
/* Sidebar styles */
aside {
  top: 4rem; /* Header height (64px = 4rem) */
  left: 0;
  overflow-y: auto;
}
</style>
