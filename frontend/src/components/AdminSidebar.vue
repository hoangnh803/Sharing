<template>
    <aside class="admin-sidebar w-64  bg-gray-700 text-white">
      <nav class="p-4">
        <ul>
          <li class="mb-4">
            <router-link to="/admin/dashboard" class="block py-2 px-4 rounded hover:bg-gray-600">
              Dashboard
            </router-link>
          </li>
          <li class="mb-4">
            <router-link to="/admin/users" class="block py-2 px-4 rounded hover:bg-gray-600">
              Users
            </router-link>
          </li>
          <li class="mb-4">
            <router-link to="/admin/documents" class="block py-2 px-4 rounded hover:bg-gray-600">
              Documents
            </router-link>
          </li>
          <li class="mb-4">
            <router-link to="/admin/university" class="block py-2 px-4 rounded hover:bg-gray-600">
              University
            </router-link>
          </li>
          <li class="mb-4">
            <router-link to="/admin/class" class="block py-2 px-4 rounded hover:bg-gray-600">
              Class
            </router-link>
          </li>
          <!-- Add more menu items here -->
        </ul>
      </nav>
    </aside>
  </template>
  
  <script>
  export default {};
  </script>
  
  <style scoped>
  .admin-sidebar {
    min-height: calc(100vh - 60px); /* Adjust height based on header height */
  }
  </style>