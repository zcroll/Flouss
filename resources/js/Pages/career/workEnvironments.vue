<template>

  <Head title="Work Environments" />
  <StickySidebar type="career" :model="occupation">
    <template #description>
      Explore the various work environments and settings where {{ occupation.name }}s typically work.
    </template>

    <!-- Main Content -->
    <div class="space-y-6">
      <Breadcrumbs :items="[
        { name: 'Home', route: 'dashboard' },
        { name: 'Jobs', route: 'jobs.index' },
        { name: occupation.name, route: 'career', params: { id: occupation.id } },
        { name: 'Work Environments' }
      ]" />

      <!-- Table of Contents -->
      <Card>
        <CardHeader class="pb-3">
          <CardTitle class="text-base">{{ __('career.in_this_article') }}</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-1">
            <div v-for="(items, category) in groupedByCategory" :key="category">
              <Button variant="ghost" class="w-full justify-between px-2 h-8 text-sm font-medium" :class="[
                'transition-colors duration-200',
                selectedCategory === category ?
                  `text-${themeStore.currentTheme.primary}` :
                  'text-muted-foreground'
              ]" @click="toggleCategory(category)">
                {{ category }}
                <Icon :name="selectedCategory === category ? 'chevron-down' : 'chevron-right'"
                  class="h-4 w-4 transition-transform duration-200"
                  :class="selectedCategory === category ? 'rotate-180' : ''" />
              </Button>

              <div v-show="selectedCategory === category" class="animate-accordion-down mt-1 pl-4 space-y-1">
                <Button v-for="item in items" :key="item.id" variant="ghost" size="sm"
                  class="w-full justify-start h-7 text-xs font-normal" :class="[
                    'transition-colors duration-200',
                    highlightedId === item.id ?
                      `text-${themeStore.currentTheme.primary}` :
                      'text-muted-foreground hover:text-foreground'
                  ]" @click="highlightAndScroll(item.id)">
                  {{ item.name }}
                </Button>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Work Environments List -->
      <div class="space-y-6">
        <Card v-for="environment in workEnvironments" :key="environment.id" :id="`section-${environment.id}`" :class="[
          'transition-all duration-300 group hover:shadow-lg',
          { 'ring-2 ring-primary': isHighlighted(environment.id) }
        ]">
          <CardHeader>
            <CardTitle class="flex items-center gap-2 text-lg">
              <Icon :name="getEnvironmentIcon(environment.category)" variant="muted" />
              {{ environment.name }}
            </CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <p class="text-sm text-muted-foreground">{{ environment.description }}</p>

            <div v-if="environment.score !== null && environment.score !== undefined" class="space-y-3">
              <div class="flex items-center justify-between text-sm">
                <span class="text-muted-foreground font-medium">Match Score</span>
                <span 
                  class="font-semibold" 
                  :class="`text-${themeStore.currentTheme.primary}-600 dark:text-${themeStore.currentTheme.primary}-400`"
                >
                  {{ Math.round(environment.score) }}%
                </span>
              </div>
              
              <!-- Progress bar wrapper -->
              <div class="w-full">
                <Progress 
                  :value="environment.score"
                  size="md"
                  :show-value="false"
                />
              </div>
            </div>

            <div class="flex gap-2 flex-wrap mt-4">
              <Badge 
                v-for="skill in environment.skills" 
                :key="skill" 
                variant="secondary" 
                class="text-xs transition-colors duration-200"
                :class="`
                  bg-${themeStore.currentTheme.primary}-50 
                  text-${themeStore.currentTheme.primary}-700
                  dark:bg-${themeStore.currentTheme.primary}-950/20
                  dark:text-${themeStore.currentTheme.primary}-300
                  group-hover:bg-${themeStore.currentTheme.primary}-100
                  dark:group-hover:bg-${themeStore.currentTheme.primary}-950/30
                `"
              >
                {{ skill }}
              </Badge>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>

    <ScrollToTop />
  </StickySidebar>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useThemeStore } from '@/stores/theme/themeStore';
import MainLayout from "@/Layouts/MainLayout.vue";
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue';
import ScrollToTop from "@/Components/helpers/ScrollToTop.vue";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/Components/ui/card";
import { Progress } from "@/Components/ui/progress";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Icon } from "@/Components/ui/icon";

defineOptions({ layout: MainLayout });

const props = defineProps({
  occupation: {
    type: Object,
    required: true,
    validator: (obj) => {
      return ['id', 'slug', 'name', 'image'].every(prop => prop in obj);
    }
  },
  workEnvironments: {
    type: Array,
    required: true,
  },
});

const themeStore = useThemeStore();
const selectedCategory = ref(null);
const highlightedId = ref(null);

const groupedByCategory = computed(() => {
  return props.workEnvironments.reduce((acc, item) => {
    (acc[item.category] = acc[item.category] || []).push(item);
    return acc;
  }, {});
});

const toggleCategory = (category) => {
  selectedCategory.value = selectedCategory.value === category ? null : category;
};

const isHighlighted = (id) => highlightedId.value === id;

const highlightAndScroll = (id) => {
  const element = document.getElementById(`section-${id}`);
  if (element) {
    highlightedId.value = id;

    setTimeout(() => {
      highlightedId.value = null;
    }, 2000);

    element.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
      inline: 'nearest'
    });
  }
};

const getEnvironmentIcon = (category) => {
  const icons = {
    'Office': 'building-office',
    'Field': 'map',
    'Laboratory': 'beaker',
    'Remote': 'computer-desktop',
  };
  return icons[category] || 'office-building';
};
</script>

<style scoped>
.animate-accordion-down {
  overflow: hidden;
  animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
  from {
    height: 0;
    opacity: 0;
    transform: translateY(-4px);
  }

  to {
    height: var(--radix-accordion-content-height);
    opacity: 1;
    transform: translateY(0);
  }
}

.highlight {
  animation: glow 2s ease-out;
}

@keyframes glow {
  0% {
    box-shadow: 0 0 0 rgba(var(--primary), 0.2);
    transform: scale(1);
  }

  50% {
    box-shadow: 0 0 20px rgba(var(--primary), 0.3);
    transform: scale(1.01);
  }

  100% {
    box-shadow: 0 0 0 rgba(var(--primary), 0.2);
    transform: scale(1);
  }
}

:root {
  scroll-behavior: smooth;
}
</style>
