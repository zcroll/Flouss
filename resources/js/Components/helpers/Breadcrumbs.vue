<template>
  <nav class="flex items-center space-x-2 text-sm mb-8 font-['aktiv-grotesk','Helvetica_Neue',Helvetica,Arial,sans-serif] pt-5">
    <template v-for="(item, index) in items" :key="index">
      <template v-if="index !== items.length - 1">
        <Link 
          v-if="item.route" 
          :href="route(item.route, item.params)" 
          class="text-[#53777a] font-medium border-b-2 border-[#53777a] transition-all duration-200 ease-in-out hover:text-violet-900 hover:border-violet-900 max-w-[120px] sm:max-w-none truncate"
        >
          {{ __(item.name) }}
        </Link>
        <svg 
          xmlns="http://www.w3.org/2000/svg" 
          class="h-3 w-3 sm:h-4 sm:w-4 flex-shrink-0" 
          viewBox="0 0 20 20" 
          fill="currentColor"
        >
          <path 
            fill-rule="evenodd" 
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" 
            clip-rule="evenodd" 
          />
        </svg>
      </template>
      <span 
        v-else 
        class="text-gray-400 font-medium border-b-2 border-gray-400 transition-all duration-200 ease-in-out hover:text-violet-900 hover:border-violet-900 max-w-[120px] sm:max-w-none truncate"
      >
        {{ __(item.name) }}
      </span>
    </template>
  </nav>
</template>

<script>
import { defineComponent } from 'vue'
import { Link } from '@inertiajs/vue3'

export default defineComponent({
  name: 'Breadcrumbs',
  components: {
    Link
  },
  props: {
    items: {
      type: Array,
      required: true,
      // Each item should have: { name: string, route?: string, params?: Object }
    }
  }
})
</script>