<template>
    <div class="min-h-screen bg-white">

        <div class="bg-[#f3f9f0] p-6 mt-12 px-20">
            <!-- Breadcrumb Navigation -->
            <div class="flex items-center gap-2 text-sm mb-4">
                <a href="/university" class="text-blue-500 hover:underline">Đại học</a>
                <ChevronRight class="w-4 h-4 text-gray-400" />
                <a href="/university/:id" class="text-blue-500 hover:underline">Đại học Bách Khoa Hà Nội</a>
                <ChevronRight class="w-4 h-4 text-gray-400" />
                <span class="text-gray-600">IoT và Ứng dụng</span>
            </div>

            <!-- Course Header -->
            <div class="flex items-start justify-between py">
                <div class="flex items-start gap-3">
                    <div class="mt-1">
                        <Folder class="w-12 h-12 text-green-500" />
                    </div>

                    <div>
                        <h1 class="text-2xl font-semibold mb-2">
                            IoT và Ứng dụng
                        </h1>

                        <div class="flex items-center gap-4 text-gray-600 text-sm">
                            <div class="flex items-center gap-1">
                                <FileText class="w-4 h-4" />
                                <span>310 documents</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class=" relative">
                    <SearchComponent is-small @search-query="handleSearchQuery" placeholder="Tìm trong IoT và Ứng dụng" />
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
            <div v-for="category in categories" :key="category.name" class="mb-12">
                <!-- Header cho từng loại -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold">{{ category.name }}</h2>
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
                    <div v-for="doc in category.documents" :key="doc.id"
                        class="flex items-center justify-between rounded-lg p-4 border-b hover:bg-[#bae6fd] transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center gap-4">
                            <!-- Document Preview -->
                            <div class="w-16 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                <img :src="doc.preview" :alt="doc.title" class="w-full h-full object-cover" />
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
                        <div class="flex items-center gap-8">
                            <div class="text-sm text-gray-500">{{ doc.year }}</div>
                            <div class="flex items-center gap-2 w-24">
                                <ThumbsUp v-if="doc.rating" class="w-4 h-4 text-green-500" />
                                <span v-if="doc.rating" class="text-green-500">{{ doc.rating }}%</span>
                                <span v-if="doc.reviews" class="text-gray-500 text-sm">({{ doc.reviews }})</span>
                                <span v-if="!doc.rating" class="text-gray-500">None</span>
                            </div>
                            <Button severity="secondary" variant="outlined">
                                <Bookmark class="w-5 h-5" />
                                <span class="text-sm">Lưu</span>
                            </Button>
                        </div>
                    </div>
                </div>
                <!-- Show More -->
                <div class="flex justify-between mt-8 gap-4">
                    <Button class="w-full" severity="secondary" variant="outlined">
                        <ChevronDown class="w-4 h-4" />
                        Hiện thêm 8 tài liệu...
                    </Button>
                    <Button class="w-full" severity="secondary" variant="outlined">
                        Hiện tất cả 126 tài liệu
                        <ChevronDown class="w-4 h-4" />
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import SearchComponent from '../components/SearchComponent.vue'
import Button from 'primevue/button'
import VerticalDocumentCard from '../components/VerticalDocumentCard.vue'
import {
    ChevronRight,
    Folder,
    FileText,
    Bookmark,
    ChevronDown,
    ChevronUp,
} from 'lucide-vue-next'

import { ref } from 'vue'

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

// Dữ liệu tài liệu mẫu
const documents = ref([
    {
        title: 'Document 1',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 5,
        course: 'Course 101',
        rating: 90,
        reviews: 120,
        link: '/document/1'
    },
    {
        title: 'Document 2 Document Document',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 3,
        course: 'Course 102',
        rating: 85,
        reviews: 80,
        link: '#'
    },
    {
        title: 'Document 3',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 12,
        course: 'Course 103',
        rating: 95,
        reviews: 200,
        link: '#'
    },
    {
        title: 'Document 3',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 12,
        course: 'Course 103',
        rating: 95,
        reviews: 200,
        link: '#'
    },
    {
        title: 'Document 3',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 12,
        course: 'Course 103',
        rating: 95,
        reviews: 200,
        link: '#'
    },
    {
        title: 'Document 3',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 12,
        course: 'Course 103',
        rating: 95,
        reviews: 200,
        link: '#'
    },
    {
        title: 'Document 3',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 12,
        course: 'Course 103',
        rating: 95,
        reviews: 200,
        link: '#'
    },
    {
        title: 'Document 3',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 12,
        course: 'Course 103',
        rating: 95,
        reviews: 200,
        link: '#'
    },
    {
        title: 'Document 3',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 12,
        course: 'Course 103',
        rating: 95,
        reviews: 200,
        link: '#'
    },
    {
        title: 'Document 3',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 12,
        course: 'Course 103',
        rating: 95,
        reviews: 200,
        link: '#'
    },
    // Thêm tài liệu khác nếu cần
]);

const categories = ref([
    {
        name: 'Bài giảng',
        documents: Array.from({ length: 7 }, (_, i) => ({
            id: i + 1,
            title: `Bài giảng ${i + 1}`,
            link: `#`,
            preview: `https://via.placeholder.com/150`,
            pageNumber: Math.floor(Math.random() * 100) + 1,
            year: 2020 + Math.floor(Math.random() * 4),
            rating: Math.random() > 0.5 ? Math.floor(Math.random() * 100) : null,
            reviews: Math.random() > 0.5 ? Math.floor(Math.random() * 50) + 1 : null,
        })),
    },
    {
        name: 'Tóm tắt',
        documents: Array.from({ length: 7 }, (_, i) => ({
            id: i + 11,
            title: `Tóm tắt ${i + 1}`,
            link: `#`,
            preview: `https://via.placeholder.com/150`,
            pageNumber: Math.floor(Math.random() * 100) + 1,
            year: 2020 + Math.floor(Math.random() * 4),
            rating: Math.random() > 0.5 ? Math.floor(Math.random() * 100) : null,
            reviews: Math.random() > 0.5 ? Math.floor(Math.random() * 50) + 1 : null,
        })),
    },
    {
        name: 'Tài liệu thực hành',
        documents: Array.from({ length: 7 }, (_, i) => ({
            id: i + 21,
            title: `Tài liệu thực hành ${i + 1}`,
            link: `#`,
            preview: `https://via.placeholder.com/150`,
            pageNumber: Math.floor(Math.random() * 100) + 1,
            year: 2020 + Math.floor(Math.random() * 4),
            rating: Math.random() > 0.5 ? Math.floor(Math.random() * 100) : null,
            reviews: Math.random() > 0.5 ? Math.floor(Math.random() * 50) + 1 : null,
        })),
    },
    {
        name: 'Bài tập',
        documents: Array.from({ length: 7 }, (_, i) => ({
            id: i + 31,
            title: `Bài tập ${i + 1}`,
            link: `#`,
            preview: `https://via.placeholder.com/150`,
            pageNumber: Math.floor(Math.random() * 100) + 1,
            year: 2020 + Math.floor(Math.random() * 4),
            rating: Math.random() > 0.5 ? Math.floor(Math.random() * 100) : null,
            reviews: Math.random() > 0.5 ? Math.floor(Math.random() * 50) + 1 : null,
        })),
    },
    {
        name: 'Hướng dẫn',
        documents: Array.from({ length: 7 }, (_, i) => ({
            id: i + 41,
            title: `Hướng dẫn ${i + 1}`,
            link: `#`,
            preview: `https://via.placeholder.com/150`,
            pageNumber: Math.floor(Math.random() * 100) + 1,
            year: 2020 + Math.floor(Math.random() * 4),
            rating: Math.random() > 0.5 ? Math.floor(Math.random() * 100) : null,
            reviews: Math.random() > 0.5 ? Math.floor(Math.random() * 50) + 1 : null,
        })),
    },
    {
        name: 'Tiểu luận',
        documents: Array.from({ length: 7 }, (_, i) => ({
            id: i + 51,
            title: `Tiểu luận ${i + 1}`,
            link: `#`,
            preview: `https://via.placeholder.com/150`,
            pageNumber: Math.floor(Math.random() * 100) + 1,
            year: 2020 + Math.floor(Math.random() * 4),
            rating: Math.random() > 0.5 ? Math.floor(Math.random() * 100) : null,
            reviews: Math.random() > 0.5 ? Math.floor(Math.random() * 50) + 1 : null,
        })),
    },
    {
        name: 'Báo cáo',
        documents: Array.from({ length: 7 }, (_, i) => ({
            id: i + 61,
            title: `Báo cáo ${i + 1}`,
            link: `#`,
            preview: `https://via.placeholder.com/150`,
            pageNumber: Math.floor(Math.random() * 100) + 1,
            year: 2020 + Math.floor(Math.random() * 4),
            rating: Math.random() > 0.5 ? Math.floor(Math.random() * 100) : null,
            reviews: Math.random() > 0.5 ? Math.floor(Math.random() * 50) + 1 : null,
        })),
    },
    {
        name: 'Khác',
        documents: Array.from({ length: 7 }, (_, i) => ({
            id: i + 71,
            title: `Tài liệu ${i + 1}`,
            link: `#`,
            preview: `https://via.placeholder.com/150`,
            pageNumber: Math.floor(Math.random() * 100) + 1,
            year: 2020 + Math.floor(Math.random() * 4),
            rating: Math.random() > 0.5 ? Math.floor(Math.random() * 100) : null,
            reviews: Math.random() > 0.5 ? Math.floor(Math.random() * 50) + 1 : null,
        })),
    },
]);

</script>