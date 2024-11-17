<template>
  <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/global.fc24fef1e7c4.css">

  <link rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/assessments.ba16abcb0f5b.css">

<section class="Roadmap" data-testid="roadmap">
<div class="assessment-with-progress">
<SidebarMainTest :progress="progress" :testStage="testStage" />
<div class="assessment Roadmap__inner">
  <WelcomeBack v-if="showWelcomeBack" @continue="continueTest" />
  <section v-else class="Assessment" tabindex="-1" data-testid="assessment-test" @submit.prevent="submitAnswer">
    <section class="Assessment__ItemSetLeadIn" v-if="currentItemIndex !== 0">{{ testStage === 'holland_codes' ? 'Would you like to...' : 'Would you enjoy...' }}</section>
    <div class="Assessment__ItemSetLeadIn Assessment__ItemSetLeadIn--spacer" aria-hidden="true"></div>
    <div class="Assessment__scroll-container">
      <div>
        <div class="Assessment__Item--forward-appear-done Assessment__Item--forward-enter-done">
          <NextStep v-if="showNextStep" @continue="hideNextStep" />
          <div v-else class="Assessment__Item Assessment__RadioItem Assessment__RadioItem--active"
               :data-item-index="currentItemIndex"
               :data-itemset-index="currentSetIndex"
               :id="'item-' + currentItem.id"
               :aria-label="ariaLabel"
               tabindex="1"
               aria-atomic="true"
               aria-live="assertive">
            <div>
                <button v-if="currentItemIndex > 0"
                        type="button"
                        title="Go back"
                        aria-label="Go Back"
                        aria-hidden="true"
                        class="Assessment__RadioItem__back"
                        data-testid="back-item"
                        @click="goBack">
                  <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up" class="svg-inline--fa fa-chevron-up fa-fw " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z"></path></svg>
                </button>
                <form @submit.prevent="submitAnswer" ref="formRef">
                    <h4 v-if="currentItemIndex === 0" aria-hidden="true">{{ testStage === 'holland_codes' ? 'Would you like to...' : 'Would you enjoy...' }}</h4>
                    <h3 aria-hidden="true">{{ testStage === 'holland_codes' ? currentItem.text : currentItem.category }}</h3>
                    <h5 v-if="testStage === 'basic_interests'" aria-hidden="true"><p>{{ currentItem.question }}</p></h5>
                    <div :id="'radio-item-' + currentItem.id"
                         class="RadioFieldCollection RadioFieldCollection--List RadioFieldCollection--button-variant"
                         :data-value-selected="form.answer"
                         data-testid="">
                        <div class="RadioField"
                             :aria-labelledby="'radio-item-' + currentItem.id"
                             v-for="option in options"
                             :key="option.id">
                            <input class="TextField TextField--radiofield TextField--type-radio TextField--alive TextField--is-blurred TextField--input TextField--dirty"
                                   :id="option.id"
                                   :name="'radio-item-' + currentItem.id"
                                   tabindex="-1"
                                   :data-qa-id="option.id"
                                   :data-testid="option.id"
                                   autocomplete="on"
                                   type="radio"
                                   :value="option.value"
                                   :checked="previousAnswers[currentItem.id] === option.value"
                                   v-model="form.answer"
                                   @change="submitAnswer"
                                   @click="form.answer = option.value; submitAnswer()" />
                            <label class="RadioField--label" :for="option.id">
                                <span class="RadioField--label__inner">{{ option.label }}</span>
                            </label>
                        </div>
                    </div>
                    <button type="button"
                            title="Skip question"
                            aria-label="Skip question"
                            aria-hidden="true"
                            class="Assessment__RadioItem__skip"
                            data-testid="skip-item"
                            @click="skipQuestion">Skip question</button>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="Discovery" aria-live="polite" aria-atomic="true" aria-relevant="text" role="presentation"></section>
</div>
</div>
</section>
</template>

<script>
import {  computed, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import SidebarMainTest from "@/Pages/Test/SidebarMainTest.vue";
import { router } from '@inertiajs/vue3';
import WelcomeBack from "@/Components/Test/WelcomeBack.vue";
import { ref, onMounted } from 'vue';
import axios from 'axios';
import anime from 'animejs/lib/anime.es.js';
import NextStep from "@/Components/Test/NextStep.vue";

export default {
components: { SidebarMainTest, WelcomeBack, NextStep },
props: {
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
},
setup(props, { emit }) {
const page = usePage();
const form = useForm({
itemId: props.currentItem.id,
type: 'answered',
answer: null,
category: props.testStage === 'holland_codes'
  ? props.hollandCodeData[props.currentSetIndex].title
  : props.currentItem.category,
testStage: props.testStage,
_token: page.props.csrf_token
});
const previousAnswers = ref({});
const options = [
{ id: 'hate-it', value: 1, label: 'Hate it' },
{ id: 'dislike-it', value: 2, label: 'Dislike it' },
{ id: 'neutral', value: 3, label: 'Neutral' },
{ id: 'like-it', value: 4, label: 'Like it' },
{ id: 'love-it', value: 5, label: 'Love it' },
];
const ariaLabel = computed(() => {
const prefix = props.testStage === 'holland_codes' ? 'Would you like to' : 'Would you enjoy';
const questionText = props.currentItem.text || props.currentItem.question;
return `${prefix} ${questionText}? Use the number keys to select one of the following; 1: Hate it. 2: Dislike it. 3: Neutral. 4: Like it. 5: Love it. To skip this question, press the down arrow. To go back to the previous question, press the up arrow.`;
});
const formRef = ref(null);

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

  anime({
    targets: formRef.value,
    ...animations[direction].start,
    ...timing,
    complete: () => {
      callback();
      anime({
        targets: formRef.value,
        ...animations[direction].end,
        ...timing
      });
    }
  });
};

const goBack = () => {
  router.post(route('test.go-back'), {
    _token: page.props.csrf_token
  }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      if (page.props.testStage) emit('update:testStage', page.props.testStage);
      if (page.props.currentItem) {
        emit('update:currentItem', page.props.currentItem);
        form.answer = previousAnswers.value[page.props.currentItem.id] || null;
      }
      if (page.props.currentSetIndex !== undefined) emit('update:currentSetIndex', page.props.currentSetIndex);
      if (page.props.currentItemIndex !== undefined) emit('update:currentItemIndex', page.props.currentItemIndex);
      
      form.itemId = props.currentItem.id;
      form.category = props.testStage === 'holland_codes'
        ? props.hollandCodeData[props.currentSetIndex].title
        : props.currentItem.category;
      form.testStage = props.testStage;
      form._token = page.props.csrf_token;

      animateTransition('back', () => {});
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
    ? 'store-holland-code-response'
    : 'store-basic-interest-response';

  if (form.answer !== null) {
    previousAnswers.value[form.itemId] = form.answer;
  }

  router.post(route(routeName), form, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      if (page.props.testStage) emit('update:testStage', page.props.testStage);
      if (page.props.currentItem) emit('update:currentItem', page.props.currentItem);
      if (page.props.currentSetIndex !== undefined) emit('update:currentSetIndex', page.props.currentSetIndex);
      if (page.props.currentItemIndex !== undefined) emit('update:currentItemIndex', page.props.currentItemIndex);

      form.reset();
      form.itemId = props.currentItem.id;
      form.category = props.testStage === 'holland_codes'
        ? props.hollandCodeData[props.currentSetIndex].title
        : props.currentItem.category;
      form.testStage = props.testStage;
      form._token = page.props.csrf_token;

      animateTransition('next', () => {});
    },
    onError: (errors) => {
      console.error('Error submitting response', errors);
    },
  });
};

watch(() => props.currentItem, (newItem) => {
form.reset();
form.itemId = newItem.id;
form.category = props.testStage === 'holland_codes'
  ? props.hollandCodeData[props.currentSetIndex].title
  : newItem.category;
form.testStage = props.testStage;
form._token = page.props.csrf_token;
form.answer = previousAnswers.value[newItem.id] || null;
});

watch(() => props.progress, (newProgress) => {
console.log('Progress updated:', newProgress);
});

const showWelcomeBack = ref(false);
const checkWelcomeBack = async () => {
  const response = await axios.get(route('welcome-back.show'));
  showWelcomeBack.value = response.data.showWelcomeBack;
};
const continueTest = async () => {
  await axios.post(route('welcome-back.set-shown'));
  showWelcomeBack.value = false;
};

const showNextStep = ref(false);

const hideNextStep = () => {
  showNextStep.value = false;
};

watch(() => props.testStage, (newStage) => {
  if (newStage === 'basic_interests' && props.currentItemIndex === 0) {
    showNextStep.value = true;
  }
});

onMounted(() => {
  checkWelcomeBack();
});
return {
form,
options,
skipQuestion,
submitAnswer,
ariaLabel,
goBack,
showWelcomeBack,
continueTest,
previousAnswers,
formRef,
showNextStep,
hideNextStep
};
},
};
</script>



