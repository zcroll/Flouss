<template>
  <AppLayout>
    <div class="min-h-screen">
      <!-- Hero Section -->
      <div class="relative py-16 bg-white">
        <div class="container mx-auto px-4">
          <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Content -->
            <div class="lg:w-2/3">
              <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                <Link :href="route('formations.index')" class="hover:text-[#db492b]">
                  {{ __('formations.all_formations') }}
                </Link>
                <span>/</span>
                <span>{{ formation.discipline }}</span>
              </div>

              <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ formation.nom }}
              </h1>

              <div class="flex flex-wrap gap-3 mb-6">
                <span class="px-3 py-1 bg-[#db492b]/10 text-[#db492b] rounded-full text-sm font-medium">
                  {{ formation.niveau }}
                </span>
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                  {{ formation.diploma }}
                </span>
              </div>

              <!-- Institution Info -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-start gap-4">
                  <div class="p-3 bg-[#db492b]/10 rounded-lg">
                    <AcademicCapIcon class="w-6 h-6 text-[#db492b]" />
                  </div>
                  <div>
                    <h3 class="font-semibold text-gray-900">{{ getInstitutionFullName(formation.type_etablissement) }}</h3>
                    <p class="text-gray-600">{{ formation.ville }}, {{ formation.region }}</p>
                  </div>
                </div>
              </div>

              <!-- Related Degrees -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                  {{ __('formations.related_degrees') }}
                </h2>
                <div class="space-y-4">
                  <div v-for="degree in formation.degrees" :key="degree.id" 
                       class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                      <h3 class="font-medium text-gray-900">{{ degree.pivot.degree_name }}</h3>
                      <p class="text-sm text-gray-600">
                        {{ __('formations.similarity_score') }}: {{ Math.round(degree.pivot.similarity_score * 100) }}%
                      </p>
                    </div>
                    <Link :href="route('degree.show', degree.slug)" 
                          class="text-[#db492b] hover:text-[#db492b]/80">
                      {{ __('formations.view_degree') }} →
                    </Link>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Sidebar -->
            <div class="lg:w-1/3">
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                  {{ __('formations.key_details') }}
                </h2>
                <dl class="space-y-4">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('formations.level') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ formation.niveau }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('formations.diploma') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ formation.diploma }}</dd>
                  </div>
                  <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('formations.discipline') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ formation.discipline }}</dd>
                  </div>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { AcademicCapIcon } from '@heroicons/vue/24/outline';
import { InstitutionTypes } from '@/Enums/InstitutionTypes'

defineProps({
  formation: {
    type: Object,
    required: true
  }
});

const getInstitutionFullName = (shortName) => {
  return InstitutionTypes[shortName] || shortName
}
</script> 