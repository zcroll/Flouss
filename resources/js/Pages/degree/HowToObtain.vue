<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        How to Obtain a {{ degree.name }} Degree
      </h2>
    </template>

    <StickySidebar 
      :slug="degree.slug" 
      :title="degree.name" 
      :image="degree.image"
      type="degree"
    >   
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-3xl font-bold mb-6 text-center text-indigo-600">Recommended Formations</h3>
            <p class="text-lg text-gray-600 mb-8 text-center">Based on the similarity analysis, here are the recommended formations to obtain a {{ degree.name }} degree.</p>
            
            <div v-if="formations.length > 0" class="mb-12">
              <div v-for="formation in formations" :key="formation.id" 
                   class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg shadow-md">
                <h5 class="text-xl font-medium text-indigo-600 mb-2">{{ formation.name }}</h5>
                <p class="text-gray-700">Similarity Score: {{ formation.similarity_score }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </StickySidebar>
  </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'

export default defineComponent({
  components: {
    AppLayout,
    StickySidebar,
  },
  props: {
    degree: Object,
    formations: Array,
  },
})
</script>