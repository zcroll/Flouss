<template>
  <div class="DashboardPage__careers">
    <CareerCard v-for="job in displayedJobs" :key="job.slug" :job="job" @select="$emit('select-job', job)" />
    
    <div class="DashboardPage__careers__buttons">
      <button v-if="!showAllJobs && hasMoreJobs" 
        class="Button DashboardPage__button DashboardPage__button--careers"
        @click="$emit('toggle-show-more')">
        <span class="Button-icon">
          <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-down"
            class="svg-inline--fa fa-chevron-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <path fill="currentColor"
              d="M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z">
            </path>
          </svg>
        </span>
        {{ __("results.See_more_matches") }}
      </button>
    </div>
  </div>
</template>

<script setup>
import CareerCard from './CareerCard.vue';

const props = defineProps({
  displayedJobs: {
    type: Array,
    required: true
  },
  showAllJobs: {
    type: Boolean,
    default: false
  },
  hasMoreJobs: {
    type: Boolean,
    required: true
  }
});

defineEmits(['select-job', 'toggle-show-more']);
</script> 