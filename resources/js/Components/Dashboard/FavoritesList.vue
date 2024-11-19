<template>
  <div class="bg-stone-950 rounded-2xl p-6 border border-stone-700">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-semibold text-stone-100">{{ title }}</h2>
      <span class="px-4 py-2 bg-stone-700 rounded-full text-sm text-stone-300">
        {{ items.length }}
      </span>
    </div>
    <div v-if="items.length > 0"
      class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[200px] overflow-y-auto custom-scrollbar">
      <div v-for="item in items" :key="item.id"
        class="group bg-stone-700/50 hover:bg-stone-700 rounded-xl p-4 transition-all duration-300">
        <Link :href="generateItemLink(item)" class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-medium text-stone-100">{{ item.name }}</h3>
            <p class="text-sm text-stone-400">{{ type }} Path</p>
          </div>
          <button @click.prevent="$emit('remove', item.id)"
            class="opacity-0 group-hover:opacity-100 p-2 hover:bg-stone-600 rounded-full transition-all">
            <svg class="w-5 h-5 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </Link>
      </div>
    </div>
    <div v-else class="flex flex-col items-center justify-center py-8 text-center">
      <slot name="empty-state">
        <svg class="w-16 h-16 text-stone-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <p class="text-stone-400 mb-2">{{ emptyMessage }}</p>
        <Link :href="browseLink" class="text-amber-500 hover:text-amber-400 text-sm">
          {{ browseMessage }} →
        </Link>
      </slot>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  items: {
    type: Array,
    default: () => []
  },
  type: {
    type: String,
    required: true
  },
  emptyMessage: {
    type: String,
    required: true
  },
  browseMessage: {
    type: String,
    required: true
  },
  browseLink: {
    type: String,
    required: true
  }
});

const generateItemLink = (item) => {
  return `/${props.type.toLowerCase()}/${item.slug}`;
};

defineEmits(['remove']);
</script>

<style scoped>
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: theme('colors.stone.700') theme('colors.stone.900');
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: theme('colors.stone.900');
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: theme('colors.stone.700');
  border-radius: 3px;
}
</style> 