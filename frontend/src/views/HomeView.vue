<template>
  <div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav :class="['border-b border-white/10 fixed w-full top-0 z-50 transition-colors duration-300 bg-[#f9faff]']">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <router-link to="/" class="flex-shrink-0 flex items-center">
              <BookOpen class="h-8 w-8 text-black" />
              <span class="ml-2 text-xl font-bold text-black">EduShare</span>
            </router-link>
            <div class="hidden md:flex items-center ml-20 space-x-8">
              <Button label="Đại học" :severity="[isScrolled ? '' : 'info']" variant="text" rounded as="router-link"
                to="/university" :class="['!px-4 !text-gray-500 !hover:text-gray-900']" />
              <Button label="Phổ thông" :severity="[isScrolled ? '' : 'info']" variant="text" rounded
                :class="['!px-4 !text-gray-500 !hover:text-gray-900']" />
            </div>
          </div>
          <div class="hidden md:flex items-center space-x-8">
            <Button label="Tải lên" severity="info" variant="text"
              :class="['!px-4 !text-gray-500 !hover:text-gray-900']" as="router-link"
              to="/upload"/>
              <NotificationDropdown />
              <!-- Avatar -->
              <UserDropdown />
          </div>
        </div>
      </div>
    </nav>
    <div class="relative overflow-hidden mt-16 py-10 px-16">
      <SearchComponent @search-query="handleSearchQuery" />
      <!-- Continue Reading Document -->
      <div class="my-12">
        <h2 class="text-xl font-semibold mb-6">Tiếp tục đọc</h2>
        <Carousel :value="documents" :numVisible="7" :numScroll="2">
          <template #item="slotProps">
            <VerticalDocumentCard :doc="slotProps.data" class="m-2" />
          </template>
        </Carousel>
      </div>
      <!-- Recomment for you Document -->
      <div class="my-12">
        <h2 class="text-xl font-semibold mb-6">Tài liệu dành cho bạn</h2>
        <Carousel :value="documents" :numVisible="7" :numScroll="2">
          <template #item="slotProps">
            <VerticalDocumentCard :doc="slotProps.data" class="m-2" />
          </template>
        </Carousel>
      </div>

    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue'
import Button from 'primevue/button'
import { BookOpen } from 'lucide-vue-next'
import SearchComponent from '../components/SearchComponent.vue'
import NotificationDropdown from '../components/NotificationDropdown.vue'
import UserDropdown from '../components/UserDropdown.vue'
import VerticalDocumentCard from '../components/VerticalDocumentCard.vue'
import Carousel from 'primevue/carousel';

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

</script>