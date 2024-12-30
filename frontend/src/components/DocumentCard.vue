<template>
  <div class="bg-white border-2 rounded-lg p-4 flex gap-4 hover:bg-[#bae6fd] transition-colors duration-200 cursor-pointer"
    @click="goToDocumentDetail">

    <!-- Document Thumbnail -->
    <div class="w-24 h-32 bg-gray-100 rounded overflow-hidden flex-shrink-0">
      <img :src="preview" :alt="document.title" class="w-full h-full object-cover" />
    </div>

    <!-- Document Info -->
    <div class="flex-grow">
      <div class="flex items-start justify-between">
        <!-- Title and Tag -->
        <div>
          <div class="flex items-center gap-2">
            <a href="#" class="text-blue-600 hover:underline font-medium">
              {{ document.title }}
            </a>
            <span class="text-sm text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
              {{ document.document_type.name }}
            </span>
          </div>

          <!-- Subject and Institution -->
          <div class="mt-2 space-y-1">
            <div class="flex items-center gap-2">
              <FolderIcon class="w-4 h-4 text-gray-400" />
              <a href="#" class="text-gray-600 hover:text-gray-900">
                {{ document.subject.name }}
              </a>
            </div>
            <div class="flex items-center gap-2">
              <BuildingIcon class="w-4 h-4 text-gray-400" />
              <a href="#" class="text-gray-600 hover:text-gray-900">
                {{ document.class ? document.class.name : document.university.name }}
              </a>
            </div>
          </div>

          <!-- Metadata -->
          <div class="flex items-center gap-4 mt-2">
            <div class="flex items-center gap-1 text-gray-500">
              <FileTextIcon class="w-4 h-4" />
              <span class="text-sm">{{ document.pages }} pages</span>
            </div>
            <div class="flex items-center gap-1 text-gray-500">
              <CalendarIcon class="w-4 h-4" />
              <span class="text-sm">{{ document.created_at.split('-')[0] }}</span> <!-- Year -->
            </div>
          </div>
        </div>

        <!-- Rating -->
        <div class="flex items-center gap-1">
          <ThumbsUpIcon class="w-4 h-4 text-green-500" />
          <span class="text-green-500 font-medium">{{ likePercentage }}%</span>
          <span class="text-gray-400">({{ likeCount }})</span>
        </div>
      </div>
    </div>

    <!-- Delete Button -->
    <button v-if="showDeleteButton" class="z-50 text-red-600 hover:text-red-800 font-medium" @click.stop="showDeleteModal = true">
      <Trash2 />
    </button>
    <!-- Modal thông báo xóa tài liệu -->
    <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Xác nhận xóa</h3>
        <p class="mt-2">Bạn có chắc chắn muốn xóa tài liệu này?</p>
        <div class="mt-4 flex justify-end">
          <button @click.stop="showDeleteModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">Đóng</button>
          <button @click.stop="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded-md">Xóa</button>
        </div>
      </div>
    </div>

    <!-- Modal thông báo kết quả xóa tài liệu -->
    <div v-if="showResultModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Thông báo</h3>
        <p class="mt-2">{{ resultMessage }}</p>
        <div class="mt-4 flex justify-end">
          <button @click.stop="showResultModal = false" class="bg-blue-500 text-white px-4 py-2 rounded-md">Đóng</button>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import {
  FileText as FileTextIcon,
  Building as BuildingIcon,
  Folder as FolderIcon,
  Calendar as CalendarIcon,
  ThumbsUp as ThumbsUpIcon,
  Trash2
} from 'lucide-vue-next'
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
const router = useRouter()

const props = defineProps({
  document: Object,
  showDeleteButton: Boolean
})
// Define emits
const preview = `http://127.0.0.1:8000/storage/${props.document.preview}`

// Fallback to an empty array if `document.likes` is undefined
// Fallback to an empty array if `document.likes` is undefined
const likes = props.document.likes || [];

// Computed property to calculate the like percentage based on `is_like` (true for like, false for unlike)
const likePercentage = computed(() => {
  const totalLikes = likes.filter(like => like.is_like).length; // Count only likes (is_like: true)
  const totalDislikes = likes.filter(like => !like.is_like).length; // Count only dislikes (is_like: false)
  
  const totalLikesAndDislikes = totalLikes + totalDislikes;
  if (totalLikesAndDislikes === 0) return 0; // Avoid division by zero if there are no likes or dislikes
  return ((totalLikes / totalLikesAndDislikes) * 100).toFixed(2); // Calculate percentage and round to 2 decimal places
});

// Computed property to get the total number of likes
const likeCount = computed(() => {
  return likes.filter(like => like.is_like).length;
});

const showDeleteModal = ref(false);
const showResultModal = ref(false);
const resultMessage = ref('');


const confirmDelete = async () => {
  try {
    const response = await api.deleteDocument(props.document.id);
    resultMessage.value = response.data.message;
  } catch (error) {
    console.error('Failed to delete document:', error);
    resultMessage.value = 'Failed to delete document.';
  } finally {
    showDeleteModal.value = false;
    showResultModal.value = true;
  }
};


const goToDocumentDetail = () => {
  const documentId = props.document.id;
  const user = JSON.parse(localStorage.getItem('user')); // Replace with actual method to get the role
  // Điều hướng đến trang chi tiết tài liệu
  if (user && user.role === 'admin') {
    router.push({
      name: 'AdminDocumentView',
      params: { id: documentId }
    });
  } else {
    router.push({
      name: 'DocumentView',
      params: { id: documentId }
    });
  }
};
</script>
