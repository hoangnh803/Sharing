<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Tài liệu đã lưu</h1>
    <div v-if="savedDocuments.length === 0" class="text-gray-500">
      Bạn chưa lưu tài liệu nào.
    </div>
    <div class="p-8 text-center text-gray-600">
      <div class="space-y-4">
        <DocumentCard v-for="doc in savedDocuments" :key="doc.id" :document="doc" @click="goToDocumentDetail" />
      </div>
    </div>
  </div>
</template>

<script setup>
// filepath: /c:/ProjectII/Sharing/frontend/src/views/SavedDocuments.vue
import { ref, onMounted } from 'vue'
import api from '../services/api'
import DocumentCard from '../components/DocumentCard.vue';

const savedDocuments = ref([])

const fetchSavedDocuments = async () => {
  try {
    const response = await api.getSavedDocuments()
    savedDocuments.value = response.data
    console.log(savedDocuments.value)
  } catch (error) {
    console.error('Error fetching saved documents:', error)
  }
}

onMounted(() => {
  fetchSavedDocuments()
})
</script>

<style scoped>
/* Add your styles here */
</style>