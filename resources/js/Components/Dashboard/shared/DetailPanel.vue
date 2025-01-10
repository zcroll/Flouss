<template>
  <div class="md:w-[340px] w-full relative overflow-hidden" 
       :class="{ 'pointer-events-none': !showDetails }">
    <div class="space-y-6 md:space-y-8 transition-all duration-500 ease-out" 
         :class="[
           showDetails ? 'opacity-100 md:translate-x-0' : 'opacity-0 md:translate-x-20',
           'transform'
         ]">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  showDetails: {
    type: Boolean,
    required: true
  }
})
</script> 