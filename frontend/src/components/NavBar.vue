<template>
    <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse pl-16" ref="navBarContainer">
      <!-- User Menu Button -->
      <button 
        v-if="isLoggedIn"
        type="button" 
        class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
        @click="toggleUserMenu"
        aria-expanded="showUserMenu"
      >
        <span class="sr-only">Open user menu</span>
        <img class="w-8 h-8 rounded-full" src="https://placehold.co/20" alt="user photo">
      </button>
  
      <!-- User Dropdown Menu -->
      <div 
        v-if="showUserMenu" 
        class="top-10 right-48 z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow  dark:divide-gray-600 absolute"
      >
        <div class="px-4 py-3 bg-gray-400 rounded-t-lg">
          <b class="block text-sm text-gray-900 ">{{ user.name }}</b>
          <b class="block text-sm text-gray-500 truncate dark:text-gray-900">{{ user.email }}</b>
        </div>
        <ul class="py-2 px-4">
          <li>
            <a  class="block py-2 text-sm ">Thông tin cá nhân</a>
            <hr/>
          </li>
          <li>
            <a  class="block py-2 text-sm ">Quản lý tài liệu</a>
            <hr/>
          </li>
          <li>
            <button @click="logout" class="w-full mt-2 block px-4 py-2 text-sm  border-2 border-rose-600 text-red-600 bg-white hover:text-white hover:bg-red-600 transition-colors">Đăng xuất</button>
          </li>
        </ul>
      </div>
  
      <!-- Toggle Nav Bar for Mobile -->
      <button 
        type="button" 
        @click="toggleNavBar"
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700"
        aria-controls="navbar-user" 
        aria-expanded="showNavBar"
      >
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"></path>
        </svg>
      </button>
    </div>
  </template>
  
  <script>
  import { mapGetters, mapActions } from 'vuex'
  
  export default {
    name: 'NavBar',
    data() {
      return {
        showUserMenu: false,
        showNavBar: false
      }
    },
    computed: {
      ...mapGetters(['isLoggedIn', 'user'])
    },
    methods: {
      ...mapActions(['logout']),
      toggleUserMenu() {
        this.showUserMenu = !this.showUserMenu
      },
      toggleNavBar() {
        this.showNavBar = !this.showNavBar
      },
      navigateToLogin() {
        this.$router.push('/login')
      },
      navigateToRegister() {
        this.$router.push('/register')
      },
      handleClickOutside(event) {
        // Kiểm tra xem click có nằm ngoài dropdown menu hay không
        if (!this.$refs.navBarContainer.contains(event.target)) {
          this.showUserMenu = false
        }
      }
    },
    mounted() {
      // Thêm sự kiện click vào document để đóng menu khi click ra ngoài
      document.addEventListener('click', this.handleClickOutside)
    },
    beforeUnmount() {
      // Loại bỏ sự kiện khi component bị huỷ
      document.removeEventListener('click', this.handleClickOutside)
    }
  }
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
  