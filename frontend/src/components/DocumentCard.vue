<template>
    <div class="bg-white rounded-lg p-4 flex gap-4 hover:bg-[#bae6fd] transition-colors duration-200 cursor-pointer">
      <!-- Document Thumbnail -->
      <div class="w-24 h-32 bg-gray-100 rounded overflow-hidden flex-shrink-0">
        <img 
          :src="thumbnail" 
          :alt="title"
          class="w-full h-full object-cover"
        />
      </div>
  
      <!-- Document Info -->
      <div class="flex-grow">
        <div class="flex items-start justify-between">
          <!-- Title and Tag -->
          <div>
            <div class="flex items-center gap-2">
              <a href="#" class="text-blue-600 hover:underline font-medium">
                {{ title }}
              </a>
              <span class="text-sm text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                {{ tag }}
              </span>
            </div>
  
            <!-- Subject and Institution -->
            <div class="mt-2 space-y-1">
              <div class="flex items-center gap-2">
                <FolderIcon class="w-4 h-4 text-gray-400" />
                <a href="#" class="text-gray-600 hover:text-gray-900">
                  {{ subject }}
                </a>
              </div>
              <div class="flex items-center gap-2">
                <BuildingIcon class="w-4 h-4 text-gray-400" />
                <a href="#" class="text-gray-600 hover:text-gray-900">
                  {{ institution }}
                </a>
              </div>
            </div>
  
            <!-- Metadata -->
            <div class="flex items-center gap-4 mt-2">
              <div class="flex items-center gap-1 text-gray-500">
                <FileTextIcon class="w-4 h-4" />
                <span class="text-sm">{{ pages }} pages</span>
              </div>
              <div class="flex items-center gap-1 text-gray-500">
                <CalendarIcon class="w-4 h-4" />
                <span class="text-sm">{{ year }}</span>
              </div>
            </div>
          </div>
  
          <!-- Rating -->
          <div class="flex items-center gap-1">
            <ThumbsUpIcon class="w-4 h-4 text-green-500" />
            <span class="text-green-500 font-medium">100%</span>
            <span class="text-gray-400">(2)</span>
          </div>
        </div>
      </div>
      <!-- Delete Button -->
    <button 
      v-if="showDeleteButton"
      class="text-red-600 hover:text-red-800 font-medium"
      @click="deleteDocument"
    >
      <Trash2 />
    </button>
    </div>
  </template>
  
  <script setup>
  import { 
    FileText as FileTextIcon,
    Building as BuildingIcon,
    Folder as FolderIcon,
    Calendar as CalendarIcon,
    ThumbsUp as ThumbsUpIcon,
    Trash2
  } from 'lucide-vue-next'
  
  defineProps({
    thumbnail: {
      type: String,
      default: 'https://placehold.co/96x128'
    },
    title: {
      type: String,
      default: 'Bài tập KTTC3- Cô Mơ - KTTC 3'
    },
    tag: {
      type: String,
      default: 'Mandatory assignments'
    },
    subject: {
      type: String,
      default: 'kế toán tài chính (ABC)'
    },
    institution: {
      type: String,
      default: 'Trường Đại học Phenikaa'
    },
    pages: {
      type: Number,
      default: 26
    },
    year: {
      type: String,
      default: '2023/2024'
    },
    showDeleteButton: {
    type: Boolean,
    default: false
  }
  })
  </script>