<template>
    <div class="min-h-screen bg-white mt-12">
        <!-- Banner -->
        <div class="h-32 bg-emerald-500 bg-[url('/education-pattern.svg')] bg-repeat"></div>

        <!-- Profile Header -->
        <div class="container mx-auto px-4 -mt-12">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-32 h-32 bg-gray-200 rounded-lg shadow-lg flex items-center justify-center">
                        <GraduationCap class="w-16 h-16 text-gray-400" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold">{{ user.name }}</h1>
                    </div>
                </div>
                <div class=" grid grid-cols-3 mt-16 border-b divide-x">
                    <div class="text-center p-2">
                        <div class="text-gray-500 text-sm">LƯỢT XEM</div>
                        <div class="font-semibold">{{ user.viewCount }}</div>
                    </div>
                    <div class="text-center p-2">
                        <div class="text-gray-500 text-sm">TÀI LIỆU</div>
                        <div class="font-semibold">{{ user.documentCount }}</div>
                    </div>
                    <div class="text-center p-2">
                        <div class="text-gray-500 text-sm">LƯỢT TẢI</div>
                        <div class="font-semibold">{{ user.downloadCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 mt-8">
            <div class="flex gap-8">
                <!-- Sidebar -->
                <div class="w-64">
                    <nav class="space-y-1">
                        <router-link 
                            to="/user/profile"
                            :class="{'bg-emerald-500 text-white': isActiveRoute('/user/profile'), 'text-gray-600': !isActiveRoute('/user/profile')}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                            <User class="w-5 h-5" />
                            <span>Thông tin cá nhân</span>
                        </router-link>
                        <router-link 
                            to="/user/documents"
                            :class="{'bg-emerald-500 text-white': isActiveRoute('/user/documents'), 'text-gray-600': !isActiveRoute('/user/documents')}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                            <FileText class="w-5 h-5" />
                            <span>Quản lý tài liệu</span>
                        </router-link>

                        <router-link 
                            to="/user/notifications"
                            :class="{'bg-emerald-500 text-white': isActiveRoute('/user/notifications'), 'text-gray-600': !isActiveRoute('/user/notifications')}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                            <Bell class="w-5 h-5" />
                            <span>Thông báo</span>
                        </router-link>
                        <router-link 
                            to="/user/saved"
                            :class="{'bg-emerald-500 text-white': isActiveRoute('/user/saved'), 'text-gray-600': !isActiveRoute('/user/notifications')}"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg">
                            <BookMarked class="w-5 h-5" />
                            <span>Tài liệu đã lưu</span>
                        </router-link>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <router-view></router-view> <!-- Nội dung thay đổi tùy theo route -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { GraduationCap, FileText, User, Bell, BookMarked } from 'lucide-vue-next'
import api from '../services/api'

const route = useRoute()
const user = ref({
    id: 0,
    name: 'Nguyễn Huy Hoàng',
    viewCount: 0,
    documentCount: 0,
    downloadCount: 0
})


// Retrieve user data from localStorage when component is mounted
onMounted(() => {
  const storedUser = JSON.parse(localStorage.getItem('user'))
  if (storedUser) {
    user.value.name = storedUser.name
    user.value.id = storedUser.id
  }
})
// Lấy thống kê lượt xem, tài liệu, lượt tải từ API
onMounted(async () => {
    const response = await api.getUserStatistics(user.value.id)
    console.log(response)
    user.value.viewCount = response.data.total_views
    user.value.documentCount = response.data.document_count
    user.value.downloadCount = response.data.total_downloads
})
// Hàm kiểm tra xem route hiện tại có khớp với route của link không
const isActiveRoute = (path) => {
    return route.path === path
}
</script>
