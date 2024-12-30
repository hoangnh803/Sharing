<template>
    <div class="min-h-screen bg-white">
      <!-- Hero Section with Search -->
      <div class="relative bg-gray-900 h-64 mt-16">
        <!-- Decorative blobs -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-[#C1FF3D] rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500 rounded-full blur-3xl opacity-20"></div>
  
        <div class="max-w-7xl mx-auto px-4 h-full flex flex-col justify-center">
          <h1 class="text-white text-3xl font-bold mb-6">
            {{ classItem.name }}
          </h1>
          <div class="relative max-w-2xl">
                    <input v-model="searchQuery" @input="handleInput" @focus="showSuggestions = true"
                        @blur="hideSuggestions" type="text"
                        class="w-full py-4 px-6 rounded-full bg-white border-gray-300 border text-gray-900 placeholder-gray-500"
                        placeholder="Tìm kiếm môn học" />
                    <!-- Clear Button -->
                    <button v-if="searchQuery" @click="clearSearch" class="absolute right-16 top-2 p-2 rounded-full">
                        <XIcon class="h-6 w-6 text-gray-500 hover:text-gray-700" />
                    </button>
                    <!-- Search Button -->
                    <button @click="handleSearch" class="absolute right-2 top-2 p-2 rounded-full">
                        <Search class="h-7 w-7 text-black" />
                    </button>
                    <!-- Suggestions -->
                    <ul v-if="showSuggestions && filteredSuggestions.length"
                        class="absolute mt-1 bg-white w-full rounded-xl shadow-lg ">
                        <li v-for="subject in filteredSuggestions" :key="subject.id"
                            @mousedown.prevent="selectSuggestion(subject)"
                            class="px-4 py-2 text-left rounded-xl cursor-pointer hover:bg-gray-200 text-gray-700">
                            {{ subject.title }}
                        </li>
                    </ul>
                </div>
        </div>
      </div>

      <!-- Breadcrumb -->
      <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center text-sm text-gray-600">
                <a href="/class" class="hover:text-blue-600">Phổ thông</a>
                <ChevronRight class="w-4 h-4 mx-2" />
                <span>
                    {{ classItem.name }}
                </span>
            </div>
        </div>
  
      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column -->
                <div class="flex-grow w-3/4">
                    <!-- Courses Section -->
                    <div class="mb-12">
                        <h2 class="text-xl font-semibold mb-6 flex items-center">
                            Môn học ({{ courses.length }})
                        </h2>
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <CourseCard v-for="(course, index) in courses.slice(0, 9)" :key="index"
                                :course-title="course.title" :course-link="course.link" :course-code="course.code" />
                        </div>
                    </div>

                    <!-- Popular Documents -->
                    <div class="mb-12">
                        <h2 class="text-xl font-semibold mb-6">Tài liệu phổ biến</h2>
                        <Carousel :value="documents" :numVisible="5" :numScroll="3">
                            <template #item="slotProps">
                                <VerticalDocumentCard :doc="slotProps.data" class="m-2" />
                            </template>
                        </Carousel>
                    </div>

                    <!-- Recent Documents -->
                    <div class="mb-12">
                        <h2 class="text-xl font-semibold mb-6">Tài liệu mới</h2>
                        <Carousel :value="documents" :numVisible="5" :numScroll="2">
                            <template #item="slotProps">
                                <VerticalDocumentCard :doc="slotProps.data" class="m-2" />
                            </template>
                        </Carousel>
                    </div>
                </div>
                <!-- Right Sidebar -->
                <div class="mt-12 flex-grow w-1/4">
                    <!-- Content Categories -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="font-semibold mb-4">Phân loại</h3>
                        <div class="space-y-3">
                            <div v-for="category in categories" :key="category.name"
                                class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ category.name }}</span>
                                <span class="font-medium">{{ category.count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
  <script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { XIcon, Search, ChevronRight } from 'lucide-vue-next';
import api from '../services/api';
import CourseCard from '../components/CourseCard.vue';
import VerticalDocumentCard from '../components/VerticalDocumentCard.vue';
import Carousel from 'primevue/carousel';

  
  const route = useRoute();
  const router = useRouter();
  const documents = ref([]);
  const classItem = ref({});
  const courses = ref([]);

  const fetchClassDetails = async () => {
    try {
      const response = await api.getClass(route.params.id);
      classItem.value = response.data;
      console.log('Class details:', classItem.value); // Thêm dòng này để kiểm tra giá trị của university
      courses.value = classItem.value.subjects.map(subject => ({
            title: subject.name,
            link: `/class/${classItem.value.id}/courses/${subject.id}`,  // Adjust the URL if necessary
            institution: classItem.value.name,  // The institution name from university
            institutionLink: `/institutions/${classItem.value.id}`, // Adjust the institution link if needed
        }));
    } catch (error) {
      console.error('Error fetching class details:', error);
    }
  };
  
  const fetchDocuments = async () => {
    try {
      const response = await api.getDocumentsByClass(route.params.id);
      documents.value = response.data;
      console.log('Documents:', documents.value);
    } catch (error) {
      console.error('Error fetching documents:', error);
    }
  };
  
const searchQuery = ref('');
const showSuggestions = ref(false);

// Lọc gợi ý theo từ khóa (môn học)
const filteredSuggestions = computed(() => {
  if (!searchQuery.value) return [];
  return courses.value.filter((course) =>
    course.title.toLowerCase().includes(searchQuery.value.toLowerCase())
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

// Chọn một gợi ý (môn học)
const selectSuggestion = (courseTitle) => {
  const selectedCourse = courses.value.find((course) => course === courseTitle);
  if (selectedCourse) {
    searchQuery.value = courseTitle;
    router.push({ path: selectedCourse.link }); // Đường dẫn tới chi tiết môn học
  }
  showSuggestions.value = false;
};
// Xóa nội dung tìm kiếm
const clearSearch = () => {
  searchQuery.value = '';
};

// Tạo computed property cho categories
const categories = computed(() => [
    { name: "Tổng số tài liệu", count: documents.value.length },
    { name: "Bài giảng", count: documents.value.filter(doc => doc.document_type?.name === "Bài giảng").length },
    { name: "Tóm tắt", count: documents.value.filter(doc => doc.document_type?.name === "Tóm tắt").length },
    { name: "Bài tập", count: documents.value.filter(doc => doc.document_type?.name === "Bài tập").length },
    { name: "Khác", count: documents.value.filter(doc => !["Bài giảng", "Tóm tắt", "Bài tập"].includes(doc.document_type?.name)).length },
]);


  
  onMounted(() => {
    fetchClassDetails();
    fetchDocuments();
  });
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>