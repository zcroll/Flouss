<script setup>
import { Button } from '@/Components/ui/button'

const props = defineProps({
  className: {
    type: String,
    default: ''
  },
  type: {
    type: String,
    default: 'text'
  },
  placeholder: {
    type: String,
    default: 'Search'
  }
})

const emit = defineEmits(['setOpen'])

const handleClick = () => {
  emit('setOpen', true)
}
</script>

<template>
  <Button
    variant="outline"
    :class="[
      'relative h-8 w-full flex-1 md:flex-none justify-start rounded-md bg-muted/25 hover:bg-muted/50 text-sm font-normal text-muted-foreground shadow-none sm:pr-12 md:w-40 lg:w-56 xl:w-64',
      className
    ]"
    @click="handleClick"
  >
    <!-- <img
      src="/Icons/light/search.svg"
      class="absolute left-1.5 top-1/2 -translate-y-1/2 h-4 w-4"
      alt="Search"
    /> -->
    <span class="ml-3">{{ placeholder }}</span>
    <kbd class="pointer-events-none absolute right-[0.3rem] top-[0.3rem] hidden h-5 select-none items-center gap-1 rounded border bg-muted px-1.5 font-mono text-[10px] font-medium opacity-100 sm:flex">
      <span class="text-xs">⌘</span>K
    </kbd>
  </Button>
</template>
