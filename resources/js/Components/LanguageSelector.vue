<template>
  <ol class="relative" @mouseleave="startCloseTimer">
    <label
      class="flex gap-2 items-center py-1.5 cursor-pointer lowercase text-white"
      tabindex="0"
      @mouseenter="showDropdown = true"
    >
      <img class="h-5" :src="getFlagSrc(selectedLanguage)" :alt="`${selectedLanguage} flag`" />
      <svg height="16" width="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M7.41 8.58L12 13.17l4.59-4.59L18 10l-6 6l-6-6l1.41-1.42Z" fill="currentColor" />
      </svg>
    </label>
    <ul
      v-show="showDropdown"
      class="bg-white flex flex-col gap-2 p-2 rounded-box shadow text-black absolute z-10 w-28 end-1"
      tabindex="0"
      @mouseenter="cancelCloseTimer"
      @mouseleave="startCloseTimer"
    >
      <li v-for="language in languages" :key="language.value">
        <a
          class="hover:text-primary duration-300 transition ease-in-out flex gap-1 flex-row items-center px-3 py-1"
          @click.prevent="switchLanguage(language.value)"
          href="#"
          rel="nofollow"
        >
          <img class="h-4" :src="getFlagSrc(language.value)" :alt="`${language.value} flag`" />
          <span>{{ getLanguageName(language.value) }}</span>
        </a>
      </li>
    </ul>
  </ol>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  languages: {
    type: Array,
    required: true,
  },
  selectedLanguage: {
    type: String,
    required: true,
  },
});

const showDropdown = ref(false);
let closeTimer = null;

const getFlagSrc = (languageCode) => {
  return `https://www.markoub.ma/Content/front/new/img/country-flags/${languageCode}.svg`;
};

const getLanguageName = (languageCode) => {
  return languageCode === 'en' ? 'English' : 'Français';
};

const switchLanguage = (language) => {
  router.post(route('language.switch', { language }));
};

const startCloseTimer = () => {
  closeTimer = setTimeout(() => {
    showDropdown.value = false;
  }, 300);
};

const cancelCloseTimer = () => {
  if (closeTimer) {
    clearTimeout(closeTimer);
  }
};
</script>

<style scoped>
/* You can add scoped styles here if needed */
</style>