<template>
    <div class="relative " ref="searchContainer" :class="{'small': isSmall}">
      <!-- Ô tìm kiếm -->
      <input
        type="text"
        v-model="searchQuery"
        @input="onSearch"
        @focus="showSuggestions = true"
        :placeholder="placeholder"
        :class="[' px-6 rounded-full bg-white border-gray-300 border	text-gray-900 placeholder-gray-500', isSmall ? 'py-2 w-96' : 'w-full py-4']"
        />
  
      <!-- Nút Search -->
      <button    
        :class="['absolute right-2  p-2 rounded-full', isSmall ? 'top' : 'top-2']"
        @click="onSearchClick"
        >
        <Search class="h-7 w-7 text-black" />
      </button>
  
      <!-- Nút Clear (X) để xóa nội dung ô tìm kiếm -->
      <button v-if="searchQuery" @click="clearSearch" :class="['absolute right-12  p-2 rounded-full', isSmall ? 'top' : 'top-2']" >
        <XIcon class="h-6 w-6 text-gray-500 hover:text-gray-700" />
      </button>
  
      <!-- Hiển thị gợi ý tìm kiếm -->
      <ul
        v-if="showSuggestions && filteredSuggestions.length"
        class="absolute mt-2 bg-white w-full rounded-3xl shadow-lg z-10"
      >
        <li
          v-for="suggestion in filteredSuggestions"
          :key="suggestion"
          @click="selectSuggestion(suggestion)"
          class="px-4 py-2 text-left rounded-3xl cursor-pointer hover:bg-gray-200 text-gray-700"
        >
          {{ suggestion }}
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
  import { Search, X as XIcon } from 'lucide-vue-next';
  import { useRouter } from 'vue-router'

  const emit = defineEmits(['search-query']);
defineProps({
  isSmall: { type: Boolean, default: false }, // Adds a 'small' version
  placeholder: { type: String, default: 'Tìm kiếm khóa học, tài liệu' } // Placeholder prop
});
  const searchQuery = ref('');
  const showSuggestions = ref(false);
  const searchContainer = ref(null);
  const router = useRouter()

  const suggestions = ref([
    'Mathematics',
    'Physics',
    'Chemistry',
    'Biology',
    'Computer Science',
    'History',
    'Geography',
    'Economics',
    'Literature',
    'Engineering'
  ]);
  
  // Tạo mảng gợi ý lọc theo searchQuery
  const filteredSuggestions = computed(() => {
    if (!searchQuery.value) return [];
    return suggestions.value.filter(suggestion =>
      suggestion.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });
  
  // Phát sự kiện tìm kiếm
  const onSearch = () => {
    emit('search-query', searchQuery.value);
  };
  
  // Gọi khi nhấn nút tìm kiếm
  const onSearchClick = () => {
      emit('search-query', searchQuery.value)
      router.push({ name: 'SearchView', query: { q: searchQuery.value } })
    }
  
  // Chọn gợi ý
  const selectSuggestion = (suggestion) => {
      searchQuery.value = suggestion
      emit('search-query', suggestion)
      showSuggestions.value = false // Ẩn gợi ý khi chọn
      router.push({ name: 'SearchView', query: { q: suggestion } })
    }
  
  // Xóa nội dung ô tìm kiếm
  const clearSearch = () => {
    searchQuery.value = '';
    emit('search-query', '');
  };
  
  // Đóng gợi ý khi nhấp ra ngoài
  const handleClickOutside = (event) => {
    if (searchContainer.value && !searchContainer.value.contains(event.target)) {
      showSuggestions.value = false;
    }
  };
  
  onMounted(() => {
    document.addEventListener('click', handleClickOutside);
  });
  
  onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
  });
  </script>
  
  <style scoped>
  /* Custom styles cho component nếu cần */
  
  </style>
  