<script setup>
import { Card, CardContent } from '@/Components/ui/card';
import { Progress } from '@/Components/ui/progress';
import { Accordion, AccordionItem, AccordionTrigger, AccordionContent } from '@/Components/ui/accordion';
import __ from '@/lang';
import { computed } from 'vue';

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

const progressStyle = computed(() => ({
  width: `${score.value}%`
}));
</script>

<template>
  <Accordion type="single" collapsible>
    <AccordionItem :value="trait.id || trait.scale_id">
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

          <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all" :class="`bg-${theme.button}`" :style="progressStyle">
              <span class="absolute right-2 text-xs text-white font-medium leading-4">
                {{ score }}%
              </span>
            </div>
          </div>
        </div>
      </AccordionTrigger>
      <AccordionContent>
        <Card :class="`bg-${theme.background.light}`">
          <CardContent class="pt-4">
            {{ isBigFive
              ? __(trait.definition)
              : __(`career.${formatName(trait.trait_name).toLowerCase()}_definition`)
            }}
          </CardContent>
        </Card>
      </AccordionContent>
    </AccordionItem>
  </Accordion>
</template>

<style scoped>
.accordion-trigger {
  width: 100%;
}
</style>