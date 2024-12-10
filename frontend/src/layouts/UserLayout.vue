<template>
    <div class="min-h-screen bg-white flex flex-col">
      <UserHeader />
      <main class="flex-grow">
        <router-view />
      </main>
      <UserFooter />
    </div>
  </template>
  
  <script setup>
  import UserHeader from '../components/UserHeader.vue';
  import UserFooter from '../components/UserFooter.vue';
  </script>
  