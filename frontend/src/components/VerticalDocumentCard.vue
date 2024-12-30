<template>
    <div @click="goToDocumentDetail" class="cursor-pointer bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 w-[150px] h-[300px]">
      <!-- Document Preview -->
      <div class="relative w-full h-1/2 bg-gray-100 rounded-xl overflow-hidden border-8">
        <img 
          :src="preview" 
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
            <p class="text-gray-600 text-base mb-3 line-clamp-1 overflow-hidden text-ellipsis">{{ props.doc.subject.name }}</p>
            
            <!-- Rating -->
            <div class="flex items-center justify-center bg-gray-100 rounded-lg gap-2">
              <ThumbsUp class="w-4 h-4 text-green-500" />
              <span class="text-green-500 font-medium text-xs">{{ likePercentage }}%</span>
              <span class="text-gray-500 text-xs">({{ likeCount }})</span>
            </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, computed } from 'vue'
  import { useRouter } from 'vue-router'
  import { ThumbsUp } from 'lucide-vue-next'
  const router = useRouter()
  
  const props = defineProps({
    doc: {
      type: Object,
      required: true
    }
  })
  const preview = `http://127.0.0.1:8000/storage/${props.doc.preview}`

  const likes = props.doc.likes || [];

// Computed property to calculate the like percentage based on `is_like` (true for like, false for unlike)
const likePercentage = computed(() => {
  const totalLikes = likes.filter(like => like.is_like).length; // Count only likes (is_like: true)
  const totalDislikes = likes.filter(like => !like.is_like).length; // Count only dislikes (is_like: false)
  
  const totalLikesAndDislikes = totalLikes + totalDislikes;
  if (totalLikesAndDislikes === 0) return 0; // Avoid division by zero if there are no likes or dislikes
  return ((totalLikes / totalLikesAndDislikes) * 100).toFixed(2); // Calculate percentage and round to 2 decimal places
});

// Computed property to get the total number of likes
const likeCount = computed(() => {
  return likes.filter(like => like.is_like).length;
});

const goToDocumentDetail = () => {
  const documentId = props.doc.id;
  const user = JSON.parse(localStorage.getItem('user')); // Replace with actual method to get the role

  if (user && user.role === 'admin') {
    router.push({
      name: 'AdminDocumentView',
      params: { id: documentId }
    });
  } else {
    router.push({
      name: 'DocumentView',
      params: { id: documentId }
    });
  }
};
  </script>
  