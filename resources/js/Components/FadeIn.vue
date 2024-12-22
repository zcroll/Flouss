<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  delay: {
    type: Number,
    default: 0
  },
  countTo: {
    type: Number,
    default: null
  },
  prefix: {
    type: String,
    default: '+'
  },
  suffix: {
    type: String,
    default: ''
  },
  duration: {
    type: Number,
    default: 2000
  }
})

const isVisible = ref(false)
const target = ref(null)
const currentCount = ref(0)

const animateCounter = () => {
  if (props.countTo === null) return
  
  const start = 0
  const end = props.countTo
  const steps = 60
  const stepDuration = props.duration / steps
  let current = start
  
  const updateCount = () => {
    current += (end - start) / steps
    currentCount.value = Math.round(current)
    
    if (current < end) {
      setTimeout(updateCount, stepDuration)
    } else {
      currentCount.value = end
    }
  }
  
  updateCount()
}

onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          setTimeout(() => {
            isVisible.value = true
            if (props.countTo !== null) {
              animateCounter()
            }
          }, props.delay)
          observer.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.1, rootMargin: '0px 0px -200px 0px' }
  )

  if (target.value) {
    observer.observe(target.value)
  }
})
</script>

<template>
  <div ref="target" :class="{ 'opacity-0': !isVisible, 'opacity-100': isVisible }" 
       class="transform transition-all duration-500"
       :style="{ 
         transform: isVisible ? 'translateY(0)' : 'translateY(24px)',
       }">
    <template v-if="countTo !== null">
      {{ prefix }}{{ currentCount }}{{ suffix }}
    </template>
    <slot v-else />
  </div>
</template> 