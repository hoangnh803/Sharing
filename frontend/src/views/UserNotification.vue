<template>
    <div class="min-h-screen bg-white">
      <div class="container mx-auto p-6">
        <!-- Header -->
        <h1 class="text-2xl font-semibold mb-6">Thông báo</h1>
  
        <!-- Notifications Card -->
        <div class="bg-white rounded-lg shadow p-8">
          <div v-if="notifications.length === 0" class="text-center text-gray-600">
            Không có thông báo mới
          </div>
  
          <div v-else>
            <div v-for="(notification, index) in notifications" :key="index" class="mb-4 p-4 border border-gray-200 rounded-lg">
              <h3 class="font-semibold text-gray-800">{{ notification.title }}</h3>
              <p class="text-gray-600">{{ notification.message }}</p>
              <p class="text-sm text-gray-400 mt-2">{{ notification.timestamp }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const notifications = ref([
    {
      title: 'Thông báo 1',
      message: 'Hệ thống sẽ bảo trì vào lúc 2 giờ sáng ngày mai. Vui lòng chuẩn bị.',
      timestamp: '10:00 AM, 05/12/2024'
    },
    {
      title: 'Thông báo 2',
      message: 'Bạn có một tài liệu mới cần xem xét.',
      timestamp: '08:30 AM, 05/12/2024'
    },
    {
      title: 'Thông báo 3',
      message: 'Lịch thi của bạn đã được cập nhật.',
      timestamp: '07:00 AM, 05/12/2024'
    }
  ])
  </script>
  
  <style scoped>
  /* Customize any styles for notifications here */
  </style>
  