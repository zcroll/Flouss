<template>
  <div class="section-head">
    <div>
      <h2 class="text-[35px] text-gray-900" tabindex="0">
        {{ __('results.top_careers') }}
      </h2>
    </div>
    
    <span class="flex gap-2">
      <button class="Button DashboardPage__button DashboardPage__button--share">
        <span class="Button-icon">
          <svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="share-from-square"
            class="svg-inline--fa fa-share-from-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <g class="fa-duotone-group">
              <path class="fa-secondary" fill="currentColor"
                d="M416 384c-17.67 0-32 14.33-32 32v31.1l-320-.0013V128h32c17.67 0 32-14.32 32-32S113.7 64 96 64H64C28.65 64 0 92.65 0 128v319.1c0 35.34 28.65 64 64 64l320-.0013c35.35 0 64-28.66 64-64V416C448 398.3 433.7 384 416 384z">
              </path>
              <path class="fa-primary" fill="currentColor"
                d="M568.9 176.5l-150.9 138.2C404.8 326.8 384 316.1 384 298.2V223.1C256 224 186.7 252.2 227 380.4c4.473 14.22-12.8 25.24-24.94 16.46C163.2 368.8 128 314.9 128 260.6C128 126.1 241.2 97.63 384 96V21.84c0-18.8 20.81-28.61 34.02-16.52l150.9 138.2C578.4 152.2 578.4 167.8 568.9 176.5z">
              </path>
            </g>
          </svg>
        </span>
        <span v-if="!isMobile" class="DashboardPage__button__copy">{{ __('results.share') }}</span>
      </button>
      <Link v-if="!isMobile" href="/jobs/" class="Button DashboardPage__button DashboardPage__button--share">
        <span>{{ __('results.View_All_careers') }}</span>
      </Link>
    </span>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount } from 'vue';

let isMobile = ref(window.innerWidth <= 410);

onMounted(() => {
  window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth <= 410;
  });
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', () => {
    isMobile.value = window.innerWidth <= 410;
  });
});
</script>
