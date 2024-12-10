<template>
  <div ref="menuContainer">
    <!-- Toggle Button for Menu -->
    <button @click="toggleMenu" class="pl-6 pr-3 rounded">
      <font-awesome-icon icon="fa-solid fa-bars" class="h-8"/>
    </button>

    <!-- Parent Categories and Subcategories -->
    <div v-if="showMenu" class="absolute bg-white shadow-lg rounded mt-2 p-4">
      <div class="absolute flex shadow text-14px top-2 left-[1px] border-2">
        <div class="h-96 w-40 bg-white space-y-2 min-w-[200px] overflow-auto">
          <div
            v-for="(category, index) in categories"
            :key="category.id"
            @mouseover="showSubCategory(category)"
            class="relative p-2 pl-3 hover:bg-gray-200 border-l-4"
            :class="`border-cat-${index % 8}`"
          >
            <a :href="'/' + category.id + '-' + category.name.replace(/ /g, '-').toLowerCase()">
              {{ category.name }}
            </a>
          </div>
        </div>

        <!-- Subcategories -->
        <div v-if="selectedCategory" class="h-96 p-4 text-white w-[800px]" :class="`bg-cat-${categories.indexOf(selectedCategory) % 8}`">
          <p class="py-2 text-2xl">{{ selectedCategory.name }}</p>
          <div class="flex">
            <div class="h-80 w-40 pr-2 pl-4 space-y-2 min-w-[200px] scroll-bar">
              <div v-for="subCategory in selectedCategory.children" :key="subCategory.id">
                <a
                  :href="'/' + subCategory.id + '-' + subCategory.name.replace(/ /g, '-').toLowerCase()"
                  class="block rounded p-2 hover:bg-black hover:bg-opacity-20"
                >
                  {{ subCategory.name }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import categoriesData from '../assets/categories.json';

export default {
  data() {
    return {
      categories: categoriesData.categories,
      selectedCategory: null,
      showParent: true,
      showMenu: false
    };
  },
  methods: {
    toggleMenu() {
      this.showMenu = !this.showMenu;
    },
    showSubCategory(category) {
      this.selectedCategory = category;
    },
    handleClickOutside(event) {
      if (!this.$refs.menuContainer.contains(event.target)) {
        this.showMenu = false;
      }
    }
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  }
};
</script>

<style scoped>
.border-cat-0 { border-left-color: #3490dc; }
.border-cat-1 { border-left-color: #38c172; }
.border-cat-2 { border-left-color: #f9c138; }
.border-cat-3 { border-left-color: #e3342f; }
.border-cat-4 { border-left-color: #9561e2; }
.border-cat-5 { border-left-color: #f6993f; }
.border-cat-6 { border-left-color: #6cb2eb; }
.border-cat-7 { border-left-color: #f66d9b; }

.bg-cat-0 { background-color: #3490dc; }
.bg-cat-1 { background-color: #38c172; }
.bg-cat-2 { background-color: #f9c138; }
.bg-cat-3 { background-color: #e3342f; }
.bg-cat-4 { background-color: #9561e2; }
.bg-cat-5 { background-color: #f6993f; }
.bg-cat-6 { background-color: #6cb2eb; }
.bg-cat-7 { background-color: #f66d9b; }
</style>
