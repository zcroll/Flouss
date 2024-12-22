<script setup>
import { computed } from 'vue'

const props = defineProps({
  src: {
    type: String,
    required: true
  },
  alt: {
    type: String,
    default: ''
  },
  shape: {
    type: Number,
    default: 0
  },
  class: {
    type: String,
    default: ''
  },
  sizes: {
    type: String,
    default: ''
  }
})

const shapes = [
  {
    width: 655,
    height: 680,
    path: 'M537.827 9.245A11.5 11.5 0 0 1 549.104 0h63.366c7.257 0 12.7 6.64 11.277 13.755l-25.6 128A11.5 11.5 0 0 1 586.87 151h-28.275a15.999 15.999 0 0 0-15.689 12.862l-59.4 297c-1.98 9.901 5.592 19.138 15.689 19.138h17.275l.127.001c.85.009 1.701.074 2.549.009 11.329-.874 21.411-7.529 24.88-25.981.002-.012.016-.016.023-.007.008.009.022.005.024-.006l24.754-123.771A11.5 11.5 0 0 1 580.104 321h63.366c7.257 0 12.7 6.639 11.277 13.755l-25.6 128A11.5 11.5 0 0 1 617.87 472H559c-22.866 0-28.984 7.98-31.989 25.931-.004.026-.037.035-.052.014-.015-.02-.048-.013-.053.012l-24.759 123.798A11.5 11.5 0 0 1 490.87 631h-29.132a14.953 14.953 0 0 0-14.664 12.021c-4.3 21.502-23.18 36.979-45.107 36.979H83.502c-29.028 0-50.8-26.557-45.107-55.021l102.4-512C145.096 91.477 163.975 76 185.902 76h318.465c10.136 0 21.179-5.35 23.167-15.288l10.293-51.467Z'
  },
  // ... other shapes
]

const selectedShape = computed(() => shapes[props.shape])

const containerClass = computed(() => {
  return ['relative flex aspect-[719/680] w-full grayscale', props.class].filter(Boolean).join(' ')
})
</script>

<template>
  <div :class="containerClass">
    <svg :viewBox="`0 0 ${selectedShape.width} ${selectedShape.height}`" fill="none" class="h-full">
      <g :clip-path="`url(#clip-${shape})`" class="group">
        <g class="origin-center scale-100 transition duration-500 motion-safe:group-hover:scale-105">
          <foreignObject :width="selectedShape.width" :height="selectedShape.height">
            <img
              :src="src"
              :alt="alt"
              class="w-full bg-neutral-100 object-cover"
              :style="{ aspectRatio: `${selectedShape.width} / ${selectedShape.height}` }"
              :sizes="sizes"
            />
          </foreignObject>
        </g>
        <use
          :href="`#shape-${shape}`"
          stroke-width="2"
          class="stroke-neutral-950/10"
        />
      </g>
      <defs>
        <clipPath :id="`clip-${shape}`">
          <path
            :id="`shape-${shape}`"
            :d="selectedShape.path"
            fill-rule="evenodd"
            clip-rule="evenodd"
          />
        </clipPath>
      </defs>
    </svg>
  </div>
</template> 