<template>
  <select 
    name="language" 
    id="language" 
    v-on:change="router.post(route('language.switch', { language: $event.target.value }))"
    class="bg-transparent text-white border border-slate-500 focus:border-green-400 focus:ring-2 focus:ring-green-400 rounded-md px-2 py-1 text-sm mr-4"
  >
    <option 
      :value="language.value" 
      v-for="language in languages"
      :key="language.value" 
      :selected="language.value === selectedLanguage"
      class="bg-[#0a1e2e] text-white"
    >
      {{ language.value === 'en' ? 'En' : 'Fr' }}
    </option>
  </select>
</template>

<script>
import { router } from '@inertiajs/vue3';

export default {
  props: {
    languages: {
      type: Array,
      required: true,
    },
    selectedLanguage: {
      type: String,
      required: true,
    },
  },
  setup() {
    return {
      router,
    };
  },
};
</script>