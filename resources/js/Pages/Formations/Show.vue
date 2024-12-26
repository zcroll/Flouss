<template>

  <Head title="Formation" />
  <div class="min-h-screen container mx-auto px-4 max-w-7xl mt-8">
    <!-- Hero Section -->
    <Card :class="[
      'relative overflow-hidden mb-8'
    ]">
      <CardContent class="p-8">
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Left Content -->
          <div class="lg:w-2/3">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
              <Link :href="route('formations.index')" :class="[themeClasses.link]">
              {{ __('formations.all_formations') }}
              </Link>
              <span>/</span>
              <span>{{ formation.discipline }}</span>
            </div>

            <h1 :class="[
              'text-3xl md:text-4xl font-bold mb-4',
              themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
            ]">
              {{ formation.nom }}
            </h1>

            <div class="flex flex-wrap gap-3 mb-6">
              <span :class="[themeClasses.tag]">
                {{ formation.niveau }}
              </span>
              <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                {{ formation.diploma }}
              </span>
            </div>

            <!-- Institution Info -->
            <Card class="mb-6">
              <CardContent class="p-6">
                <div class="flex items-start gap-4">
                  <div :class="[
                    'p-3 rounded-lg',
                    `bg-${themeStore.currentTheme.primary}-50`
                  ]">
                    <GraduationCap :class="[
                      'w-6 h-6',
                      `text-${themeStore.currentTheme.primary}-600`
                    ]" />
                  </div>
                  <div>
                    <h3 class="font-semibold text-gray-900">{{ getInstitutionFullName(formation.type_etablissement) }}
                    </h3>
                    <p class="text-gray-600">{{ formation.ville }}, {{ formation.region }}</p>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Modules (Coming Soon) -->
            <Card class="mb-6">
              <CardContent class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                  {{ __('formations.modules') }}
                </h2>
                <div class="flex flex-col items-center justify-center p-8 bg-gray-50 rounded-lg">
                  <div class="mb-4">
                    <BookOpen :class="[
                      'w-12 h-12',
                      `text-${themeStore.currentTheme.primary}-400/60`
                    ]" />
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    {{ __('formations.coming_soon') }}
                  </h3>
                  <p class="text-gray-600 text-center text-sm">
                    {{ __('formations.coming_soon_description') }}
                  </p>
                </div>
              </CardContent>
            </Card>

            <!-- Admission Openings (Coming Soon) -->
            <Card>
              <CardContent class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                  {{ __('formations.admission_openings') }}
                </h2>
                <div class="flex flex-col items-center justify-center p-8 bg-gray-50 rounded-lg">
                  <div class="mb-4">
                    <Clock :class="[
                      'w-12 h-12',
                      `text-${themeStore.currentTheme.primary}-400/60`
                    ]" />
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    {{ __('formations.coming_soon') }}
                  </h3>
                  <p class="text-gray-600 text-center text-sm">
                    {{ __('formations.coming_soon_description') }}
                  </p>
                </div>
              </CardContent>
            </Card>
          </div>

          <!-- Right Sidebar -->
          <div class="lg:w-1/3">
            <Card class="sticky top-8">
              <CardContent class="p-6">
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
              </CardContent>
            </Card>
          </div>
        </div>
      </CardContent>
    </Card>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Card, CardContent } from "@/Components/ui/card";
import { Clock, BookOpen, GraduationCap } from 'lucide-vue-next';
import { InstitutionTypes } from '@/Enums/InstitutionTypes';
import { useThemeStore } from '@/stores/theme/themeStore';

defineOptions({
  layout: MainLayout
});

defineProps({
  formation: {
    type: Object,
    required: true
  }
});

const themeStore = useThemeStore();

const themeClasses = computed(() => ({
  link: [
    'hover:text-gray-900 transition-colors duration-200',
    themeStore.isDarkMode
      ? `hover:text-${themeStore.currentTheme.primary}-400`
      : `hover:text-${themeStore.currentTheme.primary}-600`
  ],
  tag: [
    'px-3 py-1 rounded-full text-sm font-medium',
    themeStore.isDarkMode
      ? `bg-${themeStore.currentTheme.primary}-400/10 text-${themeStore.currentTheme.primary}-400`
      : `bg-${themeStore.currentTheme.primary}-50 text-${themeStore.currentTheme.primary}-600`
  ]
}));

const getInstitutionFullName = (shortName) => {
  return InstitutionTypes[shortName] || shortName;
};
</script>