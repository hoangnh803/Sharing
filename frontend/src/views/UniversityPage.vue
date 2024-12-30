<template>
  <div class="min-h-screen bg-white">


    <!-- Header Section -->
    <div class="w-full px-auto text-center pb-60 pt-32 bg-gray-100">
      <!-- Logo -->
      <div class="w-48 h-48 mx-auto mb-8">
        <img 
          src="../assets/university.png" 
          alt="University Logo" 
          class="w-full h-full object-contain" 
        />
      </div>

      <!-- Title -->
      <h1 class="text-2xl font-semibold text-gray-900 mb-2">
        Bạn đang học ở trường đại học nào?
      </h1>
      <p class="text-gray-600 mb-8">
        Tìm kiếm một trường đại học và tìm tài liệu học tập của trường đó
      </p>

      <!-- Search Bar -->
      <div class="relative mx-96">
        <input
          v-model="searchQuery"
          @input="handleInput"
          @focus="showSuggestions = true"
          @blur="hideSuggestions"
          type="text"
          class="w-full py-4 px-6 rounded-full bg-white border-gray-300 border text-gray-900 placeholder-gray-500"
          placeholder="Tìm kiếm trường đại học của bạn"
        />
        <!-- Clear Button -->
        <button v-if="searchQuery" @click="clearSearch" class="absolute right-16 top-2 p-2 rounded-full">
          <XIcon class="h-6 w-6 text-gray-500 hover:text-gray-700" />
        </button>
        <!-- Search Button -->
        <button @click="handleSearch" class="absolute right-2 top-2 p-2 rounded-full">
          <Search class="h-7 w-7 text-black" />
        </button>
        <!-- Suggestions -->
        <ul
          v-if="showSuggestions && filteredSuggestions.length"
          class="absolute mt-1 bg-white w-full rounded-xl shadow-lg z-10"
        >
          <li
            v-for="university in filteredSuggestions"
            :key="university.id"
            @mousedown.prevent="selectSuggestion(university)"
            class="px-4 py-2 text-left rounded-xl cursor-pointer hover:bg-gray-200 text-gray-700"
          >
            {{ university }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Universities List Section -->
    <div class="max-w-6xl mx-auto my-12">
      <h2 class="text-xl font-semibold text-gray-900 mb-6">
        Các trường đại học phổ biến
      </h2>

      <div class="grid md:grid-cols-2 gap-x-12 gap-y-4">
        <div v-for="university in universities" :key="university.name">
          <Button severity="info" variant="text" size="large"
            :class="['!text-blue-600 !hover:text-blue-700 !w-full !justify-start !flex !items-center !gap-3 ']"
            :label="university.name" :to="{ path: `/university/${university.id}` }" as="router-link">
            <BuildingIcon class="w-5 h-5 text-red-400" />
            <span class="text-sm">{{ university.name }}</span>
          </Button>
        </div>
      </div>
    </div>
  </div>
  
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../services/api';
import Button from 'primevue/button'
import { Building as BuildingIcon } from 'lucide-vue-next'
import { Search, X as XIcon } from 'lucide-vue-next';
import { useRouter } from 'vue-router';

const universities = ref([]);
const universitySuggestions = ref([]);
const searchQuery = ref('');
const showSuggestions = ref(false);
const router = useRouter();


const fetchUniversities = async () => {
  try {
    const response = await api.getUniversities();
    universities.value = response.data;
    universitySuggestions.value = response.data.map(university => university.name);
  } catch (error) {
    console.error('Error fetching universities:', error);
  }
};


// Lọc gợi ý theo từ khóa
const filteredSuggestions = computed(() => {
  if (!searchQuery.value) return [];
  return universitySuggestions.value.filter((name) =>
    name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
// Xử lý khi nhập text
const handleInput = () => {
  showSuggestions.value = !!filteredSuggestions.value.length;

};

// Ẩn gợi ý sau một khoảng thời gian ngắn
const hideSuggestions = () => {
  setTimeout(() => {
    showSuggestions.value = false;
  }, 200);
};

// Chọn một gợi ý
const selectSuggestion = (name) => {
  const university = universities.value.find((u) => u.name === name);
  if (university) {
    searchQuery.value = university.name;
    router.push({ path: `/university/${university.id}` });
  }
  showSuggestions.value = false;
};

// Xóa nội dung tìm kiếm
const clearSearch = () => {
  searchQuery.value = '';
};

const handleSearch = () => {
  const matchedUniversity = universities.value.find(
    (university) =>
      university.name.toLowerCase() === searchQuery.value.toLowerCase()
  );
  if (matchedUniversity) {
    router.push({ path: `/university/${matchedUniversity.id}` });
  }
};

onMounted(() => {
  fetchUniversities();
});
</script>

<style scoped>
/* Add your styles here */
</style>