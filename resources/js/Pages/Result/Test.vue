<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const itemSet = ref(null)

router.get('/personality-archetype', {}, {
  onSuccess: (response) => {
    itemSet.value = response.props.itemSet
  }
})
</script>

<template>
  <div v-if="itemSet">
    <div v-for="item in itemSet.items" :key="item.id">
      <h3>{{ item.text }}</h3>
      <div v-if="item.optionSet">
        <div v-for="option in item.optionSet.options" :key="option.id">
          {{ option.text }}
        </div>
      </div>
    </div>
  </div>
  <div v-else>
    Loading...
  </div>
</template>