<template>
  <Breadcrumb class="py-4">
    <BreadcrumbList>
      <template v-for="(breadcrumb, index) in breadcrumbs" :key="index">
        <BreadcrumbItem>
          <template v-if="index !== breadcrumbs.length - 1">
            <Link 
              :href="breadcrumb.url"
              class="group relative flex items-center px-1 py-1.5 rounded-lg transition-all duration-300"
              :class="[
                themeStore.isDarkMode 
                  ? `bg-${themeStore.currentTheme.primary}-900/30 hover:bg-${themeStore.currentTheme.primary}-800/40`
                  : `bg-${themeStore.currentTheme.primary}-50/50 hover:bg-${themeStore.currentTheme.primary}-100/60`
              ]"
            >
              <span class="relative z-10 font-medium" :class="[
                themeStore.isDarkMode
                  ? `text-${themeStore.currentTheme.hover}`
                  : `text-${themeStore.currentTheme.button}`
              ]">
                {{ __(breadcrumb.title) }}
              </span>
              <div class="absolute inset-0 rounded-lg backdrop-blur-sm"></div>
            </Link>
          </template>
          <template v-else>
            <BreadcrumbPage
              class="relative flex items-center px-1 py-1.5 rounded-lg transition-all duration-300"
              :class="[
                themeStore.isDarkMode 
                  ? `bg-${themeStore.currentTheme.primary}-900/30 ring-1 ring-${themeStore.currentTheme.button}`
                  : `bg-${themeStore.currentTheme.primary}-50/50 ring-1 ring-${themeStore.currentTheme.button}`
              ]"
            >
              <span class="relative z-10 font-medium" :class="[
                themeStore.isDarkMode
                  ? `text-${themeStore.currentTheme.hover}`
                  : `text-${themeStore.currentTheme.button}`
              ]">
                {{ __(breadcrumb.title) }}
              </span>
              <div class="absolute inset-0 rounded-lg backdrop-blur-sm"></div>
            </BreadcrumbPage>
          </template>
        </BreadcrumbItem>
        
        <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1">
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            class="h-4 w-4 flex-shrink-0 opacity-70" 
            :class="[
              themeStore.isDarkMode
                ? `text-${themeStore.currentTheme.hover}`
                : `text-${themeStore.currentTheme.button}`
            ]"
            viewBox="0 0 20 20" 
            fill="currentColor"
          >
            <path 
              fill-rule="evenodd" 
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" 
              clip-rule="evenodd" 
            />
          </svg>
        </BreadcrumbSeparator>
      </template>
    </BreadcrumbList>
  </Breadcrumb>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import { useThemeStore } from '@/stores/theme/themeStore'
import __ from '@/lang'

const themeStore = useThemeStore()

defineProps({
  breadcrumbs: {
    type: Array,
    required: true,
    // Each breadcrumb should have: { title: string, url: string }
  }
})
</script>