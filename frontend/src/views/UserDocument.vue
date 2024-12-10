<template>
    <div class="min-h-screen bg-white">
      <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-2xl font-semibold">Quản lý tài liệu</h1>
        </div>
    
        <!-- Tabs and Search -->
        <div class="bg-white rounded-lg shadow">
          <div class="border-b">
            <div class="flex items-center">
              <button 
                v-for="tab in tabs" 
                :key="tab.id"
                @click="activeTab = tab.id"
                class="px-6 py-4 text-sm relative"
                :class="[
                  activeTab === tab.id 
                    ? 'text-emerald-600 font-medium' 
                    : 'text-gray-600 hover:text-gray-900'
                ]"
              >
                {{ tab.name }}
                <span class="ml-1">({{ tab.count }})</span>
                <div 
                  v-if="activeTab === tab.id"
                  class="absolute bottom-0 left-0 right-0 h-0.5 bg-emerald-600"
                ></div>
              </button>
              
    
              <div class="flex-grow flex justify-end px-4">
                <div class="relative w-80">
                  <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Tìm kiếm..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                  />
                  <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                </div>
              </div>
            </div>
          </div>
    
          <!-- Content -->
          <div class="p-8 text-center text-gray-600">
            <div class="space-y-4">
                <DocumentCard v-for="doc in documents" :key="doc.id" :thumbnail="doc.thumbnail" :title="doc.title"
                    :tag="doc.tag" :subject="doc.subject" :institution="doc.institution" :pages="doc.pages"
                    :year="doc.year"   :showDeleteButton="true"
                    />
            </div>
          </div>
        </div>
      </div>
    </div>
    </template>
    
    <script setup>
    import { ref } from 'vue'
    import { Search } from 'lucide-vue-next'
    import DocumentCard from '../components/DocumentCard.vue';
    
    const activeTab = ref('approved')
    const searchQuery = ref('')

    const documents = ref([
    {
        id: 1,
        title: 'Introduction to Computer Science - Lecture Notes',
        thumbnail: 'https://placehold.co/96x128',
        pages: 45,
        views: '2.5K',
        downloads: '1.2K',
        rating: 95,
        tags: ['Computer Science', 'Lecture Notes', 'PDF']
    },
    {
        id: 1,
        title: 'Introduction to Computer Science - Lecture Notes',
        thumbnail: 'https://placehold.co/96x128',
        pages: 45,
        views: '2.5K',
        downloads: '1.2K',
        rating: 95,
        tags: ['Computer Science', 'Lecture Notes', 'PDF']
    },
    {
        id: 1,
        title: 'Introduction to Computer Science - Lecture Notes',
        thumbnail: 'https://placehold.co/96x128',
        pages: 45,
        views: '2.5K',
        downloads: '1.2K',
        rating: 95,
        tags: ['Computer Science', 'Lecture Notes', 'PDF']
    },
    // Additional documents here...
])
    
    const tabs = [
      {
        id: 'approved',
        name: 'Được duyệt',
        count: 0
      },
      {
        id: 'pending',
        name: 'Chờ duyệt',
        count: 0
      },
        {
            id: 'rejected',
            name: 'Bị từ chối',
            count: 0
        },
        {
            id: 'deleted',
            name: 'Đã xóa',
            count: 0
        }

    ]
    </script>