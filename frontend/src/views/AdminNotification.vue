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
            <!-- Thông báo chưa đọc -->
            <div v-if="unreadNotifications.length > 0">
              <div v-for="(notification, index) in unreadNotifications" :key="index" class="mb-4 p-4 border border-gray-200 rounded-lg">
                <h3 class="font-semibold text-gray-800">{{ notification.data.title }}</h3>
                <p class="text-gray-600">{{ notification.data.message }}</p>
                <p class="text-sm text-gray-400 mt-2">{{ notification.created_at }}</p>
                <router-link :to="{ name: 'AdminDocumentView', params: { id: notification.data.document_id } }"
                  class="text-blue-600 hover:underline"
                  @click.prevent="markAsRead(notification.id)">
                  Xem chi tiết
                </router-link>
              </div>
            </div>
  
            <!-- Phân cách -->
            <div v-if="unreadNotifications.length > 0 && readNotifications.length > 0" class="my-4 text-center text-gray-600">
              ------- Đã đọc ---------
            </div>
  
            <!-- Thông báo đã đọc -->
            <div v-if="readNotifications.length > 0">
              <div v-for="(notification, index) in readNotifications" :key="index" class="mb-4 p-4 border border-gray-200 rounded-lg">
                
                <router-link :to="{ name: 'AdminDocumentView', params: { id: notification.data.document_id } }"
                class="block  text-gray-700 dark:text-gray-200"
                  @click.prevent="markAsRead(notification.id)">
                  <h3 class="font-semibold text-gray-800">{{ notification.data.title }}</h3>
                <p class="text-gray-600">{{ notification.data.message }}</p>
                <p class="text-sm text-gray-400 mt-2">{{ notification.created_at }}</p>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import { useStore } from 'vuex'
  
  const store = useStore()
  const notifications = ref([])
  
  const fetchNotifications = async () => {
    await store.dispatch('fetchNotifications')
    notifications.value = store.getters.notifications.map(notification => {
      return {
        ...notification,
        data: JSON.parse(notification.data) // Chuyển đổi chuỗi JSON thành object
      }
    })
    console.log('Notifications:', notifications.value)
  }
  
  
  const markAsRead = async (notificationId) => {
    await store.dispatch('markNotificationAsRead', notificationId)
    await fetchNotifications() // Refresh danh sách sau khi đánh dấu đã đọc
  }
  
  const unreadNotifications = computed(() => {
    return notifications.value.filter(notification => !notification.read_at)
  })
  
  const readNotifications = computed(() => {
    return notifications.value.filter(notification => notification.read_at)
  })
  
  onMounted(() => {
    fetchNotifications()
  })
  </script>
  
  <style scoped>
  /* Customize any styles for notifications here */
  </style>