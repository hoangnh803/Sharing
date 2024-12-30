<template>
  <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-xl">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <h2 v-if="!isSubmitted" class="text-xl font-semibold">Báo cáo tài liệu</h2>
        <h2 v-else class="text-xl font-semibold">Cảm ơn</h2>
        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" @click="$emit('close')">
          <X class="w-5 h-5 text-gray-500" />
        </button>
      </div>

      <!-- Content -->
      <div class="px-6 py-2" v-if="!isSubmitted">
        <div class="space-y-6">
          <!-- Reason Options -->
          <label class="flex items-start gap-3 cursor-pointer" v-for="reason in reportReasons" :key="reason.id">
            <input type="radio" name="report-reason" :value="reason.id" v-model="selectedReason" class="mt-1" />
            <div>
              <div class="text-gray-900 font-medium mb-1">{{ reason.label }}</div>
              <div v-if="reason.description" class="text-gray-500">
                {{ reason.description }}
              </div>
            </div>
          </label>

          <!-- Other Reason Input -->
          <div v-if="selectedReason == '5'" class="mt-4">
            <textarea v-model="otherReason" rows="1" class="w-full p-3 border rounded-lg"
              placeholder="Vui lòng mô tả lý do của bạn"></textarea>
            <p v-if="otherReasonError" class="text-red-500 text-sm mt-1">
              Vui lòng nhập lý do cụ thể.
            </p>
          </div>
        </div>
      </div>

      <!-- Thank You Content -->
      <div class="p-6 text-center" v-else>
        <p class="text-lg font-semibold text-gray-900">Cảm ơn bạn đã báo cáo!</p>
        <p class="text-gray-500 mt-2">Sự đóng góp của bạn giúp cho chúng tôi cải thiện chất lượng.</p>
      </div>

      <!-- Footer -->
      <div v-if="!isSubmitted" class="flex justify-end gap-3 p-6 border-t">
        <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" @click="$emit('close')">
          Hủy
        </button>
        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
           @click="handleSubmit">
          Gửi
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { X } from 'lucide-vue-next'
import api from '../services/api' // Adjust the path as necessary



const reportReasons = ref([]);
const selectedReason = ref(null)
const otherReason = ref('')
const isSubmitted = ref(false)
const otherReasonError = ref(false);

const props = defineProps({
  documentId: {
    type: Number,
    required: true
  }
})

const fetchReportReasons = async () => {
  try {
    const response = await api.getReportReasons(); // Fetch từ API
    // Kết hợp dữ liệu từ response với reasonMap
    reportReasons.value = response.data.map(reason => {
      const mappedReason = reasonMap.find(item => item.value === reason.reason);
      return {
        ...reason,
        ...mappedReason, // Thêm label và description từ reasonMap
      };
    });
  } catch (error) {
    console.error('Error fetching report reasons:', error);
  }
};

const reasonMap = [
  { value: 'wrong-course', label: 'Tài liệu này được tải lên theo sai môn học', description: 'Nó thuộc về môn khác' },
  { value: 'wrong-title', label: 'Tên tài liệu hoặc mô tả không đúng', description: "Nội dung của tài liệu không phù hợp với mô tả hoặc tên" },
  { value: 'low-quality', label: 'Chất lượng thấp hoặc nội dung vô dụng', description: 'Nội dung có thể không liên quan, chỉ bao gồm dàn ý khóa học/trang bài giảng, có nhiều lỗi chính tả/ngữ pháp hoặc (gần như) trống.' },
  { value: 'readability', label: 'Chất lượng đọc thấp', description: 'Tài liệu khó đọc vì chữ viết tay xấu hoặc chất lượng quét/ảnh kém.' },
  { value: 'other', label: 'Lý do khác' }
];



const handleSubmit = async () => {
  if (!selectedReason.value) {
    alert('Vui lòng chọn lý do từ chối!');
    return;
  }
  if (selectedReason.value == '5' && !otherReason.value.trim()) {
    otherReasonError.value = true; // Hiển thị lỗi
    return; // Ngăn gửi form
  }

  otherReasonError.value = false; // Xóa lỗi khi nhập hợp lệ
  try {
    const payload = {
      reason_id: selectedReason.value,
      user_note: selectedReason.value == '5' ? otherReason.value : null,
    };
    console.log('document:', props.documentId, 'payload:', payload);
    await api.reportDocument(props.documentId, payload);
    isSubmitted.value = true;
  } catch (error) {
    console.error('Error reporting document:', error);
  }
}
onMounted(() => {
  fetchReportReasons();
});</script>

<style scoped></style>