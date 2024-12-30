<template>
  <div ref="searchContainer" class="relative" :class="{ 'small': isSmall }">
    <!-- Ô tìm kiếm -->
    <input type="text" v-model="searchQuery" :placeholder="placeholder" @focus="showSuggestions = true"
      @blur="hideSuggestions" @input="handleInput" :class="[
        'px-6 rounded-full bg-white border-gray-300 border text-gray-900 placeholder-gray-500',
        isSmall ? 'py-2 w-96' : 'w-full py-4'
      ]" />
    <!-- Nút Search -->
    <button :class="['absolute right-2 p-2 rounded-full', isSmall ? 'top' : 'top-2']" @click="handleSearch">
      <Search class="h-7 w-7 text-black" />
    </button>
    <!-- Nút Clear -->
    <button v-if="searchQuery" @click="clearSearch"
      :class="['absolute right-12 p-2 rounded-full', isSmall ? 'top' : 'top-2']">
      <XIcon class="h-6 w-6 text-gray-500 hover:text-gray-700" />
    </button>
    <!-- Gợi ý -->
    <ul v-if="showSuggestions && filteredSuggestions.length"
      class="absolute mt-2 bg-white w-full rounded-3xl shadow-lg z-10">
      <li v-for="suggestion in filteredSuggestions" :key="suggestion" @mousedown.prevent="selectSuggestion(suggestion)"
        class="px-4 py-2 text-left rounded-3xl cursor-pointer hover:bg-gray-200 text-gray-700">
        {{ suggestion }}
      </li>
    </ul>
  </div>
</template>


<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { Search, X as XIcon } from 'lucide-vue-next';

const emit = defineEmits(['search-query']);
const props = defineProps({
  isSmall: { type: Boolean, default: false },
  placeholder: { type: String, default: 'Tìm kiếm khóa học, tài liệu' },
  suggestions: { type: Array, default: () => [] }
});

const searchQuery = ref('');
const showSuggestions = ref(false);
const searchContainer = ref(null);
const router = useRouter();

// Lọc gợi ý theo searchQuery
const filteredSuggestions = computed(() => {
  if (!searchQuery.value || !props.suggestions.length) return [];
  return props.suggestions.filter(suggestion =>
    suggestion.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

// Xử lý khi nhập text
const handleInput = () => {
  showSuggestions.value = !!props.suggestions.length;
};

// Ẩn gợi ý sau một thời gian ngắn để tránh mất focus
const hideSuggestions = () => {
  setTimeout(() => {
    showSuggestions.value = false;
  }, 200);
};

// Chọn một gợi ý
const selectSuggestion = (suggestion) => {
  searchQuery.value = suggestion;
  showSuggestions.value = false;
  emitSearchQuery();
};

// Xóa nội dung tìm kiếm
const clearSearch = () => {
  searchQuery.value = '';
  emit('search-query', '');
};

// Thực hiện tìm kiếm
const handleSearch = () => {
  emitSearchQuery();
};

// Gửi truy vấn tìm kiếm
const emitSearchQuery = () => {
  emit('search-query', searchQuery.value);
  router.push({ path: '/search', query: { q: searchQuery.value } });
};
</script>


<style scoped>
.small {
  width: 24rem; /* Tailwind: w-96 */
}
</style>
