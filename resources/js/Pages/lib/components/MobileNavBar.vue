<template>
  <div
    class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-xl shadow-lg z-50 rounded-b-2xl border-b border-white/20">
    <div class="flex items-center justify-between px-4 py-3">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl overflow-hidden bg-white/50 p-2 backdrop-blur-sm border border-white/20">
          <img :src="modelProps.image" :alt="modelProps.title" class="w-full h-full object-contain" />
        </div>
        <h3 class="text-base font-semibold text-gray-900 truncate">{{ modelProps.title }}</h3>
      </div>
      <FavoriteButton :model-id="modelId" :model-type="type" :initial-is-favorited="modelProps.isFavorited"
        :show-label="false" class="ml-2" @favorite-toggled="$emit('favorite-toggled', $event)" />
    </div>

    <div class="w-full overflow-x-auto scrollbar-hide">
      <div class="flex w-full px-2 py-2 gap-1">
        <NavLink v-for="link in links" :key="link.url" :link="link" :is-mobile="true" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import FavoriteButton from '@/Components/helpers/FavoriteButton.vue';
import NavLink from './NavLink.vue';

defineProps({
  modelProps: Object,
  modelId: Number,
  type: String,
  links: Array,
});

defineEmits(['favorite-toggled']);
</script>