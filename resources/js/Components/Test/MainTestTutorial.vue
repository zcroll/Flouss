<template>
  <main 
    id="tutorial-screen" 
    class="w-full min-h-screen bg-background text-foreground flex items-center justify-center"
    role="main"
  >
    <div class="container mx-auto px-6 py-10 max-w-4xl">
      <Card className="bg-transparent border-none shadow-none">
        <CardHeader className="space-y-8 text-center">
          <p class="text-xl font-light tracking-tight text-muted-foreground">
            {{ __('test.step_x_of_y', { step: currentStep + 1, total: tutorialSteps.length }) }}
          </p>

          <CardTitle className="text-4xl md:text-5xl font-light tracking-tight">
            {{ tutorialSteps[currentStep].title }}
          </CardTitle>

          <CardDescription className="text-xl font-light tracking-tight max-w-2xl mx-auto">
            <template v-if="currentStep === 0">
              {{ __('test.tutorial.welcome.content') }}
              <span class="text-sm font-semibold text-foreground block mt-2">
                {{ __('test.tutorial.welcome.remember') }}
              </span>
            </template>
            <template v-else>
              {{ tutorialSteps[currentStep].content }}
            </template>
          </CardDescription>
        </CardHeader>

        <CardContent>
          <div 
            v-if="tutorialSteps[currentStep].example" 
            class="mt-12 bg-card/50 rounded-xl p-8 mx-auto"
          >
            <p class="text-sm font-medium text-muted-foreground mb-6 text-center">
              {{ __('test.example') }}:
            </p>

            <!-- Holland Codes Example -->
            <div v-if="currentStep === 1" class="text-card-foreground">
              <div class="flex flex-wrap items-center justify-center gap-2 text-base max-w-2xl mx-auto">
                <HoverCard v-for="(item, index) in hollandCodes" :key="index">
                  <HoverCardTrigger asChild>
                    <Button 
                      variant="outline" 
                      class="border-border hover:border-border/80 transition-colors duration-200"
                    >
                      <span :class="item.color" class="font-mono font-semibold tracking-[0.1em]">
                        {{ item.letter }}
                      </span>
                      <span class="font-medium tracking-wide">{{ item.rest }}</span>
                    </Button>
                  </HoverCardTrigger>
                  <HoverCardContent class="w-64">
                    <div class="space-y-2">
                      <h4 class="text-sm font-semibold">{{ item.title }}</h4>
                      <p class="text-sm text-muted-foreground">{{ item.traits }}</p>
                    </div>
                  </HoverCardContent>
                </HoverCard>
              </div>
            </div>

            <!-- Rating Example -->
            <div v-if="currentStep === 2" class="space-y-4">
              <h3 class="text-lg font-medium">{{ __('test.example_question') }}</h3>
              <div class="flex justify-between text-muted-foreground text-sm">
                <span>{{ __('test.hate_it') }}</span>
                <span>{{ __('test.dislike_it') }}</span>
                <span>{{ __('test.neutral') }}</span>
                <span>{{ __('test.like_it') }}</span>
                <span>{{ __('test.love_it') }}</span>
              </div>
            </div>
          </div>
        </CardContent>

        <CardFooter className="flex justify-center gap-4 mt-8">
          <div 
            class="flex" 
            :class="{ 
              'justify-between w-full': currentStep > 0, 
              'justify-center': currentStep === 0 
            }"
          >
            <Button
              v-if="currentStep > 0"
              variant="secondary"
              @click="handlePrevious"
            >
              {{ __('test.back') }}
            </Button>

            <Button
              @click="handleNext"
              variant="default"
              class="bg-primary hover:bg-primary/90"
            >
              {{ currentStep === tutorialSteps.length - 1 
                ? __('test.start_assessment') 
                : __('test.next') 
              }}
            </Button>
          </div>
        </CardFooter>
      </Card>
    </div>
  </main>
</template>

<script setup>
import { ref } from 'vue';
import { 
  HoverCard, 
  HoverCardContent, 
  HoverCardTrigger 
} from '@/Components/ui/hover-card';
import { 
  Card, 
  CardHeader, 
  CardTitle, 
  CardDescription,
  CardContent,
  CardFooter
} from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
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
