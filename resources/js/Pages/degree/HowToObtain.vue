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
      type="degree"
    >
    <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl">
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
          <div class="p-6">
            <!-- Section Title -->
            <h2 class="custom-heading mb-10">
              Recommended Formations
            </h2>

            <!-- Filter Section -->


            <!-- Section Description -->
            <aside class="block">
              <div id="table-of-contents" class="table-of-contents rounded-lg my-4 w-full bg-[#0a1e2e] text-gray-900 relative p-7">
                <div class="mb-4">
              <label for="categoryEtablissement" class="block mb-2 text-sm font-medium text-gray-200">Category Etablissement</label>
              <select v-model="selectedCategory" id="categoryEtablissement" class="w-full px-3 py-1.5 text-gray-400 text-sm border bg-gray-800 rounded-md  focus:ring-[#fb6302] focus:border-[#fb6302] shadow-sm ">
                <option value="">All Categories</option>
                <option v-for="category in uniqueCategories" :key="category" :value="category">{{ category }}</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="ville" class="block mb-2 text-sm font-medium text-gray-200">Ville</label>
              <select v-model="selectedVille" id="ville" class="w-full px-3 py-1.5 text-gray-400 text-sm border bg-gray-800 rounded-md focus:ring-[#fb6302] focus:border-[#fb6302] shadow-sm ">
                <option value="">All Villes</option>
                <option v-for="ville in uniqueVilles" :key="ville" :value="ville">{{ ville }}</option>
              </select>
            </div>
              </div>
            </aside>

            <!-- Formations List -->
            <div v-if="filteredFormations.length > 0" class="space-y-6">
              <div v-for="formation in filteredFormations" :key="formation.id" class="flex-1 border-b border-gray-200 py-5 p-5 rounded-3xl items-center space-x-4 text-lg m-5 text-gray-700 leading-relaxed">
                <!-- Formation Title -->
                 <div>
                  <h5 class="text-xl font-semibold text-slate-600">
                  {{ formation.name }}
                </h5>
                <h6 class="text-sm font-small text-slate-500 mb-1">
                      {{ formation.libelle }} <!-- Display etablissement name -->
                  </h6>

                  <div>
                <h5 class="text-lg font-medium text-slate-500 mb-2">
                  {{ formation.etablissement.nom }} <!-- Display etablissement name -->
                </h5>

                <!-- Similarity Score -->
                <div class="flex">
                  <template v-for="star in 5" :key="star">
                    <svg
                      :class="star <= Math.ceil(formation.similarity_score * 5) ? 'text-yellow-400' : 'text-gray-300'"
                      class="h-5 w-5 flex-shrink-0"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  </template>
                </div>
                  </div>
                 </div>

                <!-- Etablissement Name -->

              </div>
            </div>
          </div>
        </div>
      </div>
    </StickySidebar>
  </AppLayout>
</template>

<script>
import { defineComponent, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'

export default defineComponent({
  components: {
    AppLayout,
    StickySidebar,
    Link,
  },
  props: {
    degree: Object,
    formations: Array,
  },
  data() {
    return {
      selectedCategory: '',
      selectedVille: '',
    };
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
        const matchesCategory = this.selectedCategory ? formation.etablissement.categoryEtablissement === this.selectedCategory : true;
        const matchesVille = this.selectedVille ? formation.etablissement.ville === this.selectedVille : true;
        return matchesCategory && matchesVille;
      });
    },
  },
})
</script>

<style scoped>
/* Add any necessary styles */
</style>
