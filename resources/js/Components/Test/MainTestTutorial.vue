<template>
  <div id="tutorial-screen" class="welcome" aria-atomic="true" aria-live="assertive" tabindex="0">
    <div class="welcome__container">
      <h1 class="welcome__title">Career Assessment Guide</h1>
      <h2 class="sr-only">Learn how to take the assessment</h2>
      
      <div class="progress-grid">
        <div class="progress-item">
          <div class="progress-heading">
            <span>{{ currentStep + 1 }}/{{ tutorialSteps.length }}</span>
            <div class="progress-indicator" aria-label="Tutorial Progress"></div>
          </div>
          <span class="progress-subtext">Step by Step Guide</span>
        </div>
        <div class="progress-item">
          <div class="progress-heading">30 minutes</div>
          <span class="progress-subtext">Assessment Time</span>
        </div>
        <div class="progress-item">
          <div class="progress-heading">Career Match</div>
          <span class="progress-subtext">Get Results</span>
        </div>
      </div>

      <div class="tutorial-content">
        <div class="tutorial-card" :class="`tutorial-card--${tutorialSteps[currentStep].variant}`">
          <div class="sr-only">
            <h4>{{ tutorialSteps[currentStep].title }}</h4>
            <p>{{ tutorialSteps[currentStep].content }}</p>
          </div>
          
          <div class="card-content">
            <div class="card-header">
              <div class="card-icon" :class="tutorialSteps[currentStep].iconBg" v-html="tutorialSteps[currentStep].icon"></div>
              <h3 class="card-title">{{ tutorialSteps[currentStep].title }}</h3>
            </div>
            
            <p class="card-description">{{ tutorialSteps[currentStep].content }}</p>

            <div v-if="tutorialSteps[currentStep].example" class="card-example">
              <div class="example-label">Example:</div>
              <div class="example-content" v-html="tutorialSteps[currentStep].example"></div>
            </div>
          </div>
          <div class="navigation">
            <button 
            @click="handlePrevious"
            :disabled="currentStep === 0"
            class="nav-button nav-button--previous"
            :class="{ 'invisible': currentStep === 0 }"
            >
            Previous
          </button>
          
          <button 
          @click="handleNext"
          class="nav-button nav-button--next"
          >
            {{ currentStep === tutorialSteps.length - 1 ? 'Start Assessment' : 'Next Step' }}
          </button>
        </div>
      </div>
      </div>

      
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const currentStep = ref(0);

const tutorialSteps = [
  {
    title: "Welcome to Your Career Assessment",
    variant: "career-matches",
    icon: `<svg class="icon-star" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`,
    iconBg: 'bg-amber',
    content: "This assessment uses the Holland Code (RIASEC) theory to match your personality with ideal career paths. Remember, there are no wrong answers - just be yourself!"
  },
  {
    title: "Understanding Holland Codes",
    variant: "personality-type",
    icon: `<svg class="icon-heart" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>`,
    iconBg: 'bg-purple',
    content: "Your personality type combines: Realistic (R), Investigative (I), Artistic (A), Social (S), Enterprising (E), and Conventional (C). Each represents different work preferences.",
    example: `<div class="code-grid">
              <div>• R: Practical, Physical</div>
              <div>• I: Analytical, Scientific</div>
              <div>• A: Creative, Artistic</div>
              <div>• S: Helpful, Teaching</div>
              <div>• E: Leadership, Business</div>
              <div>• C: Organized, Detail-oriented</div>
             </div>`
  },
  {
    title: "How to Answer Questions",
    variant: "assessment-guide",
    icon: `<svg class="icon-clock" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/></svg>`,
    iconBg: 'bg-green',
    content: "Rate activities from 'Strongly Dislike' to 'Strongly Like' based on your interest. Focus on enjoyment, not skill. Every answer is valid - just be honest!",
    example: `<div class="example-question">
              <div>Question: "Would you enjoy designing a website?"</div>
              <div class="rating-scale">
                <span>Strongly Dislike</span>
                <span>Neutral</span>
                <span>Strongly Like</span>
              </div>
             </div>`
  },
  {
    title: "Your Career Journey Begins",
    variant: "career-path",
    icon: `<svg class="icon-briefcase" viewBox="0 0 20 20"><path d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"/></svg>`,
    iconBg: 'bg-blue',
    content: "After completing the assessment, you'll get personalized career suggestions based on your unique interests. Remember, all responses are valuable. Let's start your career discovery!"
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

<style scoped>
.welcome {
  @apply h-full w-full flex items-center justify-center p-8 bg-stone-800 text-stone-100;
}

.welcome__container {
  @apply max-w-3xl w-full;
}

.welcome__title {
  @apply text-4xl font-bold text-center mb-8 text-amber-400;
}

.progress-grid {
  @apply grid grid-cols-3 gap-6 mb-8;
}

.progress-item {
  @apply text-center;
}

.progress-heading {
  @apply text-xl font-semibold text-amber-400 flex items-center justify-center gap-2;
}

.progress-indicator {
  @apply w-3 h-3 bg-amber-500 rounded-full animate-pulse;
}

.progress-subtext {
  @apply text-sm text-stone-400;
}

.tutorial-content {
  @apply mb-8;
}

.tutorial-card {
  @apply bg-stone-900 border border-stone-700 rounded-2xl overflow-hidden transition-all duration-300 shadow-lg;
}

.card-content {
  @apply p-6 space-y-6;
}

.card-header {
  @apply flex items-center gap-4;
}

.card-icon {
  @apply p-3 rounded-full;
}

.card-icon svg {
  @apply w-12 h-12;
}

.card-title {
  @apply text-2xl font-bold text-amber-400;
}

.card-description {
  @apply text-stone-300 text-lg leading-relaxed;
}

.card-example {
  @apply bg-stone-900/50 rounded-xl p-4 border border-stone-700;
}

.example-label {
  @apply text-sm text-stone-400 mb-2;
}

.example-content {
  @apply text-stone-200;
}

.navigation {
  @apply flex justify-between items-center gap-4 sticky bottom-0 bg-stone-800 p-6 mt-auto;
}

.nav-button {
  @apply px-6 py-3 rounded-lg font-medium transition-all duration-200 bg-amber-500 text-stone-900 hover:bg-amber-600 min-w-[120px];
}

.nav-button--previous {
  @apply opacity-75 hover:opacity-100 disabled:opacity-50;
}

.icon-star, .icon-heart, .icon-clock, .icon-briefcase {
  @apply fill-current;
}

.bg-amber { @apply bg-amber-500/20; }
.bg-purple { @apply bg-purple-500/20; }
.bg-green { @apply bg-green-500/20; }
.bg-blue { @apply bg-blue-500/20; }

.tutorial-card--career-matches { @apply border-amber-500/30; }
.tutorial-card--personality-type { @apply border-purple-500/30; }
.tutorial-card--assessment-guide { @apply border-green-500/30; }
.tutorial-card--career-path { @apply border-blue-500/30; }

.code-grid {
  @apply grid grid-cols-2 gap-4;
}

.rating-scale {
  @apply flex justify-between text-sm mt-2;
}
</style>