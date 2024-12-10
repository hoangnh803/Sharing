<template>
    <header class="admin-header flex items-center justify-between p-4 bg-gray-800 text-white">
        <div class="flex items-center">
            <router-link to="/admin" class="flex-shrink-0 flex items-center">
                <BookOpen class="h-8 w-8 " :class="[isScrolled ? 'text-black' : 'text-white']" />
                <span class="ml-2 text-xl font-bold "
                    :class="[isScrolled ? 'text-black' : 'text-white']">EduShare</span>
            </router-link>
        </div>
        <button @click="logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Đăng xuất
        </button>
    </header>
</template>

<script>
import api from '../services/api';
import { BookOpen } from 'lucide-vue-next';

export default {
    components: {
        BookOpen,
    },
    methods: {
        async logout() {
      try {
        await api.logout(); // Gọi API logout
        localStorage.removeItem("user"); // Xóa thông tin người dùng khỏi localStorage
        localStorage.removeItem("token"); // Xóa token khỏi localStorage
        this.$router.push("/"); // Chuyển hướng về trang chủ
      } catch (error) {
        console.error("Error during logout:", error);
        alert("Logout failed. Please try again.");
      }
    },
    },
};
</script>

<style scoped>
.admin-header {
    height: 60px;
}
</style>