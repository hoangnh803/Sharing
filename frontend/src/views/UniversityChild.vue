<template>
    <div class="min-h-screen bg-white">

        <!-- Hero Section with Search -->
        <div class="relative bg-gray-900 h-64 overflow-hidden mt-16">
            <!-- Decorative blobs -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-[#C1FF3D] rounded-full blur-3xl opacity-20"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500 rounded-full blur-3xl opacity-20"></div>

            <div class="max-w-7xl mx-auto px-4 h-full flex flex-col justify-center">
                <h1 class="text-white text-3xl font-bold mb-6">
                    {{ universityName }}
                </h1>
                <div class="relative max-w-2xl">
                    <SearchComponent placeholder="Tìm kiếm trường đại học của bạn" @search-query="handleSearchQuery" />
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center text-sm text-gray-600">
                <a href="/university" class="hover:text-blue-600">Đại học</a>
                <ChevronRight class="w-4 h-4 mx-2" />
                <span>
                    {{ universityName }}
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
                            Môn học (205)
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

                    <!-- Other Universities -->
                    <div class="max-w-6xl mx-auto my-12">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">
                            Trường Đại học khác
                        </h2>

                        <div class="grid md:grid-cols-2 gap-x-12 gap-y-4">
                            <div v-for="university in otherUniversities" :key="university.name">
                                <Button severity="info" variant="text" size="large"
                                    :class="['!text-blue-600 !hover:text-blue-700 !w-full !justify-start !flex !items-center !gap-3 ']"
                                    :label="university.name"
                                    :to="{ path: university.link, query: { name: university.name } }" as="router-link">
                                    <BuildingIcon class="w-5 h-5 text-red-400" />
                                    <span class="text-sm">{{ university.name }}</span>
                                </Button>
                            </div>
                        </div>
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

                    <!-- University Details -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-semibold mb-4">Chi tiết đại học</h3>
                        <div class="text-sm text-gray-600">
                            <p class="mb-2">Số lượng sinh viên:</p>
                            <p class="font-medium text-gray-900">28000</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-4">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    ChevronRight,
    Building as BuildingIcon
} from 'lucide-vue-next'
import SearchComponent from '../components/SearchComponent.vue'
import CourseCard from '../components/CourseCard.vue'
import Button from 'primevue/button'
import VerticalDocumentCard from '../components/VerticalDocumentCard.vue'

import Carousel from 'primevue/carousel';


import { ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const universityName = route.query.name;

const courses = ref([
    {
        title: 'IoT và Ứng dụng',
        link: '/courses/iot-va-ung-dung',
        code: 'CNTT',
        institution: 'Trường Đại học Công nghệ',
        institutionLink: '/institutions/cong-nghe'
    },
    {
        title: 'Introduction to Machine Learning',
        link: '/courses/machine-learning',
        code: 'CNTT',
        institution: 'Trường Đại học Khoa học Tự nhiên',
        institutionLink: '/institutions/khoa-hoc'
    },
    {
        title: 'Web Development with Vue.js',
        link: '/courses/vuejs',
        code: 'CNTT',
        institution: 'Trường Đại học Bách khoa Hà Nội',
        institutionLink: '/institutions/bach-khoa'
    },
    {
        title: 'Basic Python Programming',
        link: '/courses/python-programming',
        code: 'CNTT',
        institution: 'Trường Đại học Công nghệ',
        institutionLink: '/institutions/cong-nghe'
    },
    {
        title: 'Introduction to Machine Learning',
        link: '/courses/machine-learning',
        code: 'CNTT',
        institution: 'Trường Đại học Khoa học Tự nhiên',
        institutionLink: '/institutions/khoa-hoc'
    },
    {
        title: 'Web Development with Vue.js',
        link: '/courses/vuejs',
        code: 'CNTT',
        institution: 'Trường Đại học Bách khoa Hà Nội',
        institutionLink: '/institutions/bach-khoa'
    },
    {
        title: 'Basic Python Programming',
        link: '/courses/python-programming',
        code: 'CNTT',
        institution: 'Trường Đại học Công nghệ',
        institutionLink: '/institutions/cong-nghe'
    },
    {
        title: 'Introduction to Machine Learning',
        link: '/courses/machine-learning',
        code: 'CNTT',
        institution: 'Trường Đại học Khoa học Tự nhiên',
        institutionLink: '/institutions/khoa-hoc'
    },
    {
        title: 'Web Development with Vue.js',
        link: '/courses/vuejs',
        code: 'CNTT',
        institution: 'Trường Đại học Bách khoa Hà Nội',
        institutionLink: '/institutions/bach-khoa'
    },
    {
        title: 'Basic Python Programming',
        link: '/courses/python-programming',
        code: 'CNTT',
        institution: 'Trường Đại học Công nghệ',
        institutionLink: '/institutions/cong-nghe'
    },
    {
        title: 'Introduction to Machine Learning',
        link: '/courses/machine-learning',
        code: 'CNTT',
        institution: 'Trường Đại học Khoa học Tự nhiên',
        institutionLink: '/institutions/khoa-hoc'
    },
    {
        title: 'Web Development with Vue.js',
        link: '/courses/vuejs',
        code: 'CNTT',
        institution: 'Trường Đại học Bách khoa Hà Nội',
        institutionLink: '/institutions/bach-khoa'
    },
    {
        title: 'Basic Python Programming',
        link: '/courses/python-programming',
        code: 'CNTT',
        institution: 'Trường Đại học Công nghệ',
        institutionLink: '/institutions/cong-nghe'
    },
    {
        title: 'Introduction to Machine Learning',
        link: '/courses/machine-learning',
        code: 'CNTT',
        institution: 'Trường Đại học Khoa học Tự nhiên',
        institutionLink: '/institutions/khoa-hoc'
    },
    {
        title: 'Web Development with Vue.js',
        link: '/courses/vuejs',
        code: 'CNTT',
        institution: 'Trường Đại học Bách khoa Hà Nội',
        institutionLink: '/institutions/bach-khoa'
    },
    {
        title: 'Basic Python Programming',
        link: '/courses/python-programming',
        code: 'CNTT',
        institution: 'Trường Đại học Công nghệ',
        institutionLink: '/institutions/cong-nghe'
    },
    {
        title: 'Introduction to Machine Learning',
        link: '/courses/machine-learning',
        code: 'CNTT',
        institution: 'Trường Đại học Khoa học Tự nhiên',
        institutionLink: '/institutions/khoa-hoc'
    },
    {
        title: 'Web Development with Vue.js',
        link: '/courses/vuejs',
        code: 'CNTT',
        institution: 'Trường Đại học Bách khoa Hà Nội',
        institutionLink: '/institutions/bach-khoa'
    }
])






// Dữ liệu tài liệu mẫu
const documents = ref([
    {
        title: 'Document 1',
        preview: 'https://via.placeholder.com/150',
        pageNumber: 5,
        course: 'Course 101',
        rating: 90,
        reviews: 120,
        link: '#'
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
    // Thêm tài liệu khác nếu cần
]);




const categories = [
    { name: 'Tổng số tài liệu', count: '12016' },
    { name: 'Bài giảng', count: '3964' },
    { name: 'Tóm tắt', count: '599' },
    { name: 'Bài tập', count: '870' },
    { name: 'Khác', count: '6583' },
]

const otherUniversities = [
    { id: '01', name: 'Học viện Báo chí và Tuyên truyền', link: '/university/01' },
    { name: 'Học viện Công nghệ Bưu chính Viễn thông', link: '#' },
    { name: 'Học viện Ngoại giao Việt Nam', link: '#' },
    { name: 'Học viện Ngân hàng', link: '#' },
    { name: 'Học viện Tài chính', link: '#' },
    { name: 'International University - VNU-HCM', link: '#' },
    { name: 'Royal Melbourne Institute of Technology Vietnam', link: '#' },
    { name: 'Trường Cao đẳng Thực hành FPT', link: '#' },
    { name: 'Trường Đại Học Duy Tân', link: '#' },
    { name: 'Trường Đại học Nội Vụ Hà Nội', link: '#' },
    { name: 'Trường Đại Học Thủ Dầu Một', link: '#' },
    { name: 'Trường Đại học Bách khoa - Đại học Quốc gia Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Bách khoa Hà Nội', link: '#' },
    { name: 'Trường Đại học Công nghiệp Hà Nội', link: '#' },
    { name: 'Trường Đại học Công nghiệp Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Cần Thơ', link: '#' },
    { name: 'Trường Đại học Cửu Long', link: '#' },
    { name: 'Trường Đại học FPT', link: '#' },
    { name: 'Trường Đại học Giao thông Vận tải', link: '#' },
    { name: 'Trường Đại học Khoa học Xã hội và Nhân văn', link: '#' },
    { name: 'Trường Đại học Kinh tế - Tài chính Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Kinh tế Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Kinh tế - Luật, Đại học Quốc gia Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Kinh tế, Đại học Quốc gia Hà Nội', link: '#' },
    { name: 'Trường Đại học Kinh tế, Đại học Đà Nẵng', link: '#' },
    { name: 'Trường Đại học Luật Hà Nội', link: '#' },
    { name: 'Trường Đại học Luật Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Mở Hà Nội', link: '#' },
    { name: 'Trường Đại học Mở Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Ngoại ngữ Tin học Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Ngoại thương', link: '#' },
    { name: 'Trường Đại học Nguyễn Tất Thành', link: '#' },
    { name: 'Trường Đại học Ngân hàng Thành phố Hồ Chí Minh', link: '#' },
    { name: 'Trường Đại học Sài Gòn', link: '#' },
    { name: 'Trường Đại học Thăng Long', link: '#' },
    { name: 'Trường Đại học Thương mại', link: '#' },
    { name: 'Trường Đại học Trà Vinh', link: '#' },
    { name: 'Trường Đại học Tài chính - Marketing', link: '#' },
    { name: 'Trường Đại học Văn Lang', link: '#' },
    { name: 'UEF - Đại học Kinh tế - Tài chính thành phố Hồ Chí Minh', link: '#' },
    { name: 'UEH-International School of Business', link: '#' },
    { name: 'Van Lang University', link: '#' },
    { name: 'Đại học Bách khoa Tphcm', link: '#' },
    { name: 'Đại học Hà Nội', link: '#' },
    { name: 'Đại học Hàng hải Việt Nam', link: '#' },
    { name: 'Đại học Kinh tế Quốc dân', link: '#' },
    { name: 'Đại học Sư phạm Hà Nội', link: '#' },
    { name: 'Đại học Tôn Đức Thắng', link: '#' }
]
</script>