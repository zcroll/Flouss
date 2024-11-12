<template>
  <AppLayout>
    <!-- Page Header -->
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        How to Obtain a {{ degree.name }} Degree
      </h2>
    </template>

    <!-- Add Ruller component here -->
  

    <!-- Sticky Sidebar with Degree Information -->
    <StickySidebar
      :slug="degree.slug"
      :title="degree.name"
      :image="degree.image"
      :id="degree.id"
      type="degree"
      :isFavorited="degree.is_favorited"
    >
      <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-b-3xl shadow-2xl">
        <nav class="flex items-center space-x-2 text-sm mb-8 font-['aktiv-grotesk','Helvetica_Neue',Helvetica,Arial,sans-serif]">
          <Link :href="route('dashboard')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('Home') }}</Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <Link :href="route('degrees.index')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('Degrees') }}</Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <Link :href="route('degree.index', { slug: degree.slug })" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ degree.name }}</Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <span class="text-gray-400 font-medium border-b-2 border-gray-400 transition-all duration-200 ease-in-out hover:text-blue-600 hover:border-blue-600">{{ __('How to Obtain') }}</span>
        </nav>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="">
            <!-- Section Title -->            <!-- Filter Section -->
            <aside class="block">
              <div class="bg-white">
                <section aria-labelledby="filter-heading" class="mb-10">
                  <div class="border-b border-gray-200 bg-white pb-4">
                    <div class="mx-auto flex max-w-7xl items-center justify-end gap-x-6 px-4 sm:px-6 lg:px-8">
                      <div class="relative inline-block text-left" ref="cityDropdown">
                        <button @click="toggleCityDropdown" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                          City
                          <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg>
                        </button>
                        <div v-show="showCityDropdown" class="absolute right-0 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 overflow-y-scroll z-50">
                          <div class="p-2">
                            <div v-for="ville in uniqueVilles" :key="ville" class="flex items-center justify-end space-x-2 p-2">
                              <label :for="`city-${ville}`" class="text-sm text-gray-600">{{ ville }}</label>
                              <input type="checkbox" :id="`city-${ville}`" :value="ville" v-model="selectedCities" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="relative inline-block text-left" ref="categoryDropdown">
                        <button @click="toggleCategoryDropdown" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                          Category
                          <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                          </svg>
                        </button>
                        <div v-show="showCategoryDropdown" class="absolute right-0 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 overflow-y-scroll z-50">
                          <div class="p-2">
                            <div v-for="category in uniqueCategories" :key="category" class="flex items-center justify-end space-x-2 p-2">
                              <label :for="`category-${category}`" class="text-sm text-gray-600">{{ category }}</label>
                              <input type="checkbox" :id="`category-${category}`" :value="category" v-model="selectedCategories" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                
              </div>
            </aside>

            <!-- Formations List -->
            <Formation :formations="filteredFormations" />
          </div>
         
        </div>
          <BackToTop />
          
          
      </div>
      
      
    </StickySidebar>
    <Ruller />
  </AppLayout>
</template>

<script>
import { defineComponent, ref, onMounted, onBeforeUnmount } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'
import Formation from '@/Components/Formation.vue'
import BackToTop from "@/Components/BackToTop.vue";
import Ruller from '@/Pages/ruller.vue'

export default defineComponent({
  components: {
      BackToTop,
    AppLayout,
    StickySidebar,
    Link,
    Formation,
    Ruller,
  },
  props: {
    degree: Object,
    formations: Array,
  },
  data() {
    return {
      selectedCategories: [],
      selectedCities: [],
      open: ref(false),
      showCityDropdown: false,
      showCategoryDropdown: false,
    };
  },
  methods: {
    toggleCityDropdown() {
      this.showCityDropdown = !this.showCityDropdown;
      this.showCategoryDropdown = false;
    },
    toggleCategoryDropdown() {
      this.showCategoryDropdown = !this.showCategoryDropdown;
      this.showCityDropdown = false;
    },
    closeDropdowns() {
      this.showCityDropdown = false;
      this.showCategoryDropdown = false;
    },
    handleClickOutside(event) {
      const cityDropdown = this.$refs.cityDropdown;
      const categoryDropdown = this.$refs.categoryDropdown;
      if (cityDropdown && !cityDropdown.contains(event.target)) {
        this.showCityDropdown = false;
      }
      if (categoryDropdown && !categoryDropdown.contains(event.target)) {
        this.showCategoryDropdown = false;
      }
    },
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  },
  computed: {
    uniqueCategories() {
      return [...new Set(this.formations.map(formation => formation.etablissement.categoryEtablissement))];
    },
    uniqueVilles() {
      return [...new Set(this.formations.map(formation => formation.etablissement.ville))];
    },
    filteredFormations() {
      return this.formations.filter(formation => {
        const matchesCategory = this.selectedCategories.length ? this.selectedCategories.includes(formation.etablissement.categoryEtablissement) : true;
        const matchesVille = this.selectedCities.length ? this.selectedCities.includes(formation.etablissement.ville) : true;
        return matchesCategory && matchesVille;
      });
    },
  },
})
</script>

<style scoped>
/* Add any necessary styles */
</style>
