<script setup>
import { Card, CardContent } from '@/Components/ui/card';
import { Progress } from '@/Components/ui/progress';
import { Accordion, AccordionItem, AccordionTrigger, AccordionContent } from '@/Components/ui/accordion';
import __ from '@/lang';
import { computed } from 'vue';
import { useThemeStore } from '@/stores/theme/themeStore';

const themeStore = useThemeStore();

const props = defineProps({
  trait: {
    type: Object,
    required: true
  },
  theme: {
    type: Object,
    required: true
  },
  formatName: {
    type: Function,
    default: (name) => name
  },
  isBigFive: {
    type: Boolean,
    default: false
  }
});

const score = computed(() => {
  const value = props.isBigFive ? props.trait.value : props.trait.trait_score;
  return Math.round(value * 100);
});

const progressClasses = computed(() => ({
  'relative h-4 w-full overflow-hidden rounded-full': true,
  [`[&>[role=progressbar]]:bg-${themeStore.currentTheme.button}`]: true,
  'bg-gray-200 dark:bg-gray-800': true
}));
</script>

<template>
  <Card :class="`bg-${themeStore.currentTheme.background.light} border border-gray-200`">
    <CardContent class="p-4">
      <Accordion type="single" collapsible>
        <AccordionItem :value="trait.id || trait.scale_id" class="border-none">
          <AccordionTrigger>
            <div class="w-full">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">
                  {{ isBigFive
                    ? __(trait.short_name)
                    : __(`career.${formatName(trait.trait_name).toLowerCase()}`)
                  }}
                </h3>
              </div>

              <div class="relative w-full">
                <Progress :model-value="score" :class="progressClasses">
                  <span class="absolute right-2 top-0 text-xs text-white font-medium leading-4">
                    {{ score }}%
                  </span>
                </Progress>
              </div>
            </div>
          </AccordionTrigger>
          <AccordionContent>
            <div class="pt-4">
              {{ isBigFive
                ? __(trait.definition)
                : __(`career.${formatName(trait.trait_name).toLowerCase()}_definition`)
              }}
            </div>
          </AccordionContent>
        </AccordionItem>
      </Accordion>
    </CardContent>
  </Card>
</template>
<style scoped>
.accordion-trigger {
  width: 100%;
}
</style>
