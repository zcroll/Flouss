<template>
  <Button variant="outline" size="icon"
    class="fixed bottom-6 right-6 z-50 opacity-0 transition-all duration-300 shadow-lg"
    :class="{ 'opacity-100 translate-y-0': showButton, 'translate-y-10': !showButton }" @click="scrollToTop">
    <Icon name="chevron-up" size="sm" />
    <span class="sr-only">Scroll to top</span>
  </Button>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { Button } from '@/Components/ui/button'
import { Icon } from "@/Components/ui/icon"

const showButton = ref<boolean>(false)
const scrollThreshold = 400

const checkScroll = (): void => {
  showButton.value = window.scrollY > scrollThreshold
}

const scrollToTop = (): void => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

onMounted(() => {
  window.addEventListener('scroll', checkScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', checkScroll)
})
</script>