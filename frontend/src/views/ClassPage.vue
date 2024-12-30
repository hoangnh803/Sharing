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
          Bạn đang học lớp mấy?
        </h1>
        <div  class="max-w-4xl mx-auto flex flex-row flex-wrap justify-center items-start mt-12 gap-8">
          <Button class="w-40" v-for="classItem in classes" :key="classItem.id" raised rounded="" size="large" href="#" severity="secondary"
             @click="navigateToClass(classItem.id)">
            {{ classItem.name }}
          </Button>
        </div>
  
        
  </div>
    </div>
    
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import api from '../services/api';
  import Button from 'primevue/button';
  
  

  const router = useRouter();
  const classes = ref([]);

const fetchClasses = async () => {
  try {
    const  classesResponse = await api.getClasses()
    classes.value = classesResponse.data;
  } catch (error) {
    console.error('Error fetching universities and classes:', error);
  }
};


const navigateToClass = (classId) => {
  router.push({ path: `/class/${classId}` });
};


  
  onMounted(() => {
    fetchClasses();
  });
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>