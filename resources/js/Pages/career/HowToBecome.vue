<template>

  <Head title="How to Become" />
  <StickySidebar type="career" :model="occupation">
    <template #description>
      Learn how to become a {{ occupation.name }} and explore the education, certifications and skills needed for this
      career path.
    </template>

    <!-- Main Content -->
    <div class="space-y-8">
      <!-- Breadcrumbs -->
      <Breadcrumbs :items="[
        { name: 'Home', route: 'dashboard' },
        { name: 'Jobs', route: 'jobs.index' },
        { name: occupation.name, route: 'career', params: { id: occupation.id } },
        { name: 'How to Become' }
      ]" class="mb-8" />

      <Deferred data="howToBecome">
        <template #fallback>
          <div class="animate-pulse space-y-8">
            <div class="h-10 bg-gray-200 rounded w-1/2"></div>
            <div class="space-y-6">
              <div v-for="n in 4" :key="n" class="flex space-x-4">
                <div class="h-4 w-4 mt-2 bg-gray-200 rounded-full"></div>
                <div class="flex-1">
                  <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                  <div class="mt-2 h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <section id="steps" class="space-y-6">
          <h2 class="text-2xl font-bold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
            Steps to become a {{ occupation.name }}
          </h2>
          <div v-if="howToBecome.steps?.length" class="space-y-4">
            <ul class="list-custom">
              <li v-for="(step, index) in howToBecome.steps" :key="index" class="text-lg py-1"
                :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                {{ step.title }}
              </li>
            </ul>
          </div>
          <div v-else class="text-lg" :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-700'">
            No steps available for this occupation.
          </div>
        </section>
      </Deferred>

      <Deferred data="jobDegrees">
        <template #fallback>
          <div class="animate-pulse space-y-8">
            <div class="h-10 bg-gray-200 rounded w-1/3"></div>
            <div class="space-y-6">
              <div v-for="n in 3" :key="n" class="flex items-center space-x-4 p-4 border border-gray-200 rounded-3xl">
                <div class="rounded-full bg-gray-200 h-12 w-12"></div>
                <div class="flex-1">
                  <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                  <div class="mt-2 h-3 bg-gray-200 rounded w-1/4"></div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <section id="degrees" class="space-y-6">
          <h2 class="text-2xl font-bold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
            Required Degrees
          </h2>
          <div v-if="jobDegrees?.length" class="space-y-4">
            <div class="divide-y" :class="themeStore.isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
              <div v-for="(degree, index) in jobDegrees" :key="index"
                class="flex items-center space-x-4 p-5 rounded-3xl"
                :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                <img v-if="degree.image" :src="degree.image" :alt="degree.title"
                  class="w-12 h-12 object-cover rounded-full">
                <div>
                  <Link v-if="degree.slug" :href="route('degree.index', { slug: degree.slug })"
                    :class="[themeClasses.link]">
                  {{ degree.title }}
                  </Link>
                  <span v-else>{{ degree.title }}</span>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-lg" :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-700'">
            No degrees available for this occupation.
          </div>
        </section>
      </Deferred>

      <Deferred :data="['howToBecome']">
        <template #fallback>
          <div class="animate-pulse space-y-8">
            <div class="h-10 bg-gray-200 rounded w-1/3"></div>
            <div class="space-y-6">
              <div v-for="n in 3" :key="n" class="flex space-x-4">
                <div class="h-4 w-4 mt-2 bg-gray-200 rounded-full"></div>
                <div class="flex-1">
                  <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <section id="certifications" class="space-y-6">
          <h2 class="text-2xl font-bold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
            Required Certifications
          </h2>
          <div v-if="howToBecome.certifications?.length" class="space-y-4">
            <ul class="list-custom">
              <li v-for="(cert, index) in howToBecome.certifications" :key="index" class="text-lg py-1"
                :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                {{ cert.title }}
              </li>
            </ul>
          </div>
          <div v-else class="text-lg" :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-700'">
            No certifications available for this occupation.
          </div>
        </section>

        <section v-if="howToBecome.associations?.length" id="associations" class="space-y-6">
          <h2 class="text-2xl font-bold" :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'">
            Professional Associations
          </h2>
          <ul class="list-custom">
            <li v-for="(assoc, index) in howToBecome.associations" :key="index" class="text-lg py-1"
              :class="themeStore.isDarkMode ? 'text-gray-300' : 'text-gray-700'">
              {{ assoc.title }}
            </li>
          </ul>
        </section>
      </Deferred>

      <BackToTop />
    </div>
  </StickySidebar>
</template>

<script setup>
import { defineProps } from 'vue'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link, Deferred } from '@inertiajs/vue3'
import BackToTop from "@/Components/helpers/BackToTop.vue"
import Breadcrumbs from "@/Components/helpers/Breadcrumbs.vue"
import { useThemeStore } from '@/stores/theme/themeStore'

defineOptions({ layout: MainLayout })

const themeStore = useThemeStore()

const themeClasses = {
  link: `text-${themeStore.currentTheme.primary}-600 hover:text-${themeStore.currentTheme.primary}-700`
}

const props = defineProps({
  occupation: {
    type: Object,
    required: true,
    validator: (obj) => {
      return ['id', 'slug', 'name', 'image'].every(prop => prop in obj);
    }
  },
  howToBecome: {
    type: Object,
    required: true,
  },
  jobDegrees: Array,
  disableStepsLink: Boolean,
})
</script>

<style scoped>
ul.list-custom {
  list-style: none;
  padding-left: 1.5rem;
}

ul.list-custom li {
  position: relative;
  padding-left: 2rem;
}

ul.list-custom li::before {
  content: '•';
  position: absolute;
  left: 0;
  top: -10px;
  font-size: 2.1rem;
  color: v-bind('`var(--color-${themeStore.currentTheme.primary}-500)`');
}
</style>