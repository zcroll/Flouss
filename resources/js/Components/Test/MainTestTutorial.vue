<template>
  <div id="tutorial-screen" class="w-full min-h-screen bg-zinc-950 text-zinc-50 flex items-center justify-center" aria-atomic="true" aria-live="assertive" tabindex="0">
    <div class="container mx-auto px-6 py-10 max-w-4xl text-center">
      <div class="space-y-8">
        <h4 
          class="text-xl font-light tracking-tight text-zinc-400 mb-6" 
          aria-hidden="true"
        >
          {{ __('test.step_x_of_y', { step: currentStep + 1, total: tutorialSteps.length }) }}
        </h4>

        <h3 
          class="text-4xl md:text-5xl font-light tracking-tight mb-6" 
          aria-hidden="true"
        >
          {{ tutorialSteps[currentStep].title }}
        </h3>

        <h5 
          class="text-xl font-light tracking-tight text-zinc-300 max-w-2xl mx-auto mb-8 tracking-wide"
          aria-hidden="true"
        >
          <template v-if="currentStep === 0">
            {{ __('test.tutorial.welcome.content') }} 
            <span class="text-sm font-semibold text-white block mt-2">{{ __('test.tutorial.welcome.remember') }}</span>
          </template>
          <template v-else>
            {{ tutorialSteps[currentStep].content }}
          </template>
        </h5>
        <div v-if="tutorialSteps[currentStep].example" class="mt-12 bg-zinc-900/50 rounded-xl p-8 mx-auto">
          <div class="text-sm font-medium text-zinc-400 mb-6 text-center">{{ __('test.example') }}:</div>
          <div v-if="currentStep === 1" class="text-zinc-200 max-w-4xl mx-auto">
            <div class="space-y-3">
              <div class="flex flex-wrap items-center justify-center gap-2 text-base max-w-2xl mx-auto">
                <HoverCard v-for="(item, index) in hollandCodes" :key="index">
                  <HoverCardTrigger>
                    <div class="border border-zinc-700 p-2 rounded-lg group hover:border-zinc-500 transition-colors duration-200 cursor-pointer">
                      <div class="flex items-center gap-0">
                        <span :class="item.color" class="font-mono font-semibold tracking-[0.1em] text-xs sm:text-sm">{{ item.letter }}</span>
                        <span class="font-medium tracking-wide text-white text-xs sm:text-sm">{{ item.rest }}</span>
                      </div>
                    </div>
                  </HoverCardTrigger>
                  <HoverCardContent class="w-48 sm:w-64 bg-zinc-950 border border-zinc-950 p-3 rounded-lg shadow-lg">
                    <div class="space-y-1.5">
                      <h4 class="text-xs sm:text-sm font-semibold text-white">{{ item.title }}</h4>
                      <p class="text-xs text-zinc-400">{{ item.traits }}</p>
                    </div>
                  </HoverCardContent>
                </HoverCard>
              </div>
            </div>
          </div>
          <div v-if="currentStep === 2" class="space-y-4">
            <div class="text-lg">{{ __('test.example_question') }}</div>
            <div class="flex justify-between text-zinc-400 text-xs sm:text-base">
              <span>{{ __('test.hate_it') }}</span>
              <span>{{ __('test.dislike_it') }}</span>
              <span>{{ __('test.neutral') }}</span>
              <span>{{ __('test.like_it') }}</span>
              <span>{{ __('test.love_it') }}</span>
            </div>
          </div>
        </div>
        <div class="flex justify-center gap-4 mt-16 mb-8 sm:mb-0">
          <div class="flex" :class="{ 'justify-between w-full': currentStep > 0, 'justify-center': currentStep === 0 }">
            <button 
              v-if="currentStep > 0"
              @click="handlePrevious"
              class="px-4 sm:px-8 py-2 sm:py-3 rounded-lg font-light text-base sm:text-lg transition-colors bg-zinc-300 text-zinc-900 hover:bg-zinc-950 hover:text-zinc-50"
            >
              {{ __('test.back') }}
            </button>
            
            <button 
              @click="handleNext"
              class="px-4 sm:px-8 py-2 sm:py-3 rounded-lg font-light text-base sm:text-lg transition-colors bg-[#C35B8A] text-white hover:bg-[#B04B7A]"
            >
              {{ currentStep === tutorialSteps.length - 1 ? __('test.start_assessment') : __('test.next') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/Components/ui/hover-card';
import __ from '@/lang';

const currentStep = ref(0);

const hollandCodes = [
  {
    letter: 'R',
    rest: __('test.holland_codes.realistic.rest'),
    color: 'text-blue-400',
    title: __('test.holland_codes.realistic.title'),
    traits: __('test.holland_codes.realistic.traits')
  },
  {
    letter: 'I',
    rest: __('test.holland_codes.investigative.rest'),
    color: 'text-purple-400',
    title: __('test.holland_codes.investigative.title'),
    traits: __('test.holland_codes.investigative.traits')
  },
  {
    letter: 'A',
    rest: __('test.holland_codes.artistic.rest'),
    color: 'text-pink-400',
    title: __('test.holland_codes.artistic.title'),
    traits: __('test.holland_codes.artistic.traits')
  },
  {
    letter: 'S',
    rest: __('test.holland_codes.social.rest'),
    color: 'text-green-400',
    title: __('test.holland_codes.social.title'),
    traits: __('test.holland_codes.social.traits')
  },
  {
    letter: 'E',
    rest: __('test.holland_codes.enterprising.rest'),
    color: 'text-yellow-400',
    title: __('test.holland_codes.enterprising.title'),
    traits: __('test.holland_codes.enterprising.traits')
  },
  {
    letter: 'C',
    rest: __('test.holland_codes.conventional.rest'),
    color: 'text-red-400',
    title: __('test.holland_codes.conventional.title'),
    traits: __('test.holland_codes.conventional.traits')
  }
];

const tutorialSteps = [
  {
    title: __('test.tutorial.welcome.title'),
    content: __('test.tutorial.welcome.content')
  },
  {
    title: __('test.tutorial.holland_codes.title'),
    content: __('test.tutorial.holland_codes.content'),
    example: true
  },
  {
    title: __('test.tutorial.how_to_answer.title'),
    content: __('test.tutorial.how_to_answer.content'),
    example: true
  },
  {
    title: __('test.tutorial.journey_begins.title'),
    content: __('test.tutorial.journey_begins.content')
  }
];

const emit = defineEmits(['continue']);

const handleNext = () => {
  if (currentStep.value < tutorialSteps.length - 1) {
    currentStep.value++;
  } else {
    emit('continue');
  }
};

const handlePrevious = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
  }
};
</script>