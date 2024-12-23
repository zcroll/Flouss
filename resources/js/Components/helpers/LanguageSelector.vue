<template>
  <div :class="{'mobile': isMobile}">
    <!-- Desktop View -->
    <ol v-if="!isMobile" class="relative mb-0" @mouseleave="startCloseTimer">
      <label
        class="flex gap-2 items-center py-1.5 pr-3 cursor-pointer lowercase text-white"
        tabindex="0"
        @mouseenter="showDropdown = true"
      >
        <span class="flag-icon" v-html="getFlagSvg(selectedLanguage)"></span>
      </label>
      <ul
        v-show="showDropdown"
        class="bg-white flex flex-col gap-2 p-2 rounded-xl shadow text-black absolute z-10 w-fit end-1"
        tabindex="0"
        @mouseenter="cancelCloseTimer"
        @mouseleave="startCloseTimer"
      >
        <li v-for="language in languages" :key="language.value">
          <a
            class="text-black hover:bg-gray-100 rounded-lg duration-300 transition ease-in-out flex items-center px-2 py-1.5"
            @click="switchLanguage(language.value)"
            href="#"
            rel="nofollow"
          >
            <span class="flag-icon" v-html="getFlagSvg(language.value)"></span>
          </a>
        </li>
      </ul>
    </ol>

    <!-- Mobile View -->
    <div v-else class="flex flex-row gap-4 px-2 py-2">
      <button
        v-for="language in languages"
        :key="language.value"
        @click="switchLanguage(language.value)"
        class="flex items-center px-2 py-1.5 rounded-xl transition-colors"
        :class="selectedLanguage === language.value ? 'bg-gray-950 text-white' : ''"
      >
        <span class="flag-icon" v-html="getFlagSvg(language.value)"></span>
      </button>
    </div>
  </div>
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
  isMobile: {
    type: Boolean,
    default: false
  }
});

const showDropdown = ref(false);
let closeTimer = null;

const getFlagSvg = (languageCode) => {
  const flags = {
    en: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480">
      <path fill="#012169" d="M0 0h640v480H0z"/>
      <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0h75z"/>
      <path fill="#C8102E" d="m424 281 216 159v40L369 281h55zm-184 20 6 35L54 480H0l240-179zM640 0v3L391 191l2-44L590 0h50zM0 0l239 176h-60L0 42V0z"/>
      <path fill="#FFF" d="M241 0v480h160V0H241zM0 160v160h640V160H0z"/>
      <path fill="#C8102E" d="M0 193v96h640v-96H0zM273 0v480h96V0h-96z"/>
    </svg>`,
    fr: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480">
      <path fill="#fff" d="M0 0h640v480H0z"/>
      <path fill="#002654" d="M0 0h213.3v480H0z"/>
      <path fill="#CE1126" d="M426.7 0H640v480H426.7z"/>
    </svg>`
  };
  
  return flags[languageCode] || '';
};

const getLanguageName = (languageCode) => {
  return languageCode === 'en' ? '' : '';
};

const switchLanguage = (language) => {
  router.post(route('language.switch', { language }), {}, {
    onSuccess: () => {
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
.flag-icon {
  display: block;
  width: 24px;
  height: 18px;
  border-radius: 2px;
  overflow: hidden;
  flex-shrink: 0;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.flag-icon svg {
  width: 100%;
  height: 100%;
  display: block;
}

/* Mobile adjustments */
.mobile .flag-icon {
  width: 28px;
  height: 21px;
}
</style>
