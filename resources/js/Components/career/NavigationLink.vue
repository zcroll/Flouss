<script setup>
defineProps({
  href: {
    type: String,
    required: true
  },
  theme: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <Button variant="ghost" asChild
    :class="`navigation-link hover:bg-${theme.primary}-100 dark:hover:bg-${theme.primary}-800`">
    <a :href="href">
      <slot />
    </a>
  </Button>
</template>

<style scoped>
.navigation-link {
  @apply w-full justify-start;
}
</style>