<template>
  <div class="flex h-screen bg-gray-50">

    <!-- Left Sidebar -->
    <div class="w-80 bg-white border-r flex flex-col mt-16">
      <!-- Document Header -->
      <div class="p-6 border-b">
        <div class="flex items-center gap-2 mb-4">
          <ThumbsUp class="w-5 h-5 text-blue-600" />
          <span class="text-blue-600">53% (17)</span>
          <span class="text-gray-600">• 27K views</span>
        </div>

        <h1 class="text-xl font-bold mb-4">
          Bài 5. ESP32 gửi dữ liệu HTTP request
        </h1>

        <div class="mb-4">
          <div class="flex flex-col gap-1 mb-2">
            <div class="flex items-center gap-2">
              <span class="text-gray-600">Uploaded by</span>
              <a href="#" class="text-blue-600 hover:underline">hoangqaz125</a>
            </div>
            <span class="text-gray-600 text-sm">
              <span class="font-medium">Course:</span> IoT và Ứng dụng
            </span>
            <span class="text-gray-600 text-sm">
              <span class="font-medium">Đại học:</span> Đại học Bách Khoa Hà Nội
            </span>
          </div>
        </div>

        <p class="text-gray-600 text-sm mb-2">
          ESP32 gửi dữ liệu HTTP request đến server và nhận dữ liệu trả về từ server.
        </p>
        <button class="text-blue-600 text-sm hover:underline">
        </button>
      </div>

      <!-- Action Buttons -->
      <div class="grid grid-cols-4 border-b">
        <button class="flex flex-col items-center gap-1 p-4 hover:bg-gray-50">
          <Download class="w-5 h-5" />
          <span class="text-xs">Tài về</span>
        </button>
        <button class="flex flex-col items-center gap-1 p-4 hover:bg-gray-50">
          <Bookmark class="w-5 h-5" />
          <span class="text-xs">Lưu</span>
        </button>
        <!-- Like Button -->
        <button class="flex flex-col items-center gap-1 p-4 hover:bg-gray-50" :class="isLiked ? 'text-blue-600' : ''"
          @click="toggleLike">
          <ThumbsUp :class="isLiked ? 'text-blue-600' : ''" class="w-5 h-5" />
          <span class="text-xs">{{ likeCount }}</span>
        </button>
        <!-- Dislike Button -->
        <button class="flex flex-col items-center gap-1 p-4 hover:bg-gray-50" :class="isDisliked ? 'text-red-600' : ''"
          @click="toggleDislike">
          <ThumbsDown :class="isDisliked ? 'text-red-600' : ''" class="w-5 h-5" />
          <span class="text-xs">{{ dislikeCount }}</span>
        </button>

      </div>

      <!-- Share Buttons -->
      <div class="grid grid-cols-3 p-2">
        <button class="flex items-center justify-center gap-2 p-2 hover:bg-gray-50 rounded-lg">
          <Share2 class="w-5 h-5" />
          <span class="text-sm">Chia sẻ</span>
        </button>
        <button class="flex items-center justify-center gap-2 p-2 hover:bg-gray-50 rounded-lg">
          <Printer class="w-5 h-5" />
          <span class="text-sm">In</span>
        </button>
        <button @click="openReportPopup" class="flex items-center justify-center gap-2 p-2 hover:bg-gray-50 rounded-lg">
          <Flag class="w-5 h-5" />
          <span class="text-sm">Báo cáo</span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col mt-16">
      <!-- Top Bar -->
      <div class="bg-white border-b px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <button class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700">
            Tải về ngay
          </button>
        </div>
      </div>

      <!-- Document Viewer -->
      <div class="flex-1 overflow-auto  bg-gray-100">
        <div class="max-w-7xl mx-auto bg-white shadow-lg overflow-hidden">
          <!-- Hiển thị PDF -->
          <div v-if="fileType === 'pdf'">
            <iframe :src="fileURL" class="w-full h-[84vh]" frameborder="0"></iframe>
          </div>

          <!-- Hiển thị DOC/DOCX -->
          <div v-else-if="fileType === 'doc' || fileType === 'docx'">
            <iframe :src="`https://view.officeapps.live.com/op/embed.aspx?src=${fileURL}`" class="w-full h-[80vh]"
              frameborder="0"></iframe>
          </div>

          <!-- Trường hợp file không hỗ trợ -->
          <div v-else class="p-6 text-center">
            <p class="text-gray-500 text-sm">
              File type <span class="font-bold">{{ fileType }}</span> is not supported.
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- Report Modal -->
    <ReportPopup v-if="isReportModalOpen" @close="closeReportModal" />
  </div>
</template>

<script setup>
import ReportPopup from "../components/ReportPopup.vue";
import {
  ThumbsUp,
  ThumbsDown,
  Download,
  Bookmark,
  Share2,
  Printer,
  Flag,
} from "lucide-vue-next";

import { ref } from "vue";


// Định nghĩa loại file và URL file
const fileType = ref("pdf"); // Loại file: 'pdf', 'doc', 'docx', ...
const fileURL = ref(
  "http://127.0.0.1:8000/storage/1733416828_Bai5.%20ESP32%20g%E1%BB%ADi%20d%E1%BB%AF%20li%E1%BB%87u%20HTTP%20.pdf"
);

/// State for likes and dislikes count
const likeCount = ref(17); // Số lượng like ban đầu
const dislikeCount = ref(10); // Số lượng dislike ban đầu

// State to track like/dislike status
const isLiked = ref(false);
const isDisliked = ref(false);

// Toggle like
const toggleLike = () => {
  if (isLiked.value) {
    likeCount.value--; // Unlike
    isLiked.value = false;
  } else {
    if (isDisliked.value) {
      dislikeCount.value--; // Nếu đã dislike thì bỏ dislike
      isDisliked.value = false;
    }
    likeCount.value++; // Like
    isLiked.value = true;
  }
};

// Toggle dislike
const toggleDislike = () => {
  if (isDisliked.value) {
    dislikeCount.value--; // Undo dislike
    isDisliked.value = false;
  } else {
    if (isLiked.value) {
      likeCount.value--; // Nếu đã like thì bỏ like
      isLiked.value = false;
    }
    dislikeCount.value++; // Dislike
    isDisliked.value = true;
  }
};

// State for report modal
const isReportModalOpen = ref(false);

const openReportPopup = () => {
  isReportModalOpen.value = true;
};

const closeReportModal = () => {
  isReportModalOpen.value = false;
};



</script>