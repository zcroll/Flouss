<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  feedback: {
    type: String,
    required: true
  },
  rating: {
    type: Number, 
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  },
  processing: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:rating', 'update:feedback', 'submit'])

const setRating = (value) => {
  emit('update:rating', value)
}

const handleInput = (event) => {
  emit('update:feedback', event.target.value)
}

const submitFeedback = () => {
  emit('submit')
}

const showSuccessMessage = ref(false)
watch(() => props.processing, (newVal, oldVal) => {
  if (oldVal && !newVal) {
    showSuccessMessage.value = true
    setTimeout(() => {
      showSuccessMessage.value = false
    }, 3000)
  }
})
</script>

<template>
  <div class="DashboardPage__sharing flex items-center justify-center">
    <div class="DashboardPage__sharing__wrap text-center max-w-2xl w-full">
      <h2 class="DashboardPage__sharing__title text-center text-3xl font-bold mb-6">
        Your Feedback<br />Help us improve
      </h2>

      <div v-if="showSuccessMessage" class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-center mx-auto shadow-md">
        Thank you for your feedback!
      </div>

      <div class="flex items-center justify-center space-x-2 mb-8">
        <button v-for="star in 5" :key="star" @click="setRating(star)" 
          class="focus:outline-none transform hover:scale-110 transition-transform duration-200" 
          type="button">
          <svg v-if="star <= rating" class="w-10 h-10 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
          <svg v-else class="w-10 h-10 text-gray-300 hover:text-yellow-200" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
        </button>
      </div>

      <div v-if="rating > 0" class="DashboardPage__sharing__preview flex flex-col items-center">
        <textarea 
          :value="feedback" 
          @input="handleInput" 
          placeholder="Tell us about your experience..."
          class="w-full p-4 min-h-[150px] text-center rounded-lg border-2 border-blue-200 
                 focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50
                 transition duration-200 ease-in-out resize-none
                 bg-white/80 backdrop-blur-sm shadow-inner"
          :class="{ 'border-red-500': errors.feedback }"
        ></textarea>
        <p v-if="errors.feedback" class="mt-2 text-sm text-red-500 text-center">
          {{ errors.feedback }}
        </p>
        <button 
          @click="submitFeedback" 
          :disabled="!rating || processing"
          class="mt-6 px-8 py-3 text-lg font-semibold text-white 
                 bg-gradient-to-r from-blue-500 to-indigo-600
                 rounded-full shadow-lg hover:shadow-xl
                 transform hover:-translate-y-0.5 transition-all duration-200
                 disabled:opacity-50 disabled:cursor-not-allowed
                 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          {{ processing ? 'Submitting...' : 'Submit Feedback' }}
        </button>
      </div>
    </div>
  </div>
</template>