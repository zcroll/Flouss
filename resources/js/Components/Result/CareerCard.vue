<template>
  <div class="HorizontalCard HorizontalCard--wide" :aria-labelledby="`HorizontalCard-${job.slug}`" @click="$emit('select', job)">
    <img class="HorizontalCard-image" :src="job.image" :alt="job.career" role="presentation" aria-hidden="true" />
    <div class="HorizontalCard-wrap">
      <div :id="`HorizontalCard-${job.slug}`" class="HorizontalCard-name">
        <Link :href="`/career/${job.slug}`" class="HorizontalCard-name-link" tabindex="0"
          aria-label="Click here to view your career details.">
          {{ job.name }}
        </Link>
      </div>
    </div>
    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right"
      class="svg-inline--fa fa-chevron-right fa-sm HorizontalCard-arrow" role="img" xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 320 512">
      <path fill="currentColor"
        d="M96 480c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L242.8 256L73.38 86.63c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25l-192 192C112.4 476.9 104.2 480 96 480z">
      </path>
    </svg>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  job: {
    type: Object,
    required: true
  }
});

defineEmits(['select']);
</script> 