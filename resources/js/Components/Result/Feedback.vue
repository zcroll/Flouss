<script setup>
import { Button } from '@/components/ui/button'
import { Textarea } from '@/components/ui/textarea'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { StarFilledIcon, StarIcon } from '@radix-icons/vue'
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
  <Card class="w-full max-w-lg mx-auto">
    <CardHeader>
      <CardTitle>Your Feedback</CardTitle>
      <CardDescription>
        Please rate your experience and share your thoughts with us
      </CardDescription>
    </CardHeader>
    <CardContent>
      <div v-if="showSuccessMessage" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
        Thank you for your feedback!
      </div>

      <div class="flex items-center justify-center space-x-1 mb-6">
        <button v-for="star in 5" :key="star" @click="setRating(star)" class="focus:outline-none" type="button">
          <StarFilledIcon v-if="star <= rating" class="w-8 h-8 text-yellow-400" />
          <StarIcon v-else class="w-8 h-8 text-gray-300" />
        </button>
      </div>

      <div v-if="rating > 0">
        <textarea :value="feedback" @input="handleInput" placeholder="Tell us about your experience..."
          class="w-full min-h-[100px] p-2 border rounded-md" :class="{ 'border-red-500': errors.feedback }"></textarea>
        <p v-if="errors.feedback" class="mt-1 text-sm text-red-500">
          {{ errors.feedback }}
        </p>
      </div>
    </CardContent>
    <CardFooter>
      <Button @click="submitFeedback" :disabled="!rating || processing" class="w-full">
        {{ processing ? 'Submitting...' : 'Submit Feedback' }}
      </Button>
    </CardFooter>
  </Card>
</template>