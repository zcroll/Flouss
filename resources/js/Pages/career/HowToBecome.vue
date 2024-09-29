<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Your Path to Becoming a {{ occupation.name }}
      </h2>
    </template>

    <StickySidebar 
            :slug="occupation.slug" 
            :title="occupation.name" 
            :image="occupation.image"
            type="career"
            :salary="occupation.salary"
            :personality="occupation.personality || 'N/A'"
            :satisfaction="occupation.satisfaction || 'N/A'"
        >   
           <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-3xl font-bold mb-6 text-center text-indigo-600">Your Recommended Educational Path</h3>
            <p class="text-lg text-gray-600 mb-8 text-center">Based on your test results, we've curated the best educational routes for you to pursue a career as a {{ occupation.name }}.</p>
            
            <div v-if="degreeJobs.length > 0" class="mb-12">
              <h4 class="text-2xl font-semibold text-indigo-700 mb-4">Recommended Degrees</h4>
              <div v-for="degreeJob in degreeJobs" :key="degreeJob.id" 
                   class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg shadow-md">
                <h5 class="text-xl font-medium text-indigo-600 mb-2">{{ degreeJob.degree.name }}</h5>
                <p class="text-gray-700 mb-2">{{ degreeJob.job_title }}</p>
                <p class="text-gray-600">{{ degreeJob.job_description }}</p>
              </div>
            </div>
          </div>
          </div>
</div>
          
    </StickySidebar>
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
    degreeJobs: Array,
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