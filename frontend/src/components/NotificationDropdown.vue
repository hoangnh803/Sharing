<template>
    <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse " ref="navBarContainer">
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
            class="absolute top-10 z-50 my-4 rounded-lg bg-content-background right-40 w-80 p-0 select-none arrow-top-right text-sm">

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
                <ul v-else class="py-2">
                    <li v-for="notification in notifications" :key="notification.id">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                            {{ notification.message }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Nút "Xem tất cả" -->

            <div
                class="w-full  rounded-lg text-center py-2 px-4 border border-transparent text-green-600 bg-white hover:text-white hover:bg-green-600 transition-colors">
                <router-link to="/user/notifications" @click="handleOptionClick" class="block">
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
    computed: {
        ...mapGetters(['notifications', 'unreadNotificationsCount'])
    },
    methods: {
        ...mapActions(['fetchNotifications']),
        toggleNotificationMenu() {
            this.showNotificationMenu = !this.showNotificationMenu
            if (this.showNotificationMenu) {
                this.fetchNotifications()
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