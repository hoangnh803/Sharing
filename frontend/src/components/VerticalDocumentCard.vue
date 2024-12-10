<template>
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 w-[150px] h-[300px]">
      <!-- Document Preview -->
      <div class="relative w-full h-1/2 bg-gray-100 rounded-t-xl overflow-hidden">
        <img 
          :src="props.doc.preview" 
          :alt="props.doc.title"
          class="w-full h-full object-cover"
        />
        <!-- Page Number -->
        <div class="absolute bottom-2 right-2 bg-white/80 backdrop-blur-sm rounded-full px-3 py-1 text-sm text-gray-600">
          {{ props.doc.pageNumber }}
        </div>
      </div>
  
      <!-- Document Info -->
      <div class="flex flex-col justify-betweenflex flex-col justify-between p-2 h-1/2">
        <a 
          :href="props.doc.link" 
          class="text-blue-500 hover:text-blue-600 font-medium line-clamp-3 mb-2 text-base overflow-hidden text-ellipsis"
        >
          {{ props.doc.title }}
        </a>
        <div class=" mt-auto">
            <p class="text-gray-600 text-base mb-3 line-clamp-1 overflow-hidden text-ellipsis">{{ props.doc.course }}</p>
            
            <!-- Rating -->
            <div class="flex items-center justify-center bg-gray-100 rounded-lg gap-2">
              <ThumbsUp class="w-4 h-4 text-green-500" />
              <span class="text-green-500 font-medium text-xs">{{ props.doc.rating }}%</span>
              <span class="text-gray-500 text-xs">({{ props.doc.reviews }})</span>
            </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue'
  import { ThumbsUp } from 'lucide-vue-next'
  
  const props = defineProps({
    doc: {
      type: Object,
      required: true
    }
  })
  </script>
  