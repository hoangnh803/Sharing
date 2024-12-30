<template>
  <a :href="courseLink" class="bg-gray-100 rounded-xl p-4 hover:shadow-md transition-shadow duration-200 flex flex-col">
    <!-- Course Label -->
    <div class="flex items-center gap-2 mb-3">
      <div class="w-5 h-5 text-green-500">
        <FolderIcon class="w-full h-full" />
      </div>
      <span class="text-sm text-gray-600">Course</span>
    </div>

    <!-- Course Title -->
    <div class="text-blue-500 hover:text-blue-600 text-lg font-medium block mb-3">
      {{ courseTitle }}
    </div>

    <!-- Course Code -->
    <div class="text-gray-700 text-sm mb-6">
    </div>

    <!-- Institution (Luôn nằm cuối thẻ) -->
    <div class="text-gray-700 text-sm mt-auto">
      {{ institution }}
    </div>
  </a>
</template>
  
  <script setup>
  import { Folder as FolderIcon } from 'lucide-vue-next'
  
  defineProps({
    courseTitle: {
      type: String,
      required: true
    },
    courseCode: {
      type: String,
      default: ''
    },
    courseLink: {
      type: String,
      default: '#'
    },
    institution: {
      type: String,
      default: ''
    },
    institutionLink: {
      type: String,
      default: '#'
    }
  })
  </script>