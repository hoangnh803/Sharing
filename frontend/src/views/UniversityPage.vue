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
      <div class="hidden md:block flex-grow mx-96">
        <SearchComponent placeholder="Tìm kiếm trường đại học của bạn" @search-query="handleSearchQuery" />
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
            :label="university.name" :to="{ path: university.link, query: { name: university.name } }" as="router-link">
            <BuildingIcon class="w-5 h-5 text-red-400" />
            <span class="text-sm">{{ university.name }}</span>
          </Button>
        </div>
      </div>
    </div>
  </div>
  
</template>

<script setup>
import SearchComponent from '../components/SearchComponent.vue'
import Button from 'primevue/button'
import { Building as BuildingIcon } from 'lucide-vue-next'

const universities = [
  { id: '01', name: 'Đại học Bách Khoa Hà Nội', link: '/university/dai-hoc-bach-khoa-ha-noi' },
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

<style scoped>
/* Add your styles here */
</style>