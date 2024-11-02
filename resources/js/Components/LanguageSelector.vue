<template>
  <ol class="relative mb-0" @mouseleave="startCloseTimer">
    <label
      class="flex gap-2 items-center py-1.5 pr-3 cursor-pointer lowercase text-white "
      tabindex="0"
      @mouseenter="showDropdown = true"
    >
      <img class="h-5" :src="getFlagSrc(selectedLanguage)" :alt="`${selectedLanguage} flag`" />

    </label>
    <ul
      v-show="showDropdown"
      class="bg-white flex flex-col gap-2 p-2 rounded-xl shadow text-black absolute z-10 w-20 end-1"
      tabindex="0"
      @mouseenter="cancelCloseTimer"
      @mouseleave="startCloseTimer"
    >
      <li v-for="language in languages" :key="language.value">
        <a
          class="text-black duration-300 transition ease-in-out flex gap-1 flex-row items-center px-3 py-1"
          @click="switchLanguage(language.value)"
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
  return languageCode === 'en' ? 'En' : 'Fr';
};

const switchLanguage = (language) => {
  router.post(route('language.switch', { language }), {}, {
    onSuccess: () => {
      // Reload the page after successful language switch
      router.reload();
    }
  });
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
