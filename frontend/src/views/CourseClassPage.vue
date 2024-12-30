<template>
    <div class="min-h-screen bg-white">

        <div class="bg-[#f3f9f0] p-6 mt-12 px-20">
            <!-- Breadcrumb Navigation -->
            <div class="flex items-center gap-2 text-sm mb-4">
                <a href="/class" class="text-blue-500 hover:underline">Phổ thông</a>
                <ChevronRight class="w-4 h-4 text-gray-400" />
                <router-link :to="{ path: `/class/${classId}` }" class="text-blue-500 hover:underline">{{
                    className }}</router-link>
                <ChevronRight class="w-4 h-4 text-gray-400" />
                <span class="text-gray-600">{{ subjectName }}</span>
            </div>

            <!-- Course Header -->
            <div class="flex items-start justify-between py">
                <div class="flex items-start gap-3">
                    <div class="mt-1">
                        <Folder class="w-12 h-12 text-green-500" />
                    </div>

                    <div>
                        <h1 class="text-2xl font-semibold mb-2">
                            {{ subjectName }}
                        </h1>

                        <div class="flex items-center gap-4 text-gray-600 text-sm">
                            <div class="flex items-center gap-1">
                                <FileText class="w-4 h-4" />
                                <span>{{ documents.length }} documents</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="relative w-80">
                    <input v-model="searchQuery" @input="handleInput" @focus="showSuggestions = true"
                        @blur="hideSuggestions" type="text"
                        class="w-full py-4 px-6 rounded-full bg-white border-gray-300 border text-gray-900 placeholder-gray-500"
                        :placeholder="`Tìm trong ${subjectName}`" />
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

        <!-- Trending Document -->
        <div class="my-12 mx-20">
            <h2 class="text-xl font-semibold mb-6">Thịnh hành</h2>
            <Carousel :value="documents" :numVisible="7" :numScroll="2" :showIndicators="false">
                <template #item="slotProps">
                    <VerticalDocumentCard :doc="slotProps.data" class="m-2" />
                </template>
            </Carousel>
        </div>

        <!-- New Document -->
        <div class="my-12 mx-20">
            <h2 class="text-xl font-semibold mb-6">Mới</h2>
            <Carousel :value="documents" :numVisible="7" :numScroll="2" :showIndicators="false">
                <template #item="slotProps">
                    <VerticalDocumentCard :doc="slotProps.data" class="m-2" />
                </template>
            </Carousel>
        </div>

        <!-- Lecture Notes -->
        <div class="p-6 mx-20">
            <div v-for="(docs, categoryName) in categorizedDocuments" :key="categoryName" class="mb-12">
                <!-- Header cho từng loại -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold">{{ categoryName }}</h2>
                    <div class="flex gap-8 mr-28">
                        <!-- Sort by Date -->
                        <div class="relative">
                            <button @click="toggleFilter('date')" class="flex items-center gap-2 px-3 py-1" :class="{
                                'text-green-500 hover:text-green-600': activeSort.type === 'date',
                                'text-gray-600 hover:text-gray-800': activeSort.type !== 'date'
                            }">
                                Ngày
                                <span :class="{ invisible: activeSort.type !== 'date' }">
                                    <ChevronUp v-if="activeSort.direction === 'asc'" class="w-4 h-4" />
                                    <ChevronDown v-else class="w-4 h-4" />
                                </span>
                            </button>
                        </div>

                        <!-- Sort by Rating -->
                        <div class="relative">
                            <button @click="toggleFilter('rating')" class="flex items-center gap-2 px-3 py-1" :class="{
                                'text-green-500 hover:text-green-600': activeSort.type === 'rating',
                                'text-gray-600 hover:text-gray-800': activeSort.type !== 'rating'
                            }">
                                Đánh giá
                                <span :class="{ invisible: activeSort.type !== 'rating' }">
                                    <ChevronUp v-if="activeSort.direction === 'asc'" class="w-4 h-4" />
                                    <ChevronDown v-else class="w-4 h-4" />
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Danh sách tài liệu -->
                <div class="space-y-4">
                    <div @click="goToDocumentDetail(doc)" v-for="doc in docs.slice(0, visibleDocuments)" :key="doc.id"
                        class="flex items-center justify-between rounded-lg p-4 border-b hover:bg-[#bae6fd] transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center gap-4">
                            <!-- Document Preview -->
                            <div class="w-20 h-28 bg-gray-100 rounded-lg overflow-hidden border-4">
                                <img :src="`http://127.0.0.1:8000/storage/${doc.preview}`" :alt="doc.title" class="w-full h-full object-cover" />
                            </div>

                            <!-- Document Info -->
                            <div>
                                <a :href="doc.link" class="text-blue-500 hover:underline font-medium">{{ doc.title
                                    }}</a>
                                <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                                    <FileText class="w-4 h-4" />
                                    <span>{{ doc.pageNumber }} {{ doc.pageNumber > 1 ? 'pages' : 'page' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div class="flex items-center gap-8 mr-28">
                            <div class="text-sm text-gray-500 pr-10">{{ doc.created_at.split('-')[0] }}</div>
                            <div class="flex items-center gap-2 w-24">
                                <ThumbsUp  class="w-4 h-4 text-green-500" />
                                <span v-if="doc.likes.length > 0" class="text-green-500">{{ ((doc.likes.filter(like => like.is_like).length / doc.likes.length) * 100).toFixed(2) }}%</span>
                                <span v-if="doc.likes.length > 0" class="text-gray-500 text-sm">({{ doc.likes.filter(like => like.is_like).length }})</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Show More -->
                <div v-if="docs.length > 6" class="flex justify-between mt-8 gap-4">
                    <Button v-if="visibleDocuments < docs.length" @click="showAllDocuments" class="w-full" severity="secondary" variant="outlined">
                        Hiện tất cả tài liệu
                        <ChevronDown class="w-4 h-4" />
                    </Button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import Button from 'primevue/button'
import VerticalDocumentCard from '../components/VerticalDocumentCard.vue'
import api from '../services/api'
import { onMounted, computed, ref } from 'vue'
import {
    ChevronRight,
    Folder,
    FileText,
    ChevronDown,
    ChevronUp,
    ThumbsUp,
} from 'lucide-vue-next'

import { useRoute, useRouter } from 'vue-router'
import { Search, X as XIcon } from 'lucide-vue-next';
import Carousel from 'primevue/carousel';

// Trạng thái active và chiều sort
const activeSort = ref({ type: null, direction: null });

function toggleFilter(type) {
    if (activeSort.value.type === type) {
        // Nếu đã active thì đổi chiều sort
        activeSort.value.direction = activeSort.value.direction === 'asc' ? 'desc' : 'asc';
    } else {
        // Nếu chưa active thì set kiểu sort và mặc định chiều sort là asc
        activeSort.value.type = type;
        activeSort.value.direction = 'asc';
    }
}
const route = useRoute();

const router = useRouter();
const documents = ref([]);
const classId = ref(route.params.classId);
const subjectId = ref(route.params.subjectId);
const documentTypes = ref([]);


const className = ref('');
const subjectName = ref('');

const fetchDocuments = async () => {
    try {
        const response = await api.getDocumentsByClassAndSubject(classId.value, subjectId.value);
        documents.value = response.data;
        if (documents.value.length > 0) {
            className.value = documents.value[0].class.name;
            subjectName.value = documents.value[0].subject.name;
        }
        console.log('Documents:', documents.value);
    } catch (error) {
        console.error('Failed to fetch documents:', error);
    }
};
const fetchDocumentTypes = async () => {
    try {
        const response = await api.getDocumentTypes();
        documentTypes.value = response.data;
    } catch (error) {
        console.error('Failed to fetch document types:', error);
    }
};
const categorizedDocuments = computed(() => {
    const categories = {};
    documents.value.forEach(doc => {
        const typeName = doc.document_type.name;
        if (!categories[typeName]) {
            categories[typeName] = [];
        }
        categories[typeName].push(doc);
    });
    return categories;
});

const searchQuery = ref('');
const showSuggestions = ref(false);

// Lọc gợi ý theo từ khóa (môn học)
const filteredSuggestions = computed(() => {
  if (!searchQuery.value) return [];
  return documents.value.filter((document) =>
  document.title.toLowerCase().includes(searchQuery.value.toLowerCase())
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
const selectSuggestion = (title) => {
  const selectedDocument = documents.value.find((document) => document === title);
  if (selectedDocument) {
    console.log('Selected document:', selectedDocument);
    searchQuery.value = title;
    router.push({ path: `/document/${selectedDocument.id}` });
  }
  showSuggestions.value = false;
};
// Xóa nội dung tìm kiếm
const clearSearch = () => {
  searchQuery.value = '';
};



const visibleDocuments = ref(6); // Số tài liệu hiển thị ban đầu

// Hàm hiển thị tất cả tài liệu
function showAllDocuments() {
    visibleDocuments.value = documents.value.length; // Hiển thị tất cả tài liệu
}

const goToDocumentDetail = (doc) => {
  const user = JSON.parse(localStorage.getItem('user')); // Replace with actual method to get the role

  if (user && user.role === 'admin') {
    router.push({
      name: 'AdminDocumentView',
      params: { id: doc.id }
    });
  } else {
    router.push({
      name: 'DocumentView',
      params: { id: doc.id }
    });
  }
};

onMounted(() => {
    fetchDocuments();
    fetchDocumentTypes();
});



</script>