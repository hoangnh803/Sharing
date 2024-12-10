<template>
  <nav class="border-b border-white/10 fixed w-full top-0 z-50 transition-colors duration-300 bg-[#f9faff]">
    <div class="max-w-7xl mx-auto p-2">
      <div class="flex h-12 items-center justify-between">
        <!-- Logo và Search Bar -->
        <div class="flex items-center space-x-8">
          <router-link to="/" class="flex-shrink-0 flex items-center">
            <BookOpen class="h-8 w-8 text-black" />
            <span class="ml-2 text-xl font-bold text-black">EduShare</span>
          </router-link>
          <!-- Search Bar -->
          <div class="hidden md:block flex-grow mx-4">
            <SearchComponent is-small @search-query="handleSearchQuery" />
          </div>
        </div>
        <!-- Menu -->
        <div class="hidden md:flex items-center space-x-8">
          <Button label="Đại học" :severity="info" variant="text" rounded
            :class="['!px-4 !text-gray-500 !hover:text-gray-900']" as="router-link"  to="/university"/>
          <Button label="Phổ thông" variant="text" rounded
            :class="['!px-4 !text-gray-500 !hover:text-gray-900']" as="router-link"  to="/university"/>
          <Button label="Tài lên" variant="text" as="router-link"  to="/upload"
            :class="['!px-4 !text-gray-500 !hover:text-gray-900']" />

          <!-- Hiển thị Avatar và Chuông nếu đã đăng nhập -->
          <template v-if="isLoggedIn">
            <NotificationDropdown/>
            <!-- Avatar -->
            <UserDropdown/>
          </template>

          <!-- Nút Sign In nếu chưa đăng nhập -->
          <Button
            v-else
            label="Đăng nhập"
            severity="success"
            rounded
            as="router-link"
            to="/login"
          />
        </div>
      </div>
    </div>
  </nav>
</template>

  
<script setup>
import SearchComponent from '../components/SearchComponent.vue';
import { BookOpen,  } from 'lucide-vue-next';
import Button from 'primevue/button';
import { ref, onMounted } from 'vue';
import NotificationDropdown from './NotificationDropdown.vue';
import UserDropdown from './UserDropdown.vue';

const isLoggedIn = ref(false); // Trạng thái đăng nhập

// Kiểm tra token trong localStorage
onMounted(() => {
  const token = localStorage.getItem('token');
  isLoggedIn.value = !!token; // Nếu token tồn tại, chuyển trạng thái đăng nhập thành true
});

function handleSearchQuery(query) {
  console.log(query);
}



</script>


  
  <style scoped>
  nav {
    height: 64px; /* Cố định chiều cao */
  }
  </style>
  