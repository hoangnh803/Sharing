<template>
    <div class="min-h-screen bg-white">
        <!-- Navigation Bar -->
        <nav :class="['border-b border-white/10 fixed w-full top-0 z-50 transition-colors duration-300 bg-[#f9faff]']">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo and Search Bar -->
                    <div class="flex items-center space-x-8">
                        <router-link to="/" class="flex-shrink-0 flex items-center">
                            <BookOpen class="h-8 w-8 text-black" />
                            <span class="ml-2 text-xl font-bold text-black">EduShare</span>
                        </router-link>
                        <!-- Search Bar -->
                        <div class="hidden md:block flex-grow mx-4">
                            <SearchComponent is-small @search-query="handleSearchQuery" />
                        </div>
                    </div>

                    <!-- Other Navigation Buttons -->
                    <div class="hidden md:flex items-center space-x-8">
                        <Button label="Đại học" :severity="[isScrolled ? '' : 'info']" variant="text" rounded
                            as="router-link" to="/university" :class="['!px-4 !text-gray-500 !hover:text-gray-900']" />
                        <Button label="Phổ thông" :severity="[isScrolled ? '' : 'info']" variant="text" rounded
                            :class="['!px-4 !text-gray-500 !hover:text-gray-900']" />
                        <Button label="Upload" severity="info" variant="text"
                            :class="['!px-4 !text-gray-500 !hover:text-gray-900']" />
                        <Button label="Sign In" severity="success" rounded as="router-link" to="/login" />
                    </div>
                </div>
            </div>
        </nav>
        <div class="mt-20 top-16 bg-white z-10">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap gap-4 items-center p-4">

                    <SelectButton v-model="value" :options="options" />
                </div>

            </div>
        </div>
        <!-- Course Cards -->
        <div class="max-w-7xl mx-auto px-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <CourseCard 
                    v-for="(course, index) in courses" 
                    :key="index" 
                    :course-title="course.title" 
                    :course-link="course.link" 
                    :institution="course.institution" 
                    :institution-link="course.institutionLink" 
                />
            </div>
        </div>

        <!-- Filter Bar with MultiSelect -->
        <div class="top-16 bg-white  z-10 ">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap gap-4 items-center p-4">
                    <FloatLabel v-for="filter in filters" :key="filter.name" class="w-full md:w-52" variant="on">
                        <MultiSelect v-model="selectedFilters[filter.name]" :options="filter.options"
                            :maxSelectedLabels="2" showClear class="w-full" />
                        <label>{{ filter.name }}</label>
                    </FloatLabel>
                    <button @click="clearAll" class="text-sm text-gray-500 hover:text-gray-700">
                        Clear all
                    </button>
                </div>
            </div>
        </div>
        


        <!-- Search Results -->
        <div class="max-w-7xl mx-auto py-4 px-4 mt-4">
            <h1 class="text-2xl font-semibold mb-4">Search Results for "{{ searchQuery }}"</h1>
            <div class="space-y-4">
                <DocumentCard v-for="doc in documents" :key="doc.id" :thumbnail="doc.thumbnail" :title="doc.title"
                    :tag="doc.tag" :subject="doc.subject" :institution="doc.institution" :pages="doc.pages"
                    :year="doc.year" />
            </div>
        </div>
        <!-- Footer -->
    <footer class="border-t py-12">
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
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import {  BookOpen } from 'lucide-vue-next'
import SearchComponent from '../components/SearchComponent.vue'
import DocumentCard from '../components/DocumentCard.vue';

import MultiSelect from 'primevue/multiselect'
import FloatLabel from 'primevue/floatlabel';
import Button from 'primevue/button'
import SelectButton from 'primevue/selectbutton';
import CourseCard from '../components/CourseCard.vue'

const route = useRoute()
const searchQuery = ref(route.query.q || '')

const value = ref('Đại học');
const options = ref(['Đại học', 'Phổ thông']);

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

const courses = ref([
    {
        title: 'Basic Python Programming',
        link: '/courses/python-programming',
        institution: 'Trường Đại học Công nghệ',
        institutionLink: '/institutions/cong-nghe'
    },
    {
        title: 'Introduction to Machine Learning',
        link: '/courses/machine-learning',
        institution: 'Trường Đại học Khoa học Tự nhiên',
        institutionLink: '/institutions/khoa-hoc'
    },
    {
        title: 'Web Development with Vue.js',
        link: '/courses/vuejs',
        institution: 'Trường Đại học Bách khoa Hà Nội',
        institutionLink: '/institutions/bach-khoa'
    }
])

const filters = ref([
    { name: 'Category', options: ['All', 'Lecture Notes', 'Past Exams', 'Assignments'] },
    { name: 'Length', options: ['Any Length', 'Short', 'Medium', 'Long'] },
    { name: 'File Type', options: ['All Types', 'PDF', 'DOC', 'PPT'] },
    { name: 'New Release', options: ['All Time', 'Last 24 hours', 'Last week', 'Last month'] },
    { name: 'Language', options: ['All Languages', 'English', 'Vietnamese', 'French'] }
])

const selectedFilters = ref({
    Category: [],
    Length: [],
    FileType: [],
    NewRelease: [],
    Language: []
})

const documents = ref([
    {
        id: 1,
        title: 'Introduction to Computer Science - Lecture Notes',
        thumbnail: 'https://placehold.co/96x128',
        pages: 45,
        views: '2.5K',
        downloads: '1.2K',
        rating: 95,
        tags: ['Computer Science', 'Lecture Notes', 'PDF']
    },
    {
        id: 1,
        title: 'Introduction to Computer Science - Lecture Notes',
        thumbnail: 'https://placehold.co/96x128',
        pages: 45,
        views: '2.5K',
        downloads: '1.2K',
        rating: 95,
        tags: ['Computer Science', 'Lecture Notes', 'PDF']
    },
    {
        id: 1,
        title: 'Introduction to Computer Science - Lecture Notes',
        thumbnail: 'https://placehold.co/96x128',
        pages: 45,
        views: '2.5K',
        downloads: '1.2K',
        rating: 95,
        tags: ['Computer Science', 'Lecture Notes', 'PDF']
    },
    // Additional documents here...
])

const clearAll = () => {
    selectedFilters.value = {
        Category: [],
        Length: [],
        FileType: [],
        NewRelease: [],
        Language: []
    }
}

const handleSearchQuery = (query) => {
    searchQuery.value = query
    // Trigger search based on the updated query
}

onMounted(() => {
    searchQuery.value = route.query.q || ''
    // Perform search with searchQuery.value if needed
})
</script>

<style scoped>
/* Custom styles for the search view */
</style>