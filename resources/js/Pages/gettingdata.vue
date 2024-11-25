<template>
  <div>
    <button @click="getHollandCodes" class="bg-blue-500 text-white px-4 py-2 rounded">
      Get Holland Codes
    </button>
    <div v-if="data.hollandCodes" class="mt-4">
      <h2>{{ data.hollandCodes.title }}</h2>
      <p>{{ data.hollandCodes.lead_in_text }}</p>
      <pre>{{ data.hollandCodes }}</pre>

      {{ hollandCodes }}
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const data = ref({})


defineProps({
  hollandCodes: Object
})

const getHollandCodes = () => {
  router.visit('/holland-codes', {
    method: 'get',
    preserveState: true,
    onSuccess: (page) => {
      data.value = page.props
      console.log('Holland Codes:', data.value)
    },
    onError: (errors) => {
      console.error(errors)
    }
  })
}
</script>
