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
            :class="['!px-4 !text-gray-500 !hover:text-gray-900']" as="router-link"  to="/class"/>
          <Button label="Tải lên" variant="text" @click="handleUploadClick"
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
  <!-- Popup thông báo -->
  <div v-if="showLoginModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <h3 class="text-lg font-medium leading-6 text-gray-900">Thông báo</h3>
      <p class="mt-2">Bạn cần phải đăng nhập để tiếp tục!</p>
      <div class="mt-4 flex justify-end">
        <button @click="showLoginModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">Đóng</button>
        <button @click="redirectToLogin" class="bg-blue-500 text-white px-4 py-2 rounded-md">Đăng nhập</button>
      </div>
    </div>
  </div>
</template>

  
<script setup>
import SearchComponent from '../components/SearchComponent.vue';
import { BookOpen,  } from 'lucide-vue-next';
import Button from 'primevue/button';
import { ref, onMounted } from 'vue';
import NotificationDropdown from './NotificationDropdown.vue';
import UserDropdown from './UserDropdown.vue';
import { useRouter } from 'vue-router';

const isLoggedIn = ref(false); // Trạng thái đăng nhập

// Kiểm tra token trong localStorage
onMounted(() => {
  const token = localStorage.getItem('token');
  isLoggedIn.value = !!token; // Nếu token tồn tại, chuyển trạng thái đăng nhập thành true
});
const showLoginModal = ref(false);
const router = useRouter();

const handleUploadClick = () => {
  if (!isLoggedIn.value) {
    showLoginModal.value = true;
    return;
  }
  router.push({ path: '/upload' });
};

const redirectToLogin = () => {
  router.push({ name: 'Login' });
};

function handleSearchQuery(query) {
  console.log(query);
}



</script>


  
  <style scoped>
  nav {
    height: 64px; /* Cố định chiều cao */
  }
  </style>
  