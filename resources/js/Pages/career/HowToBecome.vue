<template>
  <Head title="How to Become" />
  <StickySidebar
    :slug="occupation.slug"
    :title="occupation.name"
    :image="occupation.image"
    type="career"
    :salary="occupation.salary"
    :personality="occupation.personality || 'N/A'"
    :satisfaction="occupation.satisfaction || 'N/A'"
    :id="occupation.id"
    :isFavorited="occupation.is_favorited"
  >
    <template #description>
      Learn the steps and requirements to become a {{ occupation.name }} and start your career journey.
    </template>

    <!-- Main Content -->
    <div class="space-y-8">
      <Breadcrumbs
        :items="[
          { name: 'Home', route: 'dashboard' },
          { name: 'Jobs', route: 'jobs.index' },
          { name: occupation.name, route: 'career', params: { id: occupation.id } },
          { name: 'How to Become' }
        ]"
        class="mb-8"
      />

      <!-- Steps Section -->
      <section class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">
          {{__('career.steps')}}{{ occupation.name }}
        </h2>
        
        <div v-if="howToBecome.steps?.length" class="space-y-4">
          <div v-for="(step, index) in howToBecome.steps" 
               :key="index"
               class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white font-semibold">
                {{ index + 1 }}
              </div>
              <p class="text-gray-600">{{ step.title }}</p>
            </div>
          </div>
        </div>
        <div v-else class="text-gray-600">
          {{ __('career.no_steps') }}
        </div>
      </section>

      <!-- Degrees Section -->
      <section class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">
          {{ __('career.degrees') }}
        </h2>

        <div v-if="jobDegrees?.length" class="grid gap-4">
          <Link v-for="degree in jobDegrees" 
                :key="degree.id"
                v-if="degree.slug"
                :href="route('degree.index', { slug: degree.slug })"
                class="group bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-4 shadow-sm hover:bg-white/70 transition-all duration-300">
            <div class="flex items-center gap-4">
              <div class="w-16 h-16 rounded-xl overflow-hidden bg-white/50 p-2 backdrop-blur-sm border border-white/20">
                <img :src="degree.image" :alt="degree.title" class="w-full h-full object-contain"/>
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ degree.title }}</h3>
              </div>
              <div class="text-gray-400 group-hover:text-yellow-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          </Link>
        </div>
        <div v-else class="text-gray-600">
          {{ __('career.no_degrees') }}
        </div>
      </section>

      <!-- Associations Section -->
      <section v-if="howToBecome.associations?.length" class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">
          {{ __('career.associations') }}
        </h2>

        <div class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm">
          <ul class="space-y-3">
            <li v-for="(association, index) in howToBecome.associations" 
                :key="index"
                class="flex items-start gap-3 text-gray-600">
              <div class="mt-1.5 h-2 w-2 rounded-full bg-yellow-400 flex-shrink-0"></div>
              <span>{{ association.title }}</span>
            </li>
          </ul>
        </div>
      </section>
    </div>

    <BackToTop />
  </StickySidebar>
</template>

<script setup>
import { defineComponent } from 'vue';
import StickySidebar from '@/Pages/lib/StickySidebar.vue';
import { Link } from '@inertiajs/vue3';
import BackToTop from "@/Components/helpers/BackToTop.vue";
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue';
import MainLayout from "@/Layouts/MainLayout.vue";

defineOptions({ layout: MainLayout });

defineProps({
  occupation: {
    type: Object,
    required: true
  },
  howToBecome: {
    type: Object,
    required: true
  },
  jobDegrees: {
    type: Array,
    required: true
  },
  disableStepsLink: {
    type: Boolean,
    required: true
  }
});
</script>

<style scoped>
section {
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
