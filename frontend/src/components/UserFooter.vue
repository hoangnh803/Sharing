<template>
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
              <component
                :is="social === 'facebook' ? Facebook : social === 'twitter' ? Twitter : Instagram"
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
  </template>
  
  <script setup>
  import { BookOpen } from 'lucide-vue-next';
  import { Facebook, Twitter, Instagram } from 'lucide-vue-next';
  
  const companyLinks = ['About', 'Careers', 'Blog'];
  const helpLinks = ['Help Center', 'Contact Us'];
  const legalLinks = ['Terms of Service', 'Privacy Policy'];
  </script>
  