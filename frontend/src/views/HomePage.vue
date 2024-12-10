<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav
      :class="['border-b border-white/10 fixed w-full top-0 z-50 transition-colors duration-300', isScrolled ? 'bg-gray-100  ' : 'bg-transparent']">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <router-link to="/" class="flex-shrink-0 flex items-center">
              <BookOpen class="h-8 w-8 " :class="[isScrolled ? 'text-black' : 'text-white']" />
              <span class="ml-2 text-xl font-bold " :class="[isScrolled ? 'text-black' : 'text-white']">EduShare</span>
            </router-link>
            <div class="hidden md:flex items-center ml-20 space-x-8">
              <Button label="Đại học" :severity="[isScrolled ? '' : 'info']" variant="text" rounded as="router-link"  to="/university"
                :class="['!px-4', isScrolled ? '!text-gray-500 !hover:text-gray-900' : '']" />
              <Button label="Phổ thông" :severity="[isScrolled ? '' : 'info']" variant="text" rounded
                :class="['!px-4', isScrolled ? '!text-gray-500 !hover:text-gray-900' : '']" />
            </div>
          </div>
          <div class="flex items-center gap-4">
            <Button label="Tải lên" severity="info" variant="text"
              :class="['!px-4', isScrolled ? '!text-gray-500 !hover:text-gray-900' : '']" />
            <Button label="Đăng nhập" severity="success" rounded as="router-link"  to="/login"/>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-[#112f59]">
      <!-- Decorative blobs -->
      <div class="absolute top-0 left-0 w-64 h-64 bg-purple-300/20 rounded-full blur-3xl"></div>
      <div class="absolute top-20 right-0 w-96 h-96 bg-[#FF8A3D] rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 right-20 w-72 h-72 bg-[#C1FF3D] rounded-full blur-3xl"></div>

      <div class="max-w-4xl mx-auto text-center pt-56 pb-56 px-4 relative z-10">
        <h1 class="text-5xl font-bold text-white mb-4">
          Chia sẻ và học tập với EduShare
        </h1>
        <p class="text-white/80 text-xl mb-8">
          Truy cập vào kho tài liệu học tập, chia sẻ kiến thức và cộng tác với sinh viên trên toàn thế giới.
        </p>
        <SearchComponent @search-query="handleSearchQuery" />

      </div>
    </div>

    <!-- Stats Section -->
    <div class=" px-36 py-24 text-center bg-white">
      <div class="flex flex-col items-center mb-12">
        <h2 class="text-4xl font-bold text-black mb-2">70 Triệu học sinh, sinh viên được hỗ trợ và vẫn đang tăng</h2>
        <p class="text-gray-600">
          5000 tài liệu học tập mới được thêm vào mỗi ngày, từ các cộng đồng sinh viên năng động nhất thế giới        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <div class="text-center">
          <div class="text-5xl font-bold text-black mb-2">3 Triệu</div>
          <div class="text-sm text-gray-600">Tài liệu học tập</div>
          <div class="inline-block px-3 py-1 bg-purple-100 rounded-full text-xs text-purple-600 mt-2">
            1 tài liệu mới mỗi phút
          </div>
        </div>
        <div class="text-center">
          <div class="text-5xl font-bold text-black mb-2">11K</div>
          <div class="text-sm text-gray-600">Tổ chức</div>
          <div class="inline-block px-3 py-1 bg-green-100 rounded-full text-xs text-green-600 mt-2">
            100% đã xác thực
          </div>
        </div>
        <div class="text-center">
          <div class="text-5xl font-bold text-black mb-2">6 Triệu</div>
          <div class="text-sm text-gray-600">Người dùng</div>
          <div class="inline-block px-3 py-1 bg-orange-100 rounded-full text-xs text-orange-600 mt-2">
            Mỗi tháng
          </div>
        </div>
      </div>
    </div>


    <!-- Universities Section -->
    <div class="max-w-4xl mx-auto px-4 py-16">
      <h2 class="text-3xl font-bold text-black text-4xl text-center mb-12">
        Only the best for the best
      </h2>
      <div class="bg-white rounded-xl p-6">
        <div class="flex gap-8 mb-6 border-b justify-center">
          <button @click="activeTab = 'universities'" :class="{
            'pb-2 border-b-2 border-white': activeTab !== 'universities',
            'text-blue-600 border-b-2 border-blue-600 pb-2': activeTab === 'universities',
            'text-gray-500 hover:text-gray-700': activeTab !== 'universities'
          }">
            Đại học
          </button>
          <button @click="activeTab = 'PhoThong'" :class="{
            'pb-2 border-b-2 border-white': activeTab !== 'PhoThong',
            'text-blue-600 border-b-2 border-blue-600 pb-2': activeTab === 'PhoThong',
            'text-gray-500 hover:text-gray-700': activeTab !== 'PhoThong'
          }">
            Phổ thông
          </button>
        </div>

        <!-- Nội dung cho mỗi tab -->
        <div v-if="activeTab === 'universities'" class="flex flex-row flex-wrap justify-center items-start mb-12 gap-4">
          <div v-for="university in universities" :key="university">
            <Button severity="info" variant="outlined" rounded="" size="large">
              {{ university }}
            </Button>
          </div>


        </div>

        <div v-if="activeTab === 'PhoThong'" class="flex flex-row flex-wrap justify-center items-start mb-12 gap-8">
          <Button v-for="grade in grades" :key="grade" rounded="" size="large" href="#" severity="info"
            variant="outlined">
            Lớp {{ grade }}
          </Button>
        </div>

        <div v-if="activeTab === 'books'" class="text-center mb-12 text-gray-500">
          <p>Books content goes here.</p>
        </div>

        <!-- Nút "View all" chỉ hiển thị trong tab Universities -->
        <Button v-if="activeTab === 'universities'" rounded class="!w-full" variant="outlined" as="router-link"  to="/university" >
          Xem tất cả trường đại học
        </Button>
      </div>
    </div>

    <!-- Career Section -->
    <div class="bg-[#112f59] py-16">
      <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Bắt đầu khám phá hàng triệu tài liệu hấp dẫn</h2>
        <p class="text-white/80 mb-8">
          Tìm kiến thức chuyên sâu về bất kỳ chủ đề nào và câu trả lời mà bạn không thể tìm thấy ở bất kỳ nơi nào khác
        </p>
        <Button severity="info" variant="outlined" @click="scrollToTop"
        >
          Let's go
        </Button>
      </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-white/10 py-12">
      <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-4 gap-8">
        <div>
          <div class="flex-shrink-0 flex items-center">
            <BookOpen class="h-8 w-8 text-black" />
            <span class="ml-2 text-xl font-bold text-black">EduShare</span>
          </div>
          <div class="flex gap-4">
            <a v-for="social in ['facebook', 'twitter', 'instagram']" :key="social" href="#"
              class="text-black/60 hover:text-white">
              <component :is="social === 'facebook' ? Facebook : social === 'twitter' ? Twitter : Instagram"
                class="h-5 w-5" />
            </a>
          </div>
        </div>
        <div>

          <h3 class="text-black font-semibold mb-4">Company</h3>
          <div class="grid gap-2">
            <a v-for="link in companyLinks" :key="link" href="#" class="text-black/60 hover:text-white">
              {{ link }}
            </a>
          </div>
        </div>
        <div>
          <h3 class="text-black font-semibold mb-4">Contact & Help</h3>
          <div class="grid gap-2">
            <a v-for="link in helpLinks" :key="link" href="#" class="text-black/60 hover:text-white">
              {{ link }}
            </a>
          </div>
        </div>
        <div>
          <h3 class="text-black font-semibold mb-4">Legal</h3>
          <div class="grid gap-2">
            <a v-for="link in legalLinks" :key="link" href="#" class="text-black/60 hover:text-white">
              {{ link }}
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import {  Facebook, Twitter, Instagram, BookOpen } from 'lucide-vue-next'
import Button from 'primevue/button';
import SearchComponent from '../components/SearchComponent.vue'; // Import SearchComponent

const scrollToTop = () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth', // Cuộn mượt
      });
    };


const activeTab = ref('universities'); // Tab mặc định là "universities"

const universities = ref([
  'Học viện Công nghệ Bưu chính Viễn thông',
  'Trường Đại học Bách khoa - Đại học Quốc gia Thành phố Hồ Chí Minh',
  'Trường Đại học Cần Thơ',
  'Trường Đại học Giao thông Vận tải',
  'Trường Đại học Kinh tế - Luật, Đại học Quốc gia Thành phố Hồ Chí Minh',
  'Trường Đại học Kinh tế, Đại học Quốc gia Hà Nội',
  'Trường Đại học Luật Hà Nội',
  'Trường Đại học Ngân hàng Thành phố Hồ Chí Minh',
  'Trường Đại học Thăng Long',
  'Trường Đại học Tài chính - Marketing'
])

const grades = Array.from({ length: 12 }, (_, i) => i + 1) // Tạo mảng chứa các lớp từ 1 đến 12


const companyLinks = [
  'About Us',
  'Blog',
  'Jobs',
  'Academic Integrity',
  'Student World University Ranking 2023'
]

const helpLinks = [
  'FAQ',
  'Contact',
  'Newsroom'
]

const legalLinks = [
  'Terms',
  'Privacy Policy',
  'Cookie Settings',
  'Cookie Statement'
]
const isScrolled = ref(false);

const handleScroll = () => {
  isScrolled.value = window.scrollY > 0;
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);
});

onBeforeUnmount(() => {
  window.removeEventListener("scroll", handleScroll);
});

const handleSearchQuery = (query) => {
  console.log('Tìm kiếm:', query);
};
</script>