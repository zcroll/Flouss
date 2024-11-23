<template>


  <section class="Roadmap" data-testid="roadmap">
    <div class="assessment-with-progress">
      <SidebarMainTest :progress="progress" :test-stage="testStage" />
      <div class="assessment Roadmap__inner">
        <MainTestTutorial v-if="showTutorial" @continue="startTest" />
        <WelcomeBack v-else-if="showWelcomeBack" @continue="continueTest" />
        <section v-else class="Assessment" tabindex="-1" data-testid="assessment-test" @submit.prevent="submitAnswer">
          <section class="Assessment__ItemSetLeadIn" v-if="currentItemIndex !== 0">
            {{ testStage === 'holland_codes' ? __('test.would_you_like') : __('test.would_you_enjoy') }}
          </section>
          <div class="Assessment__ItemSetLeadIn Assessment__ItemSetLeadIn--spacer" aria-hidden="true"></div>
          <div class="Assessment__scroll-container">
            <div>
              <div class="Assessment__Item--forward-appear-done Assessment__Item--forward-enter-done">
                <NextStep v-if="showNextStep" @continue="hideNextStep" />
                <TestQuestion v-else :current-item="currentItem" :current-item-index="currentItemIndex"
                  :current-set-index="currentSetIndex" :test-stage="testStage" :previous-answers="previousAnswers"
                  :form="form" @submit="submitAnswer" @go-back="goBack" @skip="skipQuestion" ref="questionRef" />

              </div>
            </div>
          </div>
        </section>
        <section class="Discovery" aria-live="polite" aria-atomic="true" aria-relevant="text" role="presentation">
        </section>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import SidebarMainTest from './SidebarMainTest.vue';
import WelcomeBack from '@/Components/Test/WelcomeBack.vue';
import NextStep from '@/Components/Test/NextStep.vue';
import TestQuestion from '@/Components/Test/TestQuestion.vue';
import axios from 'axios';
import anime from 'animejs/lib/anime.es.js';
import MainTestTutorial from '@/Components/Test/MainTestTutorial.vue';

const props = defineProps({
  hollandCodeData: Array,
  basicInterests: Array,
  currentSetIndex: Number,
  currentItemIndex: Number,
  currentItem: Object,
  totalSets: Number,
  totalItems: Number,
  responses: Array,
  message: String,
  responseCount: Number,
  testStage: String,
  progress: Object,
});

const page = usePage();
const questionRef = ref(null);
const previousAnswers = computed(() => {
  const answers = {};
  if (props.responses && Array.isArray(props.responses)) {
    props.responses.forEach(response => {
      if (response.itemId && response.answer !== undefined) {
        answers[response.itemId] = parseInt(response.answer);
      }
    });
  }
  return answers;
});
const showWelcomeBack = ref(false);
const showNextStep = ref(false);
const showTutorial = ref(true);

const form = useForm({
  itemId: props.currentItem?.id,
  type: 'answered',
  answer: null,
  category: props.testStage === 'holland_codes'
    ? props.hollandCodeData[props.currentSetIndex]?.title
    : props.currentItem?.category,
  testStage: props.testStage,
  _token: page.props.csrf_token
});

const animateTransition = (direction, callback) => {
  const animations = {
    next: {
      start: { translateY: [0, '-100%'], scale: [1, 0.98], opacity: [1, 0] },
      end: { translateY: ['100%', 0], scale: [0.98, 1], opacity: [0, 1] }
    },
    back: {
      start: { translateY: [0, '100%'], scale: [1, 0.98], opacity: [1, 0] },
      end: { translateY: ['-100%', 0], scale: [0.98, 1], opacity: [0, 1] }
    }
  };

  const timing = {
    duration: 400,
    easing: 'cubicBezier(.4,0,.2,1)'
  };

  if (questionRef.value?.formRef) {
    anime({
      targets: questionRef.value.formRef,
      ...animations[direction].start,
      ...timing,
      complete: () => {
        callback();
        anime({
          targets: questionRef.value.formRef,
          ...animations[direction].end,
          ...timing
        });
      }
    });
  } else {
    callback();
  }
};

const goBack = () => {
  animateTransition('back', () => {
    router.get(route('main-test.go-back'), {}, {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        // Reset the form with the previous answer if it exists
        if (props.currentItem && previousAnswers.value[props.currentItem.id] !== undefined) {
          form.answer = previousAnswers.value[props.currentItem.id];
        }
      }

      animateTransition('back', () => { });
    }

  });
};

const skipQuestion = () => {
  form.type = 'skipped';
  form.answer = null;
  submitAnswer();
};

const submitAnswer = () => {
  const routeName = props.testStage === 'holland_codes'
    ? 'main-test.holland-code'
    : 'main-test.basic-interest';

  if (form.answer !== null) {
    // Update the previous answers map
    previousAnswers.value[form.itemId] = form.answer;
  }

  router.post(route(routeName), form, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      // Reset form for next question
      form.reset();
      form.itemId = props.currentItem.id;
      form.category = props.testStage === 'holland_codes'
        ? props.hollandCodeData[props.currentSetIndex].title
        : props.currentItem.category;
      form.testStage = props.testStage;
      form._token = page.props.csrf_token;

      animateTransition('next', () => { });
    },
    onError: (errors) => {
      console.error('Error submitting response', errors);
    },
  });
};

const handleReAnswer = (newAnswer) => {
  form.answer = newAnswer;
  submitAnswer();
};

const checkWelcomeBack = async () => {
  try {
    const response = await axios.get(route('welcome-back.show'));
    showWelcomeBack.value = response.data.showWelcomeBack;
  } catch (error) {
    console.error('Error checking welcome back status:', error);
  }
};

const continueTest = async () => {
  try {
    await axios.post(route('welcome-back.set-shown'));
    showWelcomeBack.value = false;
  } catch (error) {
    console.error('Error continuing test:', error);
  }
};

const hideNextStep = () => {
  showNextStep.value = false;
};

watch(() => props.testStage, (newStage) => {
  if (newStage === 'basic_interests' && props.currentItemIndex === 0) {
    showNextStep.value = true;
  }
});

watch(() => props.currentItem, (newItem) => {
  if (newItem) {
    form.itemId = newItem.id;
    form.category = props.testStage === 'holland_codes'
      ? props.hollandCodeData[props.currentSetIndex].title
      : newItem.category;
    form.testStage = props.testStage;
    // Set the previous answer if it exists
    if (previousAnswers.value[newItem.id] !== undefined) {
      form.answer = previousAnswers.value[newItem.id];
    } else {
      form.answer = null;
    }
  }
});

const startTest = () => {
  showTutorial.value = false;
};

onMounted(() => {
  checkWelcomeBack();
});
</script>


<style scoped>
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css');
@import url('https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/assessments.ba16abcb0f5b.css');
</style>
