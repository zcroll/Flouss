<template>

  <Head title="Personality Traits" />

  <StickySidebar type="career" :model="occupation">
    <template #description>
      <p :class="textClass">
        Discover the key personality traits and characteristics that make successful {{ occupation.name }}s.
      </p>
    </template>

    <div class="space-y-8">
      <Breadcrumbs :items="breadcrumbItems" class="mb-8" />

      <!-- Overview Section -->
      <section class="space-y-6">
        <h2 :class="headingClass">
          {{ __('career.we_surveyed') }} {{ occupation.name.toLowerCase() }}s
          {{ __('career.to_learn_what_personality_traits') }}
        </h2>
      </section>

      <!-- Navigation Card -->
      <Card class="w-full">
        <CardHeader>
          <CardTitle>{{ __('career.in_this_article') }}</CardTitle>
        </CardHeader>
        <CardContent>
          <nav class="space-y-2">
            <NavigationLink href="#holland-codes" :theme="themeStore.currentTheme">
              {{ __('career.primary_interests') }}
            </NavigationLink>
            <NavigationLink href="#big-five" :theme="themeStore.currentTheme">
              {{ __('career.broad_personality_traits') }}
            </NavigationLink>
          </nav>
        </CardContent>
      </Card>

      <!-- Holland Codes Section -->
      <section id="holland-codes" class="space-y-6">
        <h2 :class="headingClass">
          {{ occupation.name }}s {{ __('career.are') }}
          <em :class="emphasisClass">{{ getTopHollandTraits }}</em>
        </h2>

        <p :class="descriptionClass">
          {{ getPersonalityDescription('Holland Codes') }}
        </p>

        <div class="space-y-4">
          <PersonalityTrait v-for="trait in hollandCodeTraits" :key="trait.id" :trait="trait"
            :theme="themeStore.currentTheme" :format-name="formatTraitName" />
        </div>
      </section>

      <!-- Big Five Section -->
      <section id="big-five" class="space-y-6">
        <h2 :class="headingClass">
          {{ __('career.top_personality_traits_of') }}
          {{ occupation.name.toLowerCase() }}s
          {{ __('career.are') }}
          <em :class="emphasisClass">{{ getTopBigFiveTraits }}</em>
        </h2>

        <p :class="descriptionClass">
          {{ getPersonalityDescription('Big Five') }}
        </p>

        <div class="space-y-4">
          <PersonalityTrait v-for="trait in bigFiveTraits" :key="trait.scale_id" :trait="trait"
            :theme="themeStore.currentTheme" :is-big-five="true" />
        </div>
      </section>
    </div>

    <BackToTop />
  </StickySidebar>
</template>

<script setup>
import { computed } from 'vue';
import { useThemeStore } from '@/stores/theme/themeStore';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import BackToTop from "@/Components/helpers/BackToTop.vue";
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue';
import NavigationLink from '@/Components/career/NavigationLink.vue';
import PersonalityTrait from '@/Components/career/PersonalityTrait.vue';
import __ from '@/lang';
import {
  Card,
  CardHeader,
  CardTitle,
  CardContent
} from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';

defineOptions({ layout: MainLayout });

const themeStore = useThemeStore();

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

// Computed Styles
const textClass = computed(() => `text-sm text-${themeStore.currentTheme.primary}-600`);
const headingClass = computed(() =>
  `text-2xl font-semibold tracking-tight text-${themeStore.currentTheme.primary}-900 dark:text-${themeStore.currentTheme.primary}-100`
);
const emphasisClass = computed(() => `font-normal text-${themeStore.currentTheme.primary}-600`);
const descriptionClass = computed(() =>
  `text-${themeStore.currentTheme.primary}-600 dark:text-${themeStore.currentTheme.primary}-300`
);

// Computed Data
const breadcrumbItems = computed(() => [
  { name: 'Home', route: 'dashboard' },
  { name: 'Jobs', route: 'jobs.index' },
  { name: props.occupation.name, route: 'career', params: { id: props.occupation.id } },
  { name: 'Personality' }
]);

const hollandCodeTraits = computed(() =>
  props.personalityTraits.filter(trait => trait.trait_type === 'Holland Codes')
);

const bigFiveTraits = computed(() =>
  props.personalityTraits
    .filter(trait => trait.trait_type === 'Big Five')
    .map(trait => ({
      name: trait.trait_name,
      short_name: `career.${trait.trait_name.toLowerCase()}`,
      scale_id: trait.id,
      definition: `career.${trait.trait_name.toLowerCase()}_definition`,
      value: trait.trait_score
    }))
);

// Helper Functions
const formatTraitName = (traitName) => {
  if (!traitName) return '';
  return traitName.replace('Interest in ', '').replace(' Jobs', '');
};

const getPersonalityDescription = (traitType) =>
  props.personalityDetails.find(d => d.trait_type === traitType)?.description;

const getTopTraits = (traits, valueKey = 'trait_score', nameTransform = (t) => t.trait_name) => {
  const top2 = traits.value
    .sort((a, b) => b[valueKey] - a[valueKey])
    .slice(0, 2)
    .map(nameTransform);
  return `${top2[0]} and ${top2[1]}`;
};

const getTopHollandTraits = computed(() =>
  getTopTraits(hollandCodeTraits, 'trait_score', t => formatTraitName(t.trait_name).toLowerCase())
);

const getTopBigFiveTraits = computed(() =>
  getTopTraits(bigFiveTraits, 'value', t => t.short_name)
);
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}
</style>
