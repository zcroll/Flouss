<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  steps: Array,
  currentStep: Number,
  totalTime: Number,
})

const formatTime = (minutes) => {
  const hours = Math.floor(minutes / 60)
  const mins = minutes % 60
  return hours > 0 ? `${hours}h ${mins}m` : `${mins}m`
}

const getStatusClass = (status) => {
  return {
    'bg-gray-200': status === 'notstarted',
    'bg-blue-500': status === 'inprogress',
    'bg-green-500': status === 'completed'
  }
}
</script>

<template>
  <div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Career Assessment Steps</h1>
    
    <div class="space-y-4">
      <div v-for="step in steps" :key="step.id" 
           class="border rounded-lg p-4 transition-all"
           :class="{'border-blue-500': step.id === currentStep}">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-lg font-semibold">{{ step.name }}</h2>
            <div v-if="step.description" v-html="step.description" class="text-gray-600 text-sm mt-1"></div>
          </div>
          <div class="text-sm text-gray-500">
            {{ formatTime(step.time) }}
          </div>
        </div>
        
        <div class="mt-4">
          <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="h-2.5 rounded-full transition-all duration-500"
                 :class="getStatusClass(step.status)"
                 :style="{ width: `${step.progress}%` }"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-6 text-right text-gray-600">
      Total estimated time: {{ formatTime(totalTime) }}
    </div>
  </div>
</template>
