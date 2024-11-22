<template>
  <AppLayout>
    <!-- Page Header -->
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        How to Obtain a {{ degree.name }} Degree
      </h2>
    </template>

    <!-- Sticky Sidebar with Degree Information -->
    <StickySidebar
      :slug="degree.slug"
      :title="degree.name"
      :image="degree.image"
      :id="degree.id"
      type="degree"
      :isFavorited="degree.is_favorited"
    >
      <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white mb-5 rounded-b-3xl shadow-2xl">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center space-x-2 text-sm mb-8 font-['aktiv-grotesk','Helvetica_Neue',Helvetica,Arial,sans-serif]">
          <Link :href="route('dashboard')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-[#db492b] hover:border-[#db492b]">{{ __('Home') }}</Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <Link :href="route('degrees.index')" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-[#db492b] hover:border-[#db492b]">{{ __('Degrees') }}</Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <Link :href="route('degree.index', { slug: degree.slug })" class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-[#db492b] hover:border-[#db492b]">{{ degree.name }}</Link>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
          <span class="text-gray-400 font-medium">{{ __('How to Obtain') }}</span>
        </nav>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="">
            <!-- Filter Section -->
            <aside class="mb-8">
              <div class="bg-white rounded-xl shadow-sm border border-[#db492b]/10">
                <section aria-labelledby="filter-heading" class="p-4">
                  <h3 id="filter-heading" class="sr-only">Formation filters</h3>
                  <div class="flex flex-wrap gap-4">
                    <!-- City Filter -->
                    <div class="relative" ref="cityDropdown">
                      <button @click="toggleCityDropdown" class="flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-[#db492b]/20 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#db492b]/20 transition duration-200">
                        <span class="mr-2">{{ selectedCities.length ? `${selectedCities.length} Cities` : 'City' }}</span>
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                      </button>
                      <div v-show="showCityDropdown" class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="p-2 max-h-60 overflow-auto">
                          <div v-for="ville in uniqueVilles" :key="ville" class="flex items-center px-4 py-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                            <input type="checkbox" :id="`city-${ville}`" :value="ville" v-model="selectedCities" class="h-4 w-4 rounded border-gray-300 text-[#db492b] focus:ring-[#db492b]" />
                            <label :for="`city-${ville}`" class="ml-3 text-sm text-gray-600">{{ ville }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Level Filter -->
                    <div class="relative" ref="niveauDropdown">
                      <button @click="toggleNiveauDropdown" class="flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-[#db492b]/20 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#db492b]/20 transition duration-200">
                        <span class="mr-2">{{ selectedNiveaux.length ? `${selectedNiveaux.length} Levels` : 'Level' }}</span>
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                      </button>
                      <div v-show="showNiveauDropdown" class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="p-2 max-h-60 overflow-auto">
                          <div v-for="niveau in uniqueNiveaux" :key="niveau" class="flex items-center px-4 py-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                            <input type="checkbox" :id="`niveau-${niveau}`" :value="niveau" v-model="selectedNiveaux" class="h-4 w-4 rounded border-gray-300 text-[#db492b] focus:ring-[#db492b]" />
                            <label :for="`niveau-${niveau}`" class="ml-3 text-sm text-gray-600">{{ niveau }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Institution Type Filter -->
                    <div class="relative" ref="typeDropdown">
                      <button @click="toggleTypeDropdown" class="flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-[#db492b]/20 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#db492b]/20 transition duration-200">
                        <span class="mr-2">{{ selectedTypes.length ? `${selectedTypes.length} Types` : 'Institution Type' }}</span>
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                      </button>
                      <div v-show="showTypeDropdown" class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="p-2 max-h-60 overflow-auto">
                          <div v-for="type in uniqueTypes" :key="type" class="flex items-center px-4 py-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                            <input type="checkbox" :id="`type-${type}`" :value="type" v-model="selectedTypes" class="h-4 w-4 rounded border-gray-300 text-[#db492b] focus:ring-[#db492b]" />
                            <label :for="`type-${type}`" class="ml-3 text-sm text-gray-600">{{ type }}</label>
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
    <!-- <Ruller /> -->
  </AppLayout>
</template>

<script>
import { defineComponent, ref, onMounted, onBeforeUnmount } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'
import Formation from '@/Components/helpers/Formation.vue'
import BackToTop from "@/Components/helpers/BackToTop.vue"
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
      selectedNiveaux: [],
      selectedTypes: [],
      open: ref(false),
      showCityDropdown: false,
      showCategoryDropdown: false,
      showNiveauDropdown: false,
      showTypeDropdown: false,
    }
  },
  methods: {
    toggleCityDropdown() {
      this.showCityDropdown = !this.showCityDropdown
      this.showCategoryDropdown = false
      this.showNiveauDropdown = false
      this.showTypeDropdown = false
    },
    toggleCategoryDropdown() {
      this.showCategoryDropdown = !this.showCategoryDropdown
      this.showCityDropdown = false
      this.showNiveauDropdown = false
      this.showTypeDropdown = false
    },
    toggleNiveauDropdown() {
      this.showNiveauDropdown = !this.showNiveauDropdown
      this.showCityDropdown = false
      this.showCategoryDropdown = false
      this.showTypeDropdown = false
    },
    toggleTypeDropdown() {
      this.showTypeDropdown = !this.showTypeDropdown
      this.showCityDropdown = false
      this.showCategoryDropdown = false
      this.showNiveauDropdown = false
    },
    closeDropdowns() {
      this.showCityDropdown = false
      this.showCategoryDropdown = false
      this.showNiveauDropdown = false
      this.showTypeDropdown = false
    },
    handleClickOutside(event) {
      const cityDropdown = this.$refs.cityDropdown
      const categoryDropdown = this.$refs.categoryDropdown
      const niveauDropdown = this.$refs.niveauDropdown
      const typeDropdown = this.$refs.typeDropdown
      if (cityDropdown && !cityDropdown.contains(event.target)) {
        this.showCityDropdown = false
      }
      if (categoryDropdown && !categoryDropdown.contains(event.target)) {
        this.showCategoryDropdown = false
      }
      if (niveauDropdown && !niveauDropdown.contains(event.target)) {
        this.showNiveauDropdown = false
      }
      if (typeDropdown && !typeDropdown.contains(event.target)) {
        this.showTypeDropdown = false
      }
    },
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside)
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
  },
  computed: {
    uniqueCategories() {
      return [...new Set(this.formations.map(formation => formation.type_etablissement))]
    },
    uniqueVilles() {
      return [...new Set(this.formations.map(formation => formation.ville))]
    },
    uniqueNiveaux() {
      return [...new Set(this.formations.map(formation => formation.niveau))]
    },
    uniqueTypes() {
      return [...new Set(this.formations.map(formation => formation.type_etablissement))]
    },
    filteredFormations() {
      return this.formations.filter(formation => {
        const matchesCategory = this.selectedCategories.length ? this.selectedCategories.includes(formation.type_etablissement) : true
        const matchesVille = this.selectedCities.length ? this.selectedCities.includes(formation.ville) : true
        const matchesNiveau = this.selectedNiveaux.length ? this.selectedNiveaux.includes(formation.niveau) : true
        const matchesType = this.selectedTypes.length ? this.selectedTypes.includes(formation.type_etablissement) : true
        return matchesCategory && matchesVille && matchesNiveau && matchesType
      })
    },
  },
})
</script>

<style scoped>
/* Add any necessary styles */
</style>
