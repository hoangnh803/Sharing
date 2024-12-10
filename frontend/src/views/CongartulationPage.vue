<template>
    <div class="min-h-screen bg-white relative overflow-hidden">
      <!-- Decorative Blobs -->
      <div class="absolute top-0 left-0 w-48 h-48 bg-fuchsia-400 rounded-full opacity-50 blur-3xl"></div>
      <div class="absolute bottom-0 left-20 w-64 h-64 bg-purple-300 rounded-full opacity-50 blur-3xl"></div>
      <div class="absolute top-40 right-0 w-96 h-96 bg-lime-200 rounded-full opacity-50 blur-3xl"></div>
    
      <!-- Main Content -->
      <div class="relative z-10 container mx-auto px-4 flex flex-col items-center pt-8">
        <!-- Illustration -->
        <div class="w-full max-w-xl ">
          <img 
            src="https://st.depositphotos.com/1000764/2519/i/450/depositphotos_25199313-stock-photo-thank-you-word-over-white.jpg"
            alt="Document Processing"
            class="w-full h-auto"
          />
        </div>
    
        <!-- Success Message -->
        <h1 class="text-3xl font-semibold text-gray-800 mb-4">
          Thanks for sharing
        </h1>
        
        <p class="text-gray-600 text-center mb-2">
          Your documents are now being processed, this can take a couple of minutes.
        </p>
        <p class="text-gray-600 text-center mb-12 flex">
          Keep an eye on your 
          <div class="text-blue-500 hover:underline px-2"><Bell /></div>
           to know when they're ready.
        </p>
    
        
    
        <!-- Actions -->
        <Button as="router-link"  to="/upload" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center gap-2 mb-4" >
          <Upload class="w-5 h-5" />
          Upload more documents
        </Button>
    
        <Button  variant="link" as="router-link"  to="/home" href="#" class="text-blue-500 hover:underline">
          Go back to the document
        </Button>
      </div>
    </div>
    </template>
    
    <script setup>
    import Button from 'primevue/button'
    import { Bell, Upload } from 'lucide-vue-next'
    </script>