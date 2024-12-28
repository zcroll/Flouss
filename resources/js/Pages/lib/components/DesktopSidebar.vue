<template>
  <div class="sticky top-8 bg-white/60 backdrop-blur-xl rounded-2xl border border-white/20 shadow-sm overflow-hidden">
    <div class="p-4 relative">
      <div class="flex items-center gap-3 pb-4 border-b border-gray-200/50">
        <div class="w-12 h-12 rounded-xl overflow-hidden bg-white/50 p-2 backdrop-blur-sm border border-white/20">
          <img :src="modelProps.image" :alt="modelProps.title" class="w-full h-full object-contain" />
        </div>
        <div class="flex-1 min-w-0">
          <h3 class="text-base font-medium text-gray-900 truncate">
            {{ modelProps.title }}
          </h3>
        </div>
        <FavoriteButton :model-id="modelId" :model-type="type" :initial-is-favorited="modelProps.isFavorited"
          :show-label="false" class="scale-90" @favorite-toggled="$emit('favorite-toggled', $event)" />
      </div>

      <nav class="mt-3">
        <div class="space-y-0.5">
          <NavLink v-for="link in links" :key="link.url" :link="link" :is-mobile="false" />
        </div>
      </nav>
    </div>

    <BackgroundDecoration />
  </div>
</template>

<script setup>
import FavoriteButton from '@/Components/helpers/FavoriteButton.vue';
import NavLink from './NavLink.vue';
import BackgroundDecoration from './BackgroundDecoration.vue';

defineProps({
  modelProps: Object,
  modelId: Number,
  type: String,
  links: Array,
});

defineEmits(['favorite-toggled']);
</script>