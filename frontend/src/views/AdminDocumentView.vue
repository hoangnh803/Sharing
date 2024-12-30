<template>
  <div class="flex h-screen bg-gray-50">
    <!-- Left Sidebar -->
    <div class="w-80 bg-white border-r flex flex-col">
      <!-- Document Header -->
      <div class="p-6 border-b">
        <div class="flex items-center gap-2 mb-4">
          <ThumbsUp class="w-5 h-5 text-blue-600" />
          <span class="text-blue-600">{{ likePercentage}}% ({{ totalVotes }})</span>
          <span class="text-gray-600">• {{ document.view_count }} views</span>
        </div>

        <h1 class="text-xl font-bold mb-4">
          {{ document.title }}
        </h1>

        <div class="mb-4">
          <div class="flex flex-col gap-1 mb-2">
            <div class="flex items-center gap-2">
              <span class="text-gray-600">Uploaded by</span>
              <a href="#" class="text-blue-600 hover:underline">{{ document.uploader.email }}</a>
            </div>
            <span class="text-gray-600 text-sm">
              <span class="font-medium">Course:</span> {{ document.subject.name }}
            </span>
            <span class="text-gray-600 text-sm">
              <span class='font-medium' v-if="document.university">Trường đại học: {{ document.university.name }}</span>
              <span class='font-medium' v-else-if="document.class">Lớp: {{ document.class.name }}</span>            </span>
          </div>
        </div>

        <p class="text-gray-600 text-sm mb-2">
          {{ document.description }}
        </p>

        <!-- Document Status -->
        <div class="mt-4">
          <span class="font-medium">Status: </span>
          <span :class="{
            'text-green-600': document.is_approved === 'approved',
            'text-yellow-600': document.is_approved === 'pending',
            'text-red-600': document.is_approved === 'rejected',
          }">
            {{ document.is_approved === 'approved' ? 'Được duyệt' : document.is_approved === 'pending' ? 'Chờ duyệt' :
              'Bị từ chối' }}
          </span>
        </div>
        <div v-if="document.is_approved === 'rejected'" class="mt-4">
          <span class="text-sm font-medium">Lý do từ chối: </span>
          <span class="text-red-500">{{ getReasonLabel(document.reject_reason) }}</span>
          <div v-if="document.reject_reason === 'other'" class="mt-2">
            <span class="text-sm font-medium">Ghi chú: </span>
            <span class="text-gray-500">{{ document.user_note }}</span>
          </div>
        </div>
        <!-- Report Details -->
        <div v-if="document.reports && document.reports.length && document.is_approved === 'approved'">
          <h2 class="font-medium">Reports</h2>
          <ul class="list-disc pl-6 relative">
            <li v-for="report in document.reports" :key="report.id">
              <p>Reported by: {{ report.user.email }}</p>
              <p>Reason: {{ getReasonLabel(report.report_reason.reason) }}</p>
              <p>Note: {{ report.user_note }}</p>
            </li>
          </ul>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="grid grid-cols-1 border-t p-4">
        <!-- Approve Button (only if pending) -->
        <button v-if="document.is_approved === 'pending'"
          class="w-full bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 mb-2"
          @click="approveDocument">
          Phê duyệt
        </button>

        <!-- Reject Button (only if pending) -->
        <button v-if="document.is_approved === 'pending' || document.is_approved === 'approved' && document.reports && document.reports.length" @click="showRejectModal = true"
          class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 mb-2">
          Từ chối
        </button>
        <!-- Remove Report Button (only if reported) -->
        <button v-if="document.reports && document.reports.length && document.is_approved === 'approved'" @click="removeReport"
          class="w-full bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 mb-2">
          Gỡ báo cáo
        </button>

        <!-- Delete Button (only if rejected) -->
        <button v-if="document.is_approved === 'rejected'" @click="deleteDocument"
          class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 mb-4">
          Xóa tài liệu
        </button>

        <!-- Go Back Button (always visible) -->
        <button class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700" @click="goBack">
          Quay lại
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Document Viewer -->
      <div class="flex-1 overflow-auto bg-gray-100">
        <div class="max-w-7xl mx-auto bg-white shadow-lg overflow-hidden">
          <!-- Hiển thị PDF -->
          <div v-if="fileType === 'pdf'">
            <iframe :src="fileURL" class="w-full h-[90vh]" frameborder="0"></iframe>
          </div>

          <!-- Hiển thị DOC/DOCX -->
          <div v-else-if="fileType === 'doc' || fileType === 'docx'">
            <iframe :src="fileURL" class="w-full h-[80vh]" frameborder="0"></iframe>
          </div>

          <!-- Trường hợp file không hỗ trợ -->
          <div v-else class="p-6 text-center">
            <p class="text-gray-500 text-sm">
              File type <span class="font-bold">{{ fileType }}</span> is not supported.
            </p>
          </div>
        </div>
      </div>

      <!-- Reject Modal -->
      <RejectPopup v-if="showRejectModal" @close="showRejectModal = false" :rejectReasons="reportReasons"
        :onReject="rejectDocument" />
    </div>
  </div>
</template>


<script setup>
import { ThumbsUp } from "lucide-vue-next";
import { ref, onMounted, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import RejectPopup from "../components/RejectPopup.vue";
import api from '../services/api';
const route = useRoute();
const router = useRouter();
const documentId = route.params.id;

// Định nghĩa loại file và URL file
const fileType = ref("pdf"); // Loại file: 'pdf', 'doc', 'docx', ...
const fileURL = ref("");
const document = ref({
  title: 'Loading...',
  uploader: { email: '' },
  subject: { name: '' },
  university: { name: '' },
  description: '',
  status: 'pending', // New status field
  reports: [] // Add reports field
});
const showRejectModal = ref(false);
const reportReasons = ref([]);
const likeCount = ref(0);
const dislikeCount = ref(0);
const likePercentage = ref(0);
const totalVotes = ref(0);
// Fetch document data
const fetchDocument = async () => {
  try {
    const response = await api.userGetDocument(documentId);
    document.value = response.data;
    console.log(document.value.reports);

    const likes = document.value.likes;
    likeCount.value = likes.filter(like => like.is_like).length;
    dislikeCount.value = likes.filter(like => !like.is_like).length;
    totalVotes.value = likeCount.value + dislikeCount.value;
    likePercentage.value = totalVotes.value > 0 ? (likeCount.value / totalVotes.value) * 100 : 0;
    
    fileURL.value = `http://127.0.0.1:8000/storage/${document.value.file_path}`;
  } catch (error) {
    console.error('Error fetching document:', error);
  }
};

const reasonMap = [
  { value: 'wrong-course', label: 'Tài liệu này được tải lên theo sai môn học', description: 'Nó thuộc về môn khác' },
  { value: 'wrong-title', label: 'Tên tài liệu hoặc mô tả không đúng', description: "Nội dung của tài liệu không phù hợp với mô tả hoặc tên" },
  { value: 'low-quality', label: 'Chất lượng thấp hoặc nội dung vô dụng', description: 'Nội dung có thể không liên quan, chỉ bao gồm dàn ý khóa học/trang bài giảng, có nhiều lỗi chính tả/ngữ pháp hoặc (gần như) trống.' },
  { value: 'readability', label: 'Chất lượng đọc thấp', description: 'Tài liệu khó đọc vì chữ viết tay xấu hoặc chất lượng quét/ảnh kém.' },
  { value: 'other', label: 'Lý do khác' }
];

const getReasonLabel = (value) => {
  const reason = reasonMap.find(r => r.value === value);
  return reason ? reason.label : 'Không xác định';
};

// Fetch report reasons for rejection
const fetchReportReasons = async () => {
  try {
    const response = await api.getReportReasons();
    reportReasons.value = response.data;
  } catch (error) {
    console.error('Error fetching report reasons:', error);
  }
};

// Handle document approval
const approveDocument = async () => {
  const documentId = route.params.id;
  try {
    const response = await api.approveDocument(documentId);
    reportReasons.value = response.data;
    document.value.status = 'approved'; // Update status to approved
    alert(response.data.message || "Tài liệu đã được phê duyệt!");
    router.push("/admin/documents");
  } catch (error) {
    console.error("Error approving document:", error);
    alert("Đã xảy ra lỗi khi phê duyệt tài liệu. Vui lòng thử lại.");
  }
};

// Handle document rejection
const rejectDocument = async ({ reason_id, reason }) => {
  const documentId = route.params.id;
  try {
    const response = await api.rejectDocument(documentId, { reason_id, reason });
    document.value.status = 'rejected'; // Update status to rejected
    alert(response.data.message || "Tài liệu đã bị từ chối!");
    router.push("/admin/documents");
  } catch (error) {
    console.error("Error rejecting document:", error);
    alert("Đã xảy ra lỗi khi từ chối tài liệu. Vui lòng thử lại.");
  }
};

// Delete the document (if rejected)
const deleteDocument = async () => {
  const documentId = route.params.id;
  try {
    const response = await api.adminDeleteDocument(documentId);
    alert(response.data.message || "Tài liệu đã bị xóa!");
    router.push("/admin/documents");
  } catch (error) {
    console.error("Error deleting document:", error);
    alert("Đã xảy ra lỗi khi xóa tài liệu. Vui lòng thử lại.");
  }
};

// Handle report removal
const removeReport = async () => {
  try {
    const response = await api.removeReport(documentId)
    document.value.reports = [] // Clear reports
    alert(response.data.message || "Báo cáo đã được gỡ bỏ!")
    fetchDocument() // Refresh document data
  } catch (error) {
    console.error("Error removing report:", error)
    alert("Đã xảy ra lỗi khi gỡ báo cáo. Vui lòng thử lại.")
  }
}

onMounted(() => {
  fetchDocument();
  fetchReportReasons();
});
watch(() => route.params.id, async () => {
  console.log('Route changed. Fetching data for new university:', route.params.id);
  fetchDocument();
  fetchReportReasons();
});

// Navigate back
const goBack = () => {
  router.back();
};
</script>


<style scoped>
/* Add any additional styles here */
</style>
