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
            <button v-for="tab in tabs" :key="tab.id" @click="selectTab(tab.id)" class="px-6 py-4 text-sm relative"
              :class="[activeTab === tab.id ? 'text-emerald-600 font-medium' : 'text-gray-600 hover:text-gray-900']">
              {{ tab.name }}
              <span class="ml-1">({{ tab.count }})</span>
              <div v-if="activeTab === tab.id" class="absolute bottom-0 left-0 right-0 h-0.5 bg-emerald-600"></div>
            </button>

            <div class="flex-grow flex justify-end px-4">
              <div class="relative w-80">
                <input type="text" v-model="searchQuery" placeholder="Tìm kiếm..."
                  class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
              </div>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="p-8 text-center text-gray-600">
          <div class="space-y-4">
            <DocumentCard v-for="doc in filteredDocuments" :key="doc.id" :document="doc" :showDeleteButton="true"
              @documentDeleted="removeDocument" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import api from '../services/api';
import DocumentCard from '../components/DocumentCard.vue';
import { Search } from 'lucide-vue-next';

const activeTab = ref('approved');
const documents = ref([]);
const searchQuery = ref('');

const fetchDocuments = async () => {
  try {
    const response = await api.getDocuments();
    documents.value = response.data;
    console.log('Documents:', documents.value);
    updateTabCounts();
  } catch (error) {
    console.error('Failed to fetch documents:', error);
  }
};

const removeDocument = (id) => {
  documents.value = documents.value.filter(doc => doc.id !== id);
  updateTabCounts();
};

const filteredDocuments = computed(() => {
  return documents.value.filter(doc =>
    doc.title.toLowerCase().includes(searchQuery.value.toLowerCase()) &&
    (activeTab.value === 'reported' ? doc.is_approved === 'approved' && doc.reports && doc.reports.length > 0 : doc.is_approved === activeTab.value)
  );
});

const tabs = ref([
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
    id: 'reported',
    name: 'Bị báo cáo',
    count: 0
  }
]);

const updateTabCounts = () => {
  tabs.value.forEach(tab => {
    if (tab.id === 'reported') {
      tab.count = documents.value.filter(doc => doc.is_approved === 'approved' && doc.reports && doc.reports.length > 0).length
    } else {
      tab.count = documents.value.filter(doc => doc.is_approved === tab.id).length
    }
  });
};

const selectTab = (tabId) => {
  activeTab.value = tabId;
};

watch(documents, updateTabCounts);

fetchDocuments();


</script>

<style scoped>
/* Add your styles here */
</style>