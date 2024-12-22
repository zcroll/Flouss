<script setup>
import { computed } from 'vue'

const props = defineProps({
  invert: {
    type: Boolean,
    default: false
  },
  class: {
    type: String,
    default: ''
  }
})

const offices = [
  {
    city: 'Copenhagen',
    address: ['Bredgade 32', '1260 København K', 'Denmark'],
  },
  {
    city: 'Billund',
    address: ['Systemvej 7', '7190 Billund', 'Denmark'],
  },
]

const addressClass = computed(() => {
  return props.invert ? 'text-neutral-300' : 'text-neutral-600'
})

const cityClass = computed(() => {
  return props.invert ? 'text-white' : 'text-neutral-950'
})
</script>

<template>
  <ul :class="['text-base', props.class]" role="list">
    <li v-for="office in offices" :key="office.city">
      <p :class="['font-semibold', cityClass]">{{ office.city }}</p>
      <p :class="addressClass">
        <span v-for="line in office.address" :key="line">
          {{ line }}<br>
        </span>
      </p>
    </li>
  </ul>
</template> 