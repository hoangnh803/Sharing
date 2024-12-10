<template>
    <div>
      <h2 class="text-xl font-semibold mb-6">Thông tin cá nhân</h2>
  
      <!-- Display Name Section -->
      <div class="py-8 border-b">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-medium">TÊN HIỂN THỊ</h3>
          <button @click="editName" class="text-gray-500 hover:text-gray-700">
            <Settings2 class="w-5 h-5" />
            Sửa
          </button>
        </div>
        <div v-if="!isEditingName">
          {{ user.displayName }}
        </div>
        <div v-else>
          <input 
            v-model="newDisplayName"
            type="text" 
            class="border p-2 rounded-md"
            placeholder="Nhập tên hiển thị mới"
          />
          <button @click="saveName" class="ml-2 bg-green-500 text-white px-4 py-2 rounded-md">Lưu</button>
          <button @click="cancelEditName" class="ml-2 bg-orange-500 text-white px-4 py-2 rounded-md">Hủy</button>
        </div>
      </div>
  
      <!-- Personal Info Section -->
      <div class="py-8 border-b">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-medium">THÔNG TIN</h3>
          <button @click="editPersonalInfo" class="text-gray-500 hover:text-gray-700">
            <Settings2 class="w-5 h-5" />
            Sửa
          </button>
        </div>
        <div v-if="!isEditingInfo">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <div class="text-gray-500 mb-1">Ngày sinh</div>
              <div>{{ user.dob }}</div>
            </div>
            <div>
              <div class="text-gray-500 mb-1">Giới tính</div>
              <div>{{ user.gender }}</div>
            </div>
          </div>
          <div>
            <div class="text-gray-500 mb-1">Địa chỉ</div>
            <div>{{ user.address }}</div>
          </div>
        </div>
        <div v-else>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <div class="text-gray-500 mb-1">Ngày sinh</div>
              <input 
                v-model="newDob" 
                type="date" 
                class="border p-2 rounded-md"
              />
            </div>
            <div>
              <div class="text-gray-500 mb-1">Giới tính</div>
              <select v-model="newGender" class="border p-2 rounded-md">
                <option value="Nữ">Nữ</option>
                <option value="Nam">Nam</option>
                <option value="Khác">Khác</option>
              </select>
            </div>
          </div>
          <div>
            <div class="text-gray-500 mb-1">Địa chỉ</div>
            <input 
              v-model="newAddress" 
              type="text" 
              class="border p-2 rounded-md"
              placeholder="Nhập địa chỉ mới"
            />
          </div>
          <div class="mt-4">
              <button @click="savePersonalInfo" class="ml-2 bg-green-500 text-white px-4 py-2 rounded-md">Lưu</button>
              <button @click="cancelEditInfo" class="ml-2 bg-orange-500 text-white px-4 py-2 rounded-md">Hủy</button>
          </div>
        </div>
      </div>
  
      <!-- Email Section -->
      <div class="py-8 border-b">
        <h3 class="font-medium mb-4">EMAIL TÀI KHOẢN</h3>
        <div>{{ user.email }}</div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const user = ref({
    displayName: 'Nguyễn Huy Hoàng',
    dob: '09/08/2003',
    gender: 'Nam',
    address: '',
    email: 'hoangqaz125@gmail.com'
  })
  
  // State for editing name
  const isEditingName = ref(false)
  const newDisplayName = ref(user.value.displayName)
  
  // State for editing personal information
  const isEditingInfo = ref(false)
  const newDob = ref(user.value.dob)
  const newGender = ref(user.value.gender)
  const newAddress = ref(user.value.address)
  
  // Edit functions
  const editName = () => {
    isEditingName.value = true
  }
  
  const saveName = () => {
    user.value.displayName = newDisplayName.value
    isEditingName.value = false
  }
  
  const cancelEditName = () => {
    newDisplayName.value = user.value.displayName
    isEditingName.value = false
  }
  
  const editPersonalInfo = () => {
    isEditingInfo.value = true
  }
  
  const savePersonalInfo = () => {
    user.value.dob = newDob.value
    user.value.gender = newGender.value
    user.value.address = newAddress.value
    isEditingInfo.value = false
  }
  
  const cancelEditInfo = () => {
    newDob.value = user.value.dob
    newGender.value = user.value.gender
    newAddress.value = user.value.address
    isEditingInfo.value = false
  }
  </script>
  