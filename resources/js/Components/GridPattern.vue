<script setup>
import { computed } from 'vue'

const props = defineProps({
  width: {
    type: Number,
    default: 72
  },
  height: {
    type: Number,
    default: 56
  },
  x: {
    type: Number,
    default: 0
  },
  y: {
    type: Number,
    default: 0
  },
  squares: {
    type: Array,
    default: () => [
      [0, 1],
      [1, 0],
    ]
  },
  yOffset: {
    type: Number,
    default: 0
  },
  class: {
    type: String,
    default: ''
  }
})

const id = `grid-pattern-${Math.random().toString(36).substr(2, 9)}`

const patternWidth = computed(() => props.width * 2)
const patternHeight = computed(() => props.height * 2)

const patternTransform = computed(() => {
  return `translate(${props.x} ${props.y + props.yOffset})`
})
</script>

<template>
  <svg :class="props.class" aria-hidden="true">
    <defs>
      <pattern
        :id="id"
        :width="patternWidth"
        :height="patternHeight"
        patternUnits="userSpaceOnUse"
      >
        <g :transform="patternTransform">
          <rect
            v-for="[x, y] in squares"
            :key="`${x}-${y}`"
            :width="width"
            :height="height"
            :x="x * width"
            :y="y * height"
            class="fill-current"
          />
        </g>
      </pattern>
    </defs>
    <rect width="100%" height="100%" :fill="`url(#${id})`" />
  </svg>
</template> 