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
              <Button label="Phổ thông" :severity="[isScrolled ? '' : 'info']" variant="text" rounded as="router-link"
                to="/class" :class="['!px-4 !text-gray-500 !hover:text-gray-900']" />
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
        <Carousel :value="recentDocuments" :numVisible="7" :numScroll="2">
          <template #item="slotProps">
            <VerticalDocumentCard :doc="slotProps.data" class="m-2" />
          </template>
        </Carousel>
      </div>
      <!-- Recomment for you Document -->
      <div class="my-12">
        <h2 class="text-xl font-semibold mb-6">Tài liệu dành cho bạn</h2>
        <Carousel :value="relatedDocuments" :numVisible="7" :numScroll="2">
          <template #item="slotProps">
            <VerticalDocumentCard :doc="slotProps.data" class="m-2" />
          </template>
        </Carousel>
      </div>

    </div>
    <UserFooter />
  </div>
</template>


<script setup>
import Button from 'primevue/button'
import { BookOpen } from 'lucide-vue-next'
import SearchComponent from '../components/SearchComponent.vue'
import NotificationDropdown from '../components/NotificationDropdown.vue'
import UserDropdown from '../components/UserDropdown.vue'
import VerticalDocumentCard from '../components/VerticalDocumentCard.vue'
import Carousel from 'primevue/carousel';
import api from '../services/api';
import { ref, onMounted } from 'vue';
import UserFooter from '../components/UserFooter.vue';

const recentDocuments = ref([]);
const relatedDocuments = ref([]);

const user = JSON.parse(localStorage.getItem('user')); // Thay thế bằng ID người dùng thực tế nếu không có trong localStorage

const fetchRecentDocuments = async () => {
  try {
    const response = await api.getRecentDocuments(user.id);
    recentDocuments.value = response.data.recentDocuments;
    relatedDocuments.value = response.data.relatedDocuments;

    console.log('Recent documents:', relatedDocuments.value);
  } catch (error) {
    console.error('Error fetching recent documents:', error);
  }
};

onMounted(() => {
  fetchRecentDocuments();
});

</script>