<script setup>
import { StarFilledIcon, StarIcon } from '@radix-icons/vue'

import { ref, watch } from 'vue'
import __ from '@/lang'

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
  <div class="DashboardPage__sharing">
    <div class="DashboardPage__sharing__wrap">
      <div class="flex items-center justify-between mb-6">
        <h2 class="DashboardPage__sharing__title">
          {{ __('feedback.rate_experience') }}<br />{{ __('feedback.help_improve') }}
        </h2>

        <div class="flex items-center space-x-2">
          <button 
            v-for="star in 5" 
            :key="star" 
            @click="setRating(star)"
            class="focus:outline-none transition-colors"
            type="button"
          >
            <StarFilledIcon 
              v-if="star <= rating" 
              class="w-8 h-8 text-[#FFB900]" 
            />
            <StarIcon 
              v-else 
              class="w-8 h-8 text-gray-200 hover:text-gray-300" 
            />
          </button>
        </div>
      </div>

      <div v-if="showSuccessMessage" class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-md">
        {{ __('feedback.thank_you') }}
      </div>

      <div v-if="rating > 0" class="DashboardPage__sharing__preview">
        <textarea 
          :value="feedback"
          @input="handleInput"
          :placeholder="__('feedback.tell_us')"
          class="w-full px-4 py-3 text-sm border bg-stone-100/60 border-gray-200 rounded-lg focus:bg-stone-100/60 focus:ring-2 focus:ring-stone-800 "
          :class="{ 'border-red-500': errors.feedback }"
          rows="4"
        ></textarea>
        
        <p v-if="errors.feedback" class="mt-2 text-sm text-red-500">
          {{ errors.feedback }}
        </p>

        <button
          @click="submitFeedback"
          :disabled="!rating || processing"
          class="mt-4 w-full px-6 py-3 text-base font-medium text-white bg-stone-900 rounded-lg hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6B52F8] disabled:opacity-50"
        >
          {{ processing ? __('feedback.submitting') : __('feedback.submit') }}

        </button>
      </div>
    </div>
  </div>
</template>