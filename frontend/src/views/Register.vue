<template>
  <main class="flex items-center justify-center min-h-screen bg-stone-100 px-8">
    <div class="overflow-hidden bg-cyan-900 rounded-3xl shadow-lg max-md:w-full max-md:mx-4">
      <div class="flex gap-8 max-md:flex-col">
        <!-- Logo và thông điệp -->
        <section class="flex flex-col w-1/3 text-white p-6 max-md:w-full">
          <router-link to="/" class="flex-shrink-0 flex items-center">
              <BookOpen class="h-8 w-8 " :class="[isScrolled ? 'text-black' : 'text-white']" />
              <span class="ml-2 text-xl font-bold " :class="[isScrolled ? 'text-black' : 'text-white']">EduShare</span>
            </router-link>
          <h1 class="text-2xl font-bold leading-tight tracking-wide">
            Đăng ký ngay và trải nghiệm những lợi ích tuyệt vời!
          </h1>
          <p class="mt-4 text-sm">
            Tạo tài khoản ngay và mở khóa những tính năng mới.
          </p>
        </section>

        <!-- Form đăng ký -->
        <section
          class="flex flex-col bg-white rounded-3xl p-8 text-stone-900 w-2/3 max-md:w-full max-md:p-6">
          <!-- Thông Báo Thành Công -->
          <div v-if="isRegistrationSuccess" class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
            <p class="font-bold">Chúc mừng!</p>
            <p>Bạn đã đăng ký thành công. <router-link to="/login" class="text-teal-600 underline">Đăng nhập ngay</router-link>.</p>
          </div>

          <h2 class="text-xl font-bold tracking-wide mb-6">Tạo tài khoản</h2>

          <!-- Các nút đăng ký nhanh -->
          <div class="flex flex-wrap gap-4">
            <button
              class="flex items-center gap-2 flex-1 border border-gray-300 rounded p-2 hover:bg-gray-50">
              <img loading="lazy"
                src="https://storage.googleapis.com/a1aa/image/OyuW5Njanw6gK1becS27wcvo2TfHHw2I09j3ZTWANE2ZV7vTA.jpg"
                alt="Google" class="h-5 w-5" />
              Đăng ký với Google
            </button>
            <button
              class="flex items-center gap-2 flex-1 border border-gray-300 rounded p-2 hover:bg-gray-50">
              <i class="fab fa-facebook-f text-blue-600"></i>
              Đăng ký với Facebook
            </button>
          </div>

          <div class="my-4 text-center text-sm text-gray-600">- OR -</div>

          <!-- Form nhập thông tin -->
          <form @submit.prevent="registerUser">
            <div class="mb-4">
              <label for="fullName" class="block text-sm font-medium mb-1">Họ và Tên</label>
              <input v-model="fullName" type="text" id="fullName"
                class="w-full border-b border-gray-300 focus:border-teal-500 focus:outline-none pb-1" required />
            </div>

            <div class="mb-4">
              <label for="email" class="block text-sm font-medium mb-1">Email</label>
              <input v-model="email" type="email" id="email"
                class="w-full border-b border-gray-300 focus:border-teal-500 focus:outline-none pb-1" required />
            </div>

            <div class="mb-4 relative">
              <label for="password" class="block text-sm font-medium mb-1">Mật khẩu</label>
              <input v-model="password" :type="passwordFieldType" id="password"
                class="w-full border-b border-gray-300 focus:border-teal-500 focus:outline-none pb-1 pr-10" required />
              <button type="button" @click="togglePasswordVisibility"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500">
                <EyeOffIcon v-if="passwordFieldType === 'password'" class="w-5 h-5" />
                <EyeIcon v-else class="w-5 h-5" />
              </button>
            </div>

            <div v-if="errorMessage" class="text-red-500 text-sm mt-2">
              {{ errorMessage }}
            </div>

            <div class="mt-6">
              <button type="submit"
                class="w-full bg-teal-600 text-white py-2 rounded hover:bg-teal-700">
                Đăng ký
              </button>
            </div>
          </form>

          <p class="mt-4 text-sm text-gray-600">
            Đã có tài khoản? <router-link to="/login" class="text-teal-600">Đăng nhập</router-link>
          </p>
        </section>
      </div>
    </div>
  </main>
</template>


<script>
import { BookOpen, Eye as EyeIcon, EyeOff as EyeOffIcon } from 'lucide-vue-next';
import api from '../services/api';

export default {
  name: "RegisterPage",
  components: {
    EyeIcon,
    EyeOffIcon,
    BookOpen,
  },
  data() {
    return {
      fullName: '',
      email: '',
      password: '',
      passwordFieldType: 'password',
      errorMessage: '', // Thông báo lỗi
      isRegistrationSuccess: false, // Kiểm tra trạng thái đăng ký thành công
    };
  },
  methods: {
    togglePasswordVisibility() {
      this.passwordFieldType =
        this.passwordFieldType === 'password' ? 'text' : 'password';
    },
    async registerUser() {
      this.errorMessage = '';

      if (!this.fullName || !this.email || !this.password) {
        this.errorMessage = 'Please fill in all fields.';
        return;
      }

      const userData = {
        name: this.fullName,
        email: this.email,
        password: this.password,
        password_confirmation: this.password, // Nếu cần xác nhận
      };

      try {
        await api.register(userData);
        this.isRegistrationSuccess = true; // Hiển thị thông báo thành công
      } catch (error) {
        this.errorMessage =
          error.response?.data?.message || 'Registration failed. Please try again.';
      }
    },
  },
};
</script>


<style scoped>
/* Add your styles here */
</style>