<template>
  <div class="relative" ref="dropdownContainer">
    <!-- Avatar -->
    <div
      class="w-10 h-10 rounded-full bg-gray-400 overflow-hidden cursor-pointer border border-gray-500"
      @click="toggleDropdown"
    >
      <img :src="avatarUrl" alt="User Avatar" class="object-cover w-full h-full" />
    </div>

    <!-- Dropdown Menu -->
    <transition name="dropdown">
      <div
        v-if="isDropdownOpen"
        class="absolute right-0 mt-2 w-56 bg-[#1F485B] text-white rounded-lg shadow-lg z-50"
      >
        <!-- User Info -->
        <div class="flex items-center px-4 py-3 border-b border-gray-600">
          <div class="w-10 h-10 rounded-full bg-gray-500 overflow-hidden">
            <img :src="avatarUrl" alt="User Avatar" class="object-cover w-full h-full" />
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium">{{ userName }}</p>
          </div>
        </div>

        <!-- Options -->
        <div class="py-2">
          <router-link
            to="/user/profile"
            class="block px-4 py-2 text-sm hover:bg-gray-600 cursor-pointer"
            @click="handleOptionClick"
          >
            Thông tin cá nhân
          </router-link>
          <router-link
            to="/user/documents"
            class="block px-4 py-2 text-sm hover:bg-gray-600 cursor-pointer"
            @click="handleOptionClick"
          >
            Quản lý tài liệu
          </router-link>
        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center px-8 py-3 text-sm">
          <a href="#" class="hover:underline text-gray-300">Hỗ trợ</a>
          <button class="hover:underline text-red-500" @click="handleLogout">
            Đăng xuất
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "DropboxComponent",
  data() {
    return {
      isDropdownOpen: false, // Trạng thái mở/đóng dropdown
      userName: "User", // Tên người dùng mặc định
      avatarUrl: "", // URL avatar mặc định
    };
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen;
    },
    handleOptionClick() {
      this.isDropdownOpen = false; // Đóng dropdown sau khi chọn
    },
    async handleLogout() {
      try {
        localStorage.removeItem("token"); // Xóa token
        localStorage.removeItem("user"); // Xóa thông tin người dùng
        this.$router.push("/"); // Chuyển hướng về trang đăng nhập
      } catch (error) {
        console.error("Error during logout:", error);
        alert("Logout failed. Please try again.");
      }
    },
    handleClickOutside(event) {
      // Đóng dropdown nếu click ra ngoài
      if (!this.$refs.dropdownContainer.contains(event.target)) {
        this.isDropdownOpen = false;
      }
    },
    loadUserInfo() {
      // Lấy thông tin người dùng từ localStorage
      const userData = localStorage.getItem("user");
      if (userData) {
        try {
          const user = JSON.parse(userData);
          this.userName = user.name || "User"; // Gán tên người dùng từ localStorage
          this.avatarUrl = user.avatar || "https://via.placeholder.com/40"; // Gán avatar người dùng
        } catch (error) {
          console.error("Failed to parse user data:", error);
        }
      } else {
        // Giá trị mặc định nếu không có thông tin trong localStorage
        this.userName = "User";
        this.avatarUrl = "https://via.placeholder.com/40";
      }
    },
  },
  mounted() {
    // Thêm sự kiện click ngoài dropdown để đóng
    document.addEventListener("click", this.handleClickOutside);

    // Tải thông tin người dùng từ localStorage
    this.loadUserInfo();
  },
  beforeUnmount() {
    // Loại bỏ sự kiện khi component bị hủy
    document.removeEventListener("click", this.handleClickOutside);
  },
};
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.dropdown-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.dropdown-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.dropdown-leave-from {
  opacity: 1;
  transform: translateY(0);
}

.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
