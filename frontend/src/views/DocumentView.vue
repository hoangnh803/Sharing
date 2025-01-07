<template>
  <div class="flex h-screen bg-gray-50">

    <!-- Left Sidebar -->
    <div class="w-80 bg-white border-r flex flex-col mt-16">
      <!-- Document Header -->
      <div class="p-6 border-b">
        <div class="flex items-center gap-2 mb-4">
          <ThumbsUp class="w-5 h-5 text-blue-600" />
          <span class="text-blue-600">{{ likePercentage.toFixed(2) }}% ({{ totalVotes  }})</span>
          <span class="text-gray-600">• {{ documentData.view_count }} views</span>
        </div>

        <h1 class="text-xl font-bold mb-4">
          {{ documentData.title }}
        </h1>

        <div class="mb-4">
          <div class="flex flex-col gap-1 mb-2">
            <div class="flex items-center gap-2">
              <span class="text-gray-600">Uploaded by</span>
              <a href="#" class="text-blue-600 hover:underline">{{ documentData.uploader.email }}</a>
            </div>
            <span class="text-gray-600 text-sm">
              
            <span class="font-medium">{{ 'Môn: ' }}</span>
      <router-link
        v-if="documentData.class?.name"
        :to="`/class/${documentData.class.id}/courses/${documentData.subject.id}`"
        class="text-blue-500 hover:underline"
      >
        {{ documentData.subject.name }}
      </router-link>
      <router-link
        v-else-if="documentData.university?.name"
        :to="`/university/${documentData.university.id}/courses/${documentData.subject.id}`"
        class="text-blue-500 hover:underline"
      >
        {{ documentData.subject.name }}
      </router-link>
    </span>
            <span class="text-gray-600 text-sm">
      <span class="font-medium">{{ documentData.class?.name ? 'Class: ' : 'University: ' }}</span>
      <router-link
        v-if="documentData.class?.name"
        :to="`/class/${documentData.class.id}`"
        class="text-blue-500 hover:underline"
      >
        {{ documentData.class.name }}
      </router-link>
      <router-link
        v-else-if="documentData.university?.name"
        :to="`/university/${documentData.university.id}`"
        class="text-blue-500 hover:underline"
      >
        {{ documentData.university.name }}
      </router-link>
    </span>
          </div>
        </div>

        <p class="text-gray-600 text-sm mb-2">
          {{ documentData.description }}
        </p>
        <!-- Rejection Reason -->
        <div v-if="documentData.is_approved === 'rejected'">
          <span class="font-medium text-red-500">Rejection Reason</span>
          <p>{{ getReasonLabel(documentData.reject_reason) }}</p>
          <div v-if="documentData.reject_reason === 'other'">
            <p>Note: {{ documentData.user_note }}</p>
          </div>
        </div>

        <!-- Report Details -->
        <div v-if="documentData.is_approved === 'approved' && documentData.reports && documentData.reports.length">
          <h2 class="font-medium text-red-600">Reports</h2>
          <ul class="list-disc relative">
            <li v-for="report in documentData.reports" :key="report.id">
              <p>Reported by: {{ report.user.email }}</p>
              <p>Reason: {{ getReasonLabel(report.report_reason.reason) }}</p>
              <p>Note: {{ report.user_note }}</p>
            </li>
          </ul>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="grid grid-cols-4 border-b">
        <button @click="downloadDocument" class="flex flex-col items-center gap-1 p-4 hover:bg-gray-50">
          <Download class="w-5 h-5" />
          <span class="text-xs">Tài về</span>
        </button>
        <button class="flex flex-col items-center gap-1 p-4 hover:bg-gray-50" @click="toggleSaveDocument">
          <Bookmark class="w-5 h-5 " :class="isSaved ? 'text-green-600' : ''"/>
          <span class="text-xs">{{ isSaved ? 'Bỏ lưu' : 'Lưu' }}</span>
        </button>
        <button class="flex flex-col items-center gap-1 p-4 hover:bg-gray-50" :class="isLiked ? 'text-blue-600' : ''"
          @click="likeDocument">
          <ThumbsUp :class="isLiked ? 'text-blue-600' : ''" class="w-5 h-5" />
          <span class="text-xs">{{ likeCount }}</span>
        </button>
        <button class="flex flex-col items-center gap-1 p-4 hover:bg-gray-50" :class="isDisliked ? 'text-red-600' : ''"
          @click="dislikeDocument">
          <ThumbsDown :class="isDisliked ? 'text-red-600' : ''" class="w-5 h-5" />
          <span class="text-xs">{{ dislikeCount }}</span>
        </button>
      </div>

      <!-- Share Buttons -->
      <div class="grid grid-cols-3 p-2">
        <button @click="openReportPopup" class="flex items-center justify-center gap-2 p-2 hover:bg-gray-50 rounded-lg">
          <Flag class="w-5 h-5" />
          <span class="text-sm">Báo cáo</span>
        </button>
      </div>
      <!-- Modal thông báo -->
      <div v-if="showLoginModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Thông báo</h3>
        <p class="mt-2">Bạn cần phải đăng nhập để tiếp tục!</p>
        <div class="mt-4 flex justify-end">
          <button @click="showLoginModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">Đóng</button>
          <button @click="redirectToLogin" class="bg-blue-500 text-white px-4 py-2 rounded-md">Đăng nhập</button>
        </div>
      </div>
    </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col mt-16">
      <!-- Document Viewer -->
      <div class="flex-1 overflow-auto bg-gray-100">
        <div class="max-w-7xl mx-auto bg-white shadow-lg overflow-hidden">
          <!-- Hiển thị PDF -->
          <div v-if="fileType === 'pdf'">
            <iframe :src="fileURL" class="w-full h-[90vh]" frameborder="0"></iframe>
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
    <ReportPopup v-if="isReportModalOpen" :documentId="documentId" @close="closeReportModal" />
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

import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import api from "../services/api";
import { useRouter } from 'vue-router';

const fileType = ref("pdf");
const route = useRoute();
const fileURL = ref("");
const documentData = ref({
  title: "Loading...",
  uploader: { email: "" },
  subject: { name: "" },
  university: { name: "" },
  description: "",
  like_percentage: 0,
  like_count: 0,
  views: 0,
});
const isLiked = ref(false);
const isDisliked = ref(false);
const likeCount = ref(0);
const dislikeCount = ref(0);
const likePercentage = ref(0);
const isReportModalOpen = ref(false);
const totalVotes = ref(0);
const showLoginModal = ref(false);
const isLoggedIn = ref(false); 
const documentId = ref(route.params.id);

// Kiểm tra token trong localStorage
onMounted(() => {
  const token = localStorage.getItem('token');
  isLoggedIn.value = !!token; // Nếu token tồn tại, chuyển trạng thái đăng nhập thành true
});

const reasonMap = [
  { value: 'wrong-course', label: 'Tài liệu này được tải lên theo sai môn học', description: 'Nó thuộc về môn khác' },
  { value: 'wrong-title', label: 'Tên tài liệu hoặc mô tả không đúng', description: "Nội dung của tài liệu không phù hợp với mô tả hoặc tên" },
  { value: 'low-quality', label: 'Chất lượng thấp hoặc nội dung vô dụng', description: 'Nội dung có thể không liên quan, chỉ bao gồm dàn ý khóa học/trang bài giảng, có nhiều lỗi chính tả/ngữ pháp hoặc (gần như) trống.' },
  { value: 'readability', label: 'Chất lượng đọc thấp', description: 'Tài liệu khó đọc vì chữ viết tay xấu hoặc chất lượng quét/ảnh kém.' },
  { value: 'other', label: 'Lý do khác' }
];
const isSaved = ref(false)

const getReasonLabel = (value) => {
  const reason = reasonMap.find(r => r.value === value);
  return reason ? reason.label : 'Không xác định';
};

const currentUser = ref(null);

const fetchCurrentUser = () => {
  const userData = localStorage.getItem('user');
  if (userData) {
    currentUser.value = JSON.parse(userData);
  }
};

fetchCurrentUser();
const fetchDocument = async () => {
  try {
    const documentId = route.params.id;
    const response = currentUser.value 
      ? await api.userGetDocument(documentId) 
      : await api.guestGetDocument(documentId); // Thay `1` bằng ID thực tế
    documentData.value = response.data;
    console.log(documentData.value);
    fileURL.value = `http://127.0.0.1:8000/storage/${documentData.value.file_path}`;
    // Tính toán số lượt thích và không thích
    const likes = documentData.value.likes;
    likeCount.value = likes.filter(like => like.is_like).length;
    dislikeCount.value = likes.filter(like => !like.is_like).length;

    // Tính toán tổng số lượt bình chọn và phần trăm lượt thích
    updateLikePercentage();

    // Tính toán phần trăm lượt thích
    const totalVotes = likeCount.value + dislikeCount.value;
    likePercentage.value = totalVotes > 0 ? (likeCount.value / totalVotes) * 100 : 0;

    // Kiểm tra xem người dùng hiện tại đã thích hoặc không thích tài liệu hay chưa
    const userLikeStatus = likes.find(like => like.user_id === currentUser.value.id);
    isLiked.value = userLikeStatus ? userLikeStatus.is_like : false;
    isDisliked.value = userLikeStatus ? !userLikeStatus.is_like : false;
    // Check if the document is saved by the current user
    isSaved.value = documentData.value.is_saved;
  } catch (error) {
    console.error("Error fetching document:", error);
  }
};

const toggleSaveDocument = async () => {
  if (!isLoggedIn.value) {
    showLoginModal.value = true;
    return;
  }
  try {
    const documentId = route.params.id;
    if (isSaved.value) {
      await api.unsaveDocument(documentId)
      isSaved.value = false
    } else {
      await api.saveDocument(documentId)
      isSaved.value = true
    }
  } catch (error) {
    console.error('Error toggling save document:', error)
  }
}

const likeDocument = async () => {
  if (!isLoggedIn.value) {
    showLoginModal.value = true;
    return;
  }
  try {
    const documentId = route.params.id;
    if (isLiked.value) {
      await api.unlikeDocument(documentId);
      likeCount.value--;
      isLiked.value = false;
    } else {
      await api.likeDocument(documentId);
      likeCount.value++;
      isLiked.value = true;
      if (isDisliked.value) {
        dislikeCount.value--;
        isDisliked.value = false;
      }
    }
    updateLikePercentage();
  } catch (error) {
    console.error("Error liking document:", error);
  }
};

const dislikeDocument = async () => {
  if (!isLoggedIn.value) {
    showLoginModal.value = true;
    return;
  }
  try {
    const documentId = route.params.id;
    if (isDisliked.value) {
      await api.undislikeDocument(documentId);
      dislikeCount.value--;
      isDisliked.value = false;
    } else {
      await api.dislikeDocument(documentId);
      dislikeCount.value++;
      isDisliked.value = true;
      if (isLiked.value) {
        likeCount.value--;
        isLiked.value = false;
      }
    }
    updateLikePercentage();
  } catch (error) {
    console.error("Error disliking document:", error);
  }
};

const updateLikePercentage = () => {
  totalVotes.value = likeCount.value + dislikeCount.value;
  likePercentage.value = totalVotes.value > 0 ? (likeCount.value / totalVotes.value) * 100 : 0;
};

const downloadDocument = async () => {
  if (!isLoggedIn.value) {
    showLoginModal.value = true;
    return;
  }
  try {
    const documentId = route.params.id;
    const response = await api.downloadDocument(documentId, { responseType: 'blob' }); // Đảm bảo API trả về dữ liệu dạng `blob`
    const blob = new Blob([response.data]);
    const url = window.URL.createObjectURL(blob);

    // Lấy tên file từ `file_path` (nếu cần)
    const fileName = documentData.value.file_path.split('/').pop() || 'document';

    // Tạo link và kích hoạt tải xuống
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', fileName); // Đặt tên file khi tải về
    document.body.appendChild(link);
    link.click();

    // Cleanup link sau khi tải xuống
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error('Error downloading document:', error);
    alert('Đã xảy ra lỗi khi tải xuống tài liệu. Vui lòng thử lại.');
  }
};
const recordView = async () => {
  try {
    const documentId = route.params.id;
    await api.recordView(documentId)
  } catch (error) {
    console.error('Error recording view:', error)
  }
}
const router = useRouter();

const redirectToLogin = () => {
  router.push({ name: 'Login' });
};

const openReportPopup = () => {
  if (!isLoggedIn.value) {
    showLoginModal.value = true;
    return;
  }
  isReportModalOpen.value = true;
};

const closeReportModal = () => {
  isReportModalOpen.value = false;
};

onMounted(() => {
  fetchDocument();
  recordView();
});
watch(() => route.params.id, async (newId) => {
  documentId.value = newId;

  console.log('Route changed. Fetching data for new university:', route.params.id);
  fetchDocument();
  recordView();
});

</script>
