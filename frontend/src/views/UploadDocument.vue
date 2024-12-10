<template>
  <div class="min-h-screen bg-white flex flex-col items-center pt-4">
    <!-- Logo -->
    <router-link to="/" class="flex-shrink-0 flex items-center">
      <BookOpen class="h-8 w-8 text-black" />
      <span class="ml-2 text-xl font-bold text-black">EduShare</span>
    </router-link>

    <!-- Header -->
    <h1 class="text-2xl text-gray-800 font-medium mb-8 mt-2">
      Chia sẻ tài liệu đến mọi người
    </h1>


    <!-- Progress Steps -->
    <div class="flex flex-col w-full max-w-3xl mb-8">
      <!-- Step Labels -->
      <div class="flex justify-around mb-2 px-4">
        <div class="flex items-center gap-1">
          <div class="w-5 h-5 rounded-full bg-blue-500 text-white text-xs flex items-center justify-center">
            1
          </div>
          <span class="text-blue-500 font-medium">Tải lên</span>
        </div>
        <div class="flex items-center gap-1">
          <div class="w-5 h-5 rounded-full bg-gray-200 text-gray-400 text-xs flex items-center justify-center">
            2
          </div>
          <span class="text-gray-400">Chi tiết</span>
        </div>
        <div class="flex items-center gap-1">
          <div class="w-5 h-5 rounded-full bg-gray-200 text-gray-400 text-xs flex items-center justify-center">
            3
          </div>
          <span class="text-gray-400">Hoàn tất</span>
        </div>
      </div>

      <!-- Progress Bars -->
      <div class="flex items-center gap-1">
        <div class="h-2 flex-1 rounded-full bg-blue-500"></div>
        <div class="h-2 flex-1 rounded-full bg-gray-200"></div>
        <div class="h-2 flex-1 rounded-full bg-gray-200"></div>
      </div>
    </div>

    <!-- Upload Area -->
    <div
      class="w-full max-w-3xl py-8 px-20 border-2 border-dashed rounded-lg border-blue-100 flex flex-col items-center"
      :class="{ 'border-blue-500 bg-blue-50': isDragging }" @dragenter.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false" @dragover.prevent @drop.prevent="handleDrop">
      <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
        <Upload class="w-6 h-6 text-blue-500" />
      </div>
      <h2 class="text-xl text-gray-700 font-medium mb-2">
        Kéo & thả tài liệu vào đây
      </h2>
      <p class="text-gray-500 mb-4">Hoặc</p>
      <button class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
        @click="$refs.fileInput.click()">
        Duyệt các file của tôi
      </button>
      <input ref="fileInput" type="file" class="hidden" accept=".pdf,.doc,.docx" @change="handleFileSelect" />
      <p class="text-gray-400 text-sm mt-4">
        Định dạng hỗ trợ: pdf, doc, docx
      </p>
    </div>

    <!-- Display File Information -->
    <div v-if="file" class="mt-4 text-gray-600">
      <div class="flex items-center justify-between mb-2">
        <span class="text-gray-700">{{ file.name }}</span>
        <span class="pl-2 text-sm text-gray-400">{{ file.size }}</span>
        <button @click="removeFile" class="text-red-500 hover:text-red-700 ml-2">
          <Trash2 class="w-5 h-5" />
        </button>
      </div>
    </div>

    <!-- Progress Bar -->
    <div v-if="file" class="w-full max-w-3xl mt-2">
      <div class="flex items-center justify-between mb-2">
        <span class="text-gray-600">Uploading: {{ progress }}%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
        <div :style="{ width: progress + '%' }" class="bg-blue-500 h-2 rounded-full"></div>
      </div>
    </div>

    <!-- Submit Button -->
    <div v-if="progress === 100 && file" class="mt-8">
      <button @click="submitDocuments"
        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
        Tải lên
      </button>
    </div>

    <!-- Copyright Notice -->
    <p class="text-gray-400 text-sm text-center max-w-3xl mt-8 px-4">
      Bằng cách tải tài liệu lên EduShare, bạn tuyên bố rằng bạn sở hữu bản quyền đối với các tài liệu hoặc có
      sự cho phép rõ ràng từ chủ sở hữu bản quyền để sử dụng và tải tài liệu lên.
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Upload, BookOpen, Trash2 } from 'lucide-vue-next'
import api from '../services/api'

const router = useRouter()
const isDragging = ref(false)
const progress = ref(0) // Tiến trình tải lên
const file = ref(null) // Lưu thông tin file

// Hàm upload file tạm thời lên server
const uploadTempFileToServer = async (selectedFile) => {
  const formData = new FormData();
  formData.append('document', selectedFile);

  try {
    const response = await api.UploadTempDocument(formData, (event) => {
      progress.value = Math.round((event.loaded * 100) / event.total);
    });

    if (response.data && response.data.file_path) {
      file.value = {
        name: selectedFile.name,
        size: (selectedFile.size / 1024).toFixed(1) + ' KB',
        path: response.data.file_path,
      };
      console.log('Uploaded file:', file.value);
    }
  } catch (error) {
    console.error('Failed to upload file:', error);
    progress.value = 0;
  }
};

// Khi người dùng chọn file
const handleFileSelect = async (e) => {
  const selectedFile = e.target.files[0];
  if (selectedFile) {
    await uploadTempFileToServer(selectedFile);
  }
};

// Khi người dùng kéo thả file
const handleDrop = async (e) => {
  isDragging.value = false;
  const droppedFile = e.dataTransfer.files[0];
  if (droppedFile) {
    await uploadTempFileToServer(droppedFile);
  }
};

const removeFile = async () => {
  console.log(file.value.path);

  if (file.value && file.value.path) {
    try {
      // Gửi đường dẫn file cần xóa qua params
      await api.deleteFile(file.value.path);  // Gọi trực tiếp hàm deleteFile và truyền file_path

      file.value = null; // Xóa thông tin file trong state
      progress.value = 0; // Đặt lại thanh tiến trình
      console.log('File deleted successfully.');
    } catch (error) {
      console.error('Failed to delete file:', error);
    }
  }
};




// Chuyển đến bước tiếp theo với thông tin file
const submitDocuments = () => {
  if (file.value) {
    router.push({
      path: '/upload/details',
      query: {
        file_name: file.value.name,
        file_size: file.value.size,
        file_path: file.value.path,
      },
    });
  }
};
</script>
