<template>
  <nav v-if="links.length > 3" class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
    <div class="-mt-px flex w-0 flex-1">
      <a 
        href="#" 
        @click.prevent="changePage(prevUrl)" 
        :class="[
          'inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium',
          prevUrl ? 'text-gray-500 hover:border-gray-300 hover:text-gray-700' : 'text-gray-300 cursor-not-allowed'
        ]"
      >
        <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
        Précédent
      </a>
    </div>
    <div class="hidden md:-mt-px md:flex">
      <template v-for="(link, key) in paginationLinks" :key="key">
        <a 
          v-if="link.url" 
          href="#"
          @click.prevent="changePage(link.url)"
          :class="[
            'inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium',
            link.active 
              ? 'border-[#fb6303] text-[#fb6303]' 
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
          :aria-current="link.active ? 'page' : undefined"
          v-html="link.label"
        />
        <span 
          v-else
          class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500"
          v-html="link.label"
        />
      </template>
    </div>
    <div class="-mt-px flex w-0 flex-1 justify-end">
      <a 
        href="#" 
        @click.prevent="changePage(nextUrl)"
        :class="[
          'inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium',
          nextUrl ? 'text-gray-500 hover:border-gray-300 hover:text-gray-700' : 'text-gray-300 cursor-not-allowed'
        ]"
      >
        Suivant
        <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
      </a>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
  links: {
    type: Array,
    required: true
  },
  meta: {
    type: Object,
    required: false,
    default: () => ({})
  }
});

const paginationLinks = computed(() => props.links.slice(1, -1));

const prevUrl = computed(() => {
  const prevLink = props.links.find(link => link.label === '&laquo; Précédent');
  return prevLink && !prevLink.active ? prevLink.url : null;
});

const nextUrl = computed(() => {
  const nextLink = props.links.find(link => link.label === 'Suivant &raquo;');
  return nextLink && !nextLink.active ? nextLink.url : null;
});

const changePage = (url) => {
  if (url) {
    router.visit(url, {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    });
  }
};
</script>