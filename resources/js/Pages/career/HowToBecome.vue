<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Your Path to Becoming a {{ occupation.name }}
      </h2>
    </template>

    <sticky-sidebar :title="occupation.name" :image="occupation.image">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-3xl font-bold mb-6 text-center text-indigo-600">Your Recommended Educational Path</h3>
            <p class="text-lg text-gray-600 mb-8 text-center">Based on your test results, we've curated the best educational routes for you to pursue a career as a {{ occupation.name }}.</p>
            
            <div v-if="formations.length > 0">
              <div v-for="(formation, index) in sortedFormationsWithDescription" :key="formation.id" 
                   class="mb-8 p-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center mb-4">
                  <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4 font-bold">
                    {{ index + 1 }}
                  </div>
                  <h4 class="text-2xl font-semibold text-indigo-700">{{ formation.nom }}</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <p class="mb-2"><span class="font-medium text-indigo-600">Program Description:</span> {{ formation.descriptionFr }}</p>
                    <p class="mb-2"><span class="font-medium text-indigo-600">Diploma:</span> {{ formation.diplomeLibelleFr }}</p>
                    <p class="mb-2"><span class="font-medium text-indigo-600">Institution:</span> {{ formation.EtablissementFr || 'N/A' }}</p>
                  </div>
                  <div>
                    <p class="mb-2"><span class="font-medium text-indigo-600">Course Name:</span> {{ formation.parcoursNomFr }}</p>
                    <p class="mb-2"><span class="font-medium text-indigo-600">Course Code:</span> {{ formation.parcoursCode }}</p>
                    <p class="mb-2"><span class="font-medium text-indigo-600">Relevance Score:</span> 
                      <span class="text-green-600 font-bold">{{ (formation.similarity_score * 100).toFixed(2) }}%</span>
                    </p>
                  </div>
                </div>
                <div class="mt-4">
                  <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">Learn more about this program →</a>
                </div>
              </div>
            </div>
            <p v-else class="text-lg text-gray-600 text-center">We couldn't find specific educational programs for this occupation. Consider exploring related fields or consulting with a career advisor.</p>
          </div>
        </div>
      </div>
    </sticky-sidebar>
  </app-layout>
</template>

<script>
import { defineComponent, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'

export default defineComponent({
  components: {
    AppLayout,
    StickySidebar,
  },
  props: {
    occupation: Object,
    formations: Array,
  },
  setup(props) {
    const sortedFormationsWithDescription = computed(() => {
      return [...props.formations]
        .filter(formation => formation.descriptionFr)
        .sort((a, b) => b.similarity_score - a.similarity_score)
    })

    return {
      sortedFormationsWithDescription
    }
  }
})
</script>