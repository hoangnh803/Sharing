<template>
  <div class="relative" ref="navBarContainer">
    <!-- Notification Menu Button -->
    <button type="button" class="pr-3 rounded" @click="toggleNotificationMenu" aria-expanded="showNotificationMenu">
      <span class="sr-only">Open notification menu</span>
      <font-awesome-icon icon="fa-solid fa-bell" class="w-8 h-8" />
      <span v-if="unreadNotificationsCount > 0"
        class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
        {{ unreadNotificationsCount }}
      </span>
    </button>

    <!-- Notification Dropdown Menu -->
    <div v-if="showNotificationMenu"
      class="absolute top-6 z-50 my-4 rounded-lg bg-content-background right-4 w-80 p-0 select-none arrow-top-right text-sm">

      <!-- Header thông báo -->
      <div class="px-4 py-1.5 bg-main-background rounded-t-lg border-b">
        <b>BẠN CÓ {{ unreadNotificationsCount }} THÔNG BÁO MỚI</b>
      </div>

      <!-- Nội dung thông báo -->
      <div class="max-h-96 overflow-y-auto scroll-bar">
        <!-- Nếu không có thông báo mới -->
        <p v-if="unreadNotificationsCount === 0" class="py-3 text-center text-gray-darker">
          Không có thông báo mới
        </p>

        <!-- Nếu có thông báo mới -->
        <ul v-else class="py-2 relative">
          <li v-for="notification in unreadNotifications" :key="notification.id">
            <router-link :to="documentRoute(notification)"
              class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200"
              @click="markAsRead(notification.id)">
              {{ notification.data.message }} - <span class='text-blue-600'>{{ notification.data.title }}</span>
            </router-link>
          </li>
        </ul>
      </div>

      <!-- Nút "Xem tất cả" -->
      <div
        class="w-full rounded-lg text-center py-2 px-4 border border-transparent text-green-600 bg-white hover:text-white hover:bg-green-600 transition-colors">
        <router-link :to="notificationRoute()" @click="handleOptionClick" class="block">
          Xem tất cả
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'NotificationDropdown',
  data() {
    return {
      showNotificationMenu: false
    }
  },
  created() {
    // Lấy thông tin người dùng từ localStorage
    const user = JSON.parse(localStorage.getItem('user'));
    if (user && user.role) {
      this.userRole = user.role;
    }
    this.fetchNotifications();
    // this.fetchInterval = setInterval(this.fetchNotifications, 10000);
  },
  computed: {
    ...mapGetters(['notifications', 'unreadNotificationsCount']),
    parsedNotifications() {
      return this.notifications.map(notification => {
        return {
          ...notification,
          data: JSON.parse(notification.data)
        }
      })
    },
    unreadNotifications() {
      return this.parsedNotifications.filter(notification => !notification.read_at);
    }
  },
  methods: {
    ...mapActions(['fetchNotifications', 'markNotificationAsRead']),
    toggleNotificationMenu() {
      this.showNotificationMenu = !this.showNotificationMenu
      if (this.showNotificationMenu) {
        this.fetchNotifications()
        console.log('Notifications:', this.notifications) // Log giá trị notifications
      }
    },
    handleClickOutside(event) {
      // Kiểm tra xem click có nằm ngoài dropdown menu hay không
      if (!this.$refs.navBarContainer.contains(event.target)) {
        this.showNotificationMenu = false
      }
    },
    handleOptionClick() {
      this.showNotificationMenu = false; // Đóng dropdown sau khi chọn
    },
    markAsRead(notificationId) {
      this.markNotificationAsRead(notificationId)
      this.showNotificationMenu = false; // Đóng dropdown sau khi chọn
    },
    documentRoute(notification) {
      // Kiểm tra vai trò người dùng và quyết định đường dẫn
      if (this.userRole === 'admin') {
        return { name: 'AdminDocumentView', params: { id: notification.data.document_id } }
      } else {
        return { name: 'DocumentView', params: { id: notification.data.document_id } }
      }
    },
    notificationRoute() {
      // Kiểm tra vai trò người dùng và quyết định đường dẫn
      if (this.userRole === 'admin') {
        return { name: 'AdminNotification' }
      } else {
        return { name: 'UserNotification' }
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
.bg-main-background {
  background-color: #a2a4a6;
}

.bg-content-background {
  background-color: #ffffff;
}
</style>