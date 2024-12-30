<template>
  <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-xl">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-xl font-semibold">Lý do từ chối</h2>
        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" @click="$emit('close')">
          <X class="w-5 h-5 text-gray-500" />
        </button>
      </div>

      <!-- Content -->
      <div class="px-6 py-2">
        <div class="space-y-6">
          <!-- Reason Options -->
          <label class="flex items-start gap-3 cursor-pointer" v-for="reason in mappedRejectReasons" :key="reason.value">
            <input type="radio" name="reject-reason" :value="reason.value" v-model="selectedReasonId" class="mt-1" />
            <div>
              <div class="text-gray-900 font-medium mb-1">{{ reason.label }}</div>
              <div v-if="reason.description" class="text-gray-500">
                {{ reason.description }}
              </div>
            </div>
          </label>

          <!-- Other Reason Input -->
          <div v-if="selectedReasonId === 'other'" class="mt-4">
            <textarea v-model="note" rows="1" class="w-full p-3 border rounded-lg" placeholder="Vui lòng mô tả lý do của bạn"></textarea>
            <p v-if="noteError" class="text-red-500 text-sm mt-1">
              Vui lòng nhập lý do cụ thể.
            </p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex justify-end gap-3 p-6 border-t">
        <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" @click="$emit('close')">
          Hủy
        </button>
        <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors" @click="handleReject">
          Từ chối
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { X } from 'lucide-vue-next'

const props = defineProps({
  rejectReasons: {
    type: Array,
    required: true,
  },
  onReject: {
    type: Function,
    required: true,
  },
})

// Local state
const selectedReasonId = ref(null)
const note = ref('')
const noteError = ref(false)

// Mapping of values to labels and descriptions
const reasonMap = {
  'wrong-course': {
    label: 'Tài liệu này được tải lên theo sai môn học',
    description: 'Nó thuộc về môn khác',
  },
  'wrong-title': {
    label: 'Tên tài liệu hoặc mô tả không đúng',
    description: 'Nội dung của tài liệu không phù hợp với mô tả hoặc tên',
  },
  'low-quality': {
    label: 'Chất lượng thấp hoặc nội dung vô dụng',
    description: 'Nội dung có thể không liên quan, chỉ bao gồm dàn ý khóa học/trang bài giảng, có nhiều lỗi chính tả/ngữ pháp hoặc (gần như) trống.',
  },
  'readability': {
    label: 'Chất lượng đọc thấp',
    description: 'Tài liệu khó đọc vì chữ viết tay xấu hoặc chất lượng quét/ảnh kém.',
  },
  'other': {
    label: 'Lý do khác',
    description: '',
  },
}

// Computed property to map rejectReasons to an array with labels and descriptions
const mappedRejectReasons = computed(() => {
  return props.rejectReasons.map((item) => {
    const reason = item.reason; // Get the reason string
    return {
      ...reasonMap[reason], // Add label and description based on the reason
      value: reason, // Use the reason field
    };
  });
});


const handleReject = () => {
  if (!selectedReasonId.value) {
    alert('Vui lòng chọn lý do từ chối!');
    return;
  }

  // You need to map the selected reason to its corresponding ID for insertion
  const selectedReason = props.rejectReasons.find((reason) => reason.reason === selectedReasonId.value);
  const reasonId = selectedReason ? selectedReason.id : null;

  if (selectedReasonId.value === 'other' && !note.value.trim()) {
    noteError.value = true;
    return;
  }

  noteError.value = false;

  // Pass the reasonId (integer) instead of the reason string
  props.onReject({ reason_id: reasonId, reason: note.value });
};

</script>

<style scoped>
</style>
