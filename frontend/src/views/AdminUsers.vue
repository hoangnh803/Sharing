<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-6">User Management</h1>
    <!-- Input tìm kiếm -->
    <input 
      v-model="searchTerm" 
      type="text" 
      placeholder="Search by name or email"
      @input="filterUsers"
      class="mb-4 p-2 border rounded"
    />
    <!-- Bảng danh sách người dùng -->
    <table class="min-w-full border border-gray-200">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-4 py-2 border text-left">ID</th>
          <th class="px-4 py-2 border text-left">Name</th>
          <th class="px-4 py-2 border text-left">Email</th>
          <th class="px-4 py-2 border text-left">Role</th>
          <th class="px-4 py-2 border text-left">Status</th>
          <th class="px-4 py-2 border text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr @click="openUserModal(user)" v-for="user in filteredUsers" :key="user.id" class="cursor:pointer hover:bg-gray-50">
          <td class="px-4 py-2 border">{{ user.id }}</td>
          <td class="px-4 py-2 border">{{ user.name }}</td>
          <td class="px-4 py-2 border">{{ user.email }}</td>
          <td class="px-4 py-2 border">{{ user.role }}</td>
          <td class="px-4 py-2 border">
            <span :class="{'text-green-500': !user.is_locked, 'text-red-500': user.is_locked}">
              {{ user.is_locked ? 'Locked' : 'Active' }}
            </span>
          </td>
          <td class="px-4 py-2 border">
            <!-- Nút Lock/Unlock -->
            <button @click="toggleLock(user)" class="text-blue-500">
              {{ user.is_locked ? 'Unlock' : 'Lock' }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>
     <!-- Modal hiển thị thông tin người dùng -->
     <div v-if="selectedUser" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">User Details</h2>
        <p><strong>ID:</strong> {{ selectedUser.id }}</p>
        <p><strong>Name:</strong> {{ selectedUser.name }}</p>
        <p><strong>Email:</strong> {{ selectedUser.email }}</p>
        <p><strong>Role:</strong> {{ selectedUser.role }}</p>
        <p><strong>Status:</strong> {{ selectedUser.is_locked ? 'Locked' : 'Active' }}</p>
        <p v-if="selectedUser.gender"><strong>Gender:</strong> {{ selectedUser.gender }}</p>
        <p v-if="selectedUser.date_of_birth"><strong>Date of Birth:</strong> {{ selectedUser.date_of_birth }}</p>
        <p v-if="selectedUser.address"><strong>Address:</strong> {{ selectedUser.address }}</p>
        <!-- Hiển thị thêm thông tin thống kê -->
      <h3 class="mt-4 text-lg font-semibold">Statistics</h3>
      <p><strong>View Count:</strong> {{ selectedUser.viewCount ?? 'Loading...' }}</p>
      <p><strong>Document Count:</strong> {{ selectedUser.documentCount ?? 'Loading...' }}</p>
      <p><strong>Download Count:</strong> {{ selectedUser.downloadCount ?? 'Loading...' }}</p>
        <div class="mt-4 flex justify-end">
          <button @click="closeUserModal" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import apiService from '../services/api';

export default {
  data() {
    return {
      searchTerm: '', // Từ khóa tìm kiếm
      filteredUsers: [], // Danh sách người dùng sau khi lọc
      users: [],
      selectedUser: null, // Lưu trữ thông tin người dùng đang được chọn
    };
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await apiService.getUsers();
        this.users = response.data;
        console.log('Users:', this.users);
        this.filteredUsers = this.users; // Khởi tạo filteredUsers với tất cả người dùng ban đầu
      } catch (error) {
        console.error('Error fetching users:', error);
      }
    },
    async toggleLock(user) {
      try {
        user.is_locked = !user.is_locked;
        await apiService.updateUser(user.id, { is_locked: user.is_locked });
        this.fetchUsers(); // Cập nhật lại danh sách người dùng sau khi thay đổi trạng thái khóa
      } catch (error) {
        console.error('Error toggling lock:', error);
      }
    },
    // Phương thức lọc người dùng
    filterUsers() {
      const searchTermLowerCase = this.searchTerm.toLowerCase();
      this.filteredUsers = this.users.filter(user => {
        return user.name.toLowerCase().includes(searchTermLowerCase) || 
               user.email.toLowerCase().includes(searchTermLowerCase);
      });
    },

    async openUserModal(user) {
      this.selectedUser = { ...user, viewCount: null, documentCount: null, downloadCount: null };
      try {
        const response = await apiService.getUserStatistics(user.id);
        this.selectedUser.viewCount = response.data.total_views;
        this.selectedUser.documentCount = response.data.document_count;
        this.selectedUser.downloadCount = response.data.total_downloads;
      } catch (error) {
        console.error('Error fetching user statistics:', error);
      }
    },
    closeUserModal() {
      this.selectedUser = null; // Đóng modal
    },
  },
  mounted() {
    this.fetchUsers();
  },
};
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 12px;
  text-align: left;
}

button {
  cursor: pointer;
}

button:hover {
  text-decoration: underline;
}
</style>
