<template>
  <Head title="Personality Traits" />
  <StickySidebar type="career" :model="occupation">
    <template #description>
      <h1 class="text-2xl font-bold mb-2">Personality Traits</h1>
      <p class="text-muted-foreground">
        Discover the key personality traits and characteristics that make successful {{ occupation.name }}s.
      </p>
    </template>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto space-y-8">
      <Breadcrumbs :items="breadcrumbItems" />

      <!-- Categories Navigation -->
      <nav class="sticky top-4 z-10 rounded-lg p-4 shadow-sm" :class="[
        themeStore.isDarkMode 
          ? 'bg-gray-800/40 backdrop-blur-xl border border-gray-700'
          : 'bg-white/40 backdrop-blur-xl border border-gray-200'
      ]">
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
          <Button
            v-for="category in ['Holland Codes', 'Big Five']"
            :key="category"
            variant="ghost"
            size="sm"
            :class="[
              'whitespace-nowrap transition-colors duration-200',
              selectedCategory === category
                ? `bg-${themeStore.currentTheme.primary}-100 text-${themeStore.currentTheme.primary}-700 
                   dark:bg-${themeStore.currentTheme.primary}-900/20 dark:text-${themeStore.currentTheme.primary}-300`
                : 'hover:bg-muted'
            ]"
            @click="selectedCategory = category"
          >
            <Icon 
              :name="category === 'Holland Codes' ? 'puzzle-piece' : 'brain'" 
              class="w-4 h-4 mr-2" 
            />
            {{ category }}
          </Button>
        </div>
      </nav>

      <!-- Personality Traits Grid -->
      <TransitionGroup 
        name="trait-list" 
        tag="div" 
        class="grid grid-cols-1 md:grid-cols-2 gap-6"
      >
        <Card
          v-for="trait in filteredTraits"
          :key="trait.id"
          class="group relative overflow-hidden transition-all duration-300 hover:shadow-lg"
        >
          <!-- Category Badge -->
          <div 
            class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-medium"
            :class="`bg-${themeStore.currentTheme.primary}-50 text-${themeStore.currentTheme.primary}-700
                    dark:bg-${themeStore.currentTheme.primary}-900/20 dark:text-${themeStore.currentTheme.primary}-300`"
          >
            {{ trait.trait_type }}
          </div>

          <CardHeader>
            <CardTitle class="flex items-start gap-3">
              <div 
                class="p-2 rounded-lg"
                :class="`bg-${themeStore.currentTheme.primary}-50 dark:bg-${themeStore.currentTheme.primary}-900/20`"
              >
                <Icon 
                  :name="getTraitIcon(trait)" 
                  class="w-5 h-5"
                  :class="`text-${themeStore.currentTheme.primary}-500`"
                />
              </div>
              <div>
                <h3 class="text-lg font-semibold">
                  {{ formatTraitName(trait.trait_name) }}
                </h3>
              </div>
            </CardTitle>
          </CardHeader>

          <CardContent class="space-y-6">
            <!-- Description -->
            <p class="text-sm text-muted-foreground leading-relaxed">
              {{ getTraitDescription(trait) }}
            </p>

            <!-- Score -->
            <div class="space-y-2">
              <div class="flex items-center justify-between text-sm">
                <span class="text-muted-foreground font-medium">Trait Score</span>
                <span 
                  class="font-semibold"
                  :class="`text-${themeStore.currentTheme.primary}-600 dark:text-${themeStore.currentTheme.primary}-400`"
                >
                  {{ formatScore(trait.trait_score) }}%
                </span>
              </div>
              <Progress 
                :value="formatScore(trait.trait_score)"
                size="md"
                class="rounded-full"
              />
            </div>

            <!-- Related Traits -->
            <div class="flex flex-wrap gap-2">
              <Badge 
                v-for="relatedTrait in getRelatedTraits(trait)"
                :key="relatedTrait"
                variant="secondary"
                class="text-xs transition-all duration-200"
                :class="`
                  bg-${themeStore.currentTheme.primary}-50/50
                  text-${themeStore.currentTheme.primary}-700
                  dark:bg-${themeStore.currentTheme.primary}-900/10
                  dark:text-${themeStore.currentTheme.primary}-300
                  group-hover:bg-${themeStore.currentTheme.primary}-100
                  dark:group-hover:bg-${themeStore.currentTheme.primary}-900/20
                `"
              >
                {{ relatedTrait }}
              </Badge>
            </div>
          </CardContent>
        </Card>
      </TransitionGroup>
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
  personalityTraits: {
    type: Array,
    required: true,
  },
  personalityDetails: {
    type: Array,
    required: true,
  },
});

const themeStore = useThemeStore();
const selectedCategory = ref('Holland Codes');

// Computed
const breadcrumbItems = computed(() => [
  { name: 'Home', route: 'dashboard' },
  { name: 'Jobs', route: 'jobs.index' },
  { name: props.occupation.name, route: 'career', params: { id: props.occupation.id } },
  { name: 'Personality' }
]);

const filteredTraits = computed(() => {
  return props.personalityTraits.filter(trait => trait.trait_type === selectedCategory.value);
});

// Helper Functions
const formatTraitName = (traitName) => {
  if (!traitName) return '';
  return traitName.replace('Interest in ', '').replace(' Jobs', '');
};

const getTraitIcon = (trait) => {
  const icons = {
    'Realistic': 'wrench',
    'Investigative': 'magnifying-glass',
    'Artistic': 'paint-brush',
    'Social': 'users',
    'Enterprising': 'presentation-chart-bar',
    'Conventional': 'clipboard-document-list',
    // Add more icons for Big Five traits
    'Openness': 'light-bulb',
    'Conscientiousness': 'check-circle',
    'Extraversion': 'user-group',
    'Agreeableness': 'heart',
    'Neuroticism': 'brain',
  };

  const traitName = formatTraitName(trait.trait_name);
  return icons[traitName] || 'star';
};

const getTraitDescription = (trait) => {
  return props.personalityDetails.find(
    detail => detail.trait_type === trait.trait_type
  )?.description || '';
};

const getRelatedTraits = (trait) => {
  // This is a placeholder - implement your logic to get related traits
  return ['Leadership', 'Problem Solving', 'Communication'].slice(0, 2);
};

const formatScore = (score) => {
  // Convert decimal score to percentage and round to nearest integer
  return Math.round(parseFloat(score) * 100);
};
</script>

<style scoped>
.trait-list-move,
.trait-list-enter-active,
.trait-list-leave-active {
  transition: all 0.5s ease;
}

.trait-list-enter-from,
.trait-list-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

.trait-list-leave-active {
  position: absolute;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>
