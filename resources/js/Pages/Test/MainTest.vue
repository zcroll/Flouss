<template>

        <link rel="stylesheet" href="/css/assessment.css">

  <section class="Roadmap" data-testid="roadmap">
    <div class="assessment-with-progress">
      <SidebarMainTest />
      <div class="assessment Roadmap__inner">
        <form class="Assessment" tabindex="-1" data-testid="assessment-test" @submit.prevent="submitAnswer">
          <div class="Assessment__ItemSetLeadIn Assessment__ItemSetLeadIn--spacer" aria-hidden="true"></div>
          <div class="Assessment__scroll-container">
            <div>
              <!-- Holland Codes Section -->
              <div v-if="testStage === 'holland_codes'" class="Assessment__Item--forward-appear-done Assessment__Item--forward-enter-done">
                <div class="Assessment__Item Assessment__RadioItem Assessment__RadioItem--active" :data-item-index="currentItemIndex" :data-itemset-index="currentSetIndex" :id="'item-' + currentItem.id" :aria-label="ariaLabel" tabindex="1" aria-atomic="true" aria-live="assertive">
                  <div>
                    <h4 aria-hidden="true">Would you like to...</h4>
                    <h3 aria-hidden="true">{{ currentItem.text }}</h3>
                    <div :id="'radio-item-' + currentItem.id" class="RadioFieldCollection RadioFieldCollection--List RadioFieldCollection--button-variant" :data-value-selected="form.answer" data-testid="">
                      <div class="RadioField" :aria-labelledby="'radio-item-' + currentItem.id" v-for="option in options" :key="option.id">
                        <input class="TextField TextField--radiofield TextField--type-radio TextField--alive TextField--is-blurred TextField--input TextField--dirty" :id="option.id" :name="'radio-item-' + currentItem.id" tabindex="-1" :data-qa-id="option.id" :data-testid="option.id" autocomplete="on" type="radio" :value="option.value" v-model="form.answer" @change="submitAnswer" />
                        <label class="RadioField--label" :for="option.id">
                          <span class="RadioField--label__inner">{{ option.label }}</span>
                        </label>
                      </div>
                    </div>
                    <button type="button" title="Skip question" aria-label="Skip question" aria-hidden="true" class="Assessment__RadioItem__skip" data-testid="skip-item" @click="skipQuestion">Skip question</button>
                  </div>
                </div>
              </div>

              <!-- Basic Interests Section -->
              <div v-else-if="testStage === 'basic_interests'" class="Assessment__Item--forward-appear-done Assessment__Item--forward-enter-done">
                <div class="Assessment__Item Assessment__RadioItem Assessment__RadioItem--active" :data-item-index="currentItemIndex" :id="'item-' + currentItem.id" :aria-label="ariaLabel" tabindex="1" aria-atomic="true" aria-live="assertive">
                  <div>
                    <h4 aria-hidden="true">Would you enjoy...</h4>
                    <h3 aria-hidden="true">{{ currentItem.category }}</h3>
                    <h5 aria-hidden="true"><p>{{ currentItem.question }}</p></h5>
                    <div :id="'radio-item-' + currentItem.id" class="RadioFieldCollection RadioFieldCollection--List RadioFieldCollection--button-variant" :data-value-selected="form.answer" data-testid="">
                      <div class="RadioField" :aria-labelledby="'radio-item-' + currentItem.id" v-for="option in options" :key="option.id">
                        <input class="TextField TextField--radiofield TextField--type-radio TextField--alive TextField--is-blurred TextField--input TextField--dirty" :id="option.id" :name="'radio-item-' + currentItem.id" tabindex="-1" :data-qa-id="option.id" :data-testid="option.id" autocomplete="on" type="radio" :value="option.value" v-model="form.answer" @change="submitAnswer" />
                        <label class="RadioField--label" :for="option.id">
                          <span class="RadioField--label__inner">{{ option.label }}</span>
                        </label>
                      </div>
                    </div>
                    <button type="button" title="Skip question" aria-label="Skip question" aria-hidden="true" class="Assessment__RadioItem__skip" data-testid="skip-item" @click="skipQuestion">Skip question</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        <section class="Discovery" aria-live="polite" aria-atomic="true" aria-relevant="text" role="presentation"></section>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import SidebarMainTest from "@/Pages/Test/SidebarMainTest.vue";
import { router } from '@inertiajs/vue3';

export default {
  components: { SidebarMainTest },
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
  },
  setup(props, { emit }) {
    const form = useForm({
      itemId: props.currentItem.id,
      type: 'answered',
      answer: null,
      category: props.testStage === 'holland_codes'
        ? props.hollandCodeData[props.currentSetIndex].title
        : props.currentItem.category,
      testStage: props.testStage,
    });

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

    const skipQuestion = () => {
      form.type = 'skipped';
      form.answer = null;
      submitAnswer();
    };

    const submitAnswer = () => {
      const routeName = props.testStage === 'holland_codes'
        ? 'store-holland-code-response'
        : 'store-basic-interest-response';

      router.post(route(routeName), form, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
          console.log('Response successfully submitted');
          // Emit events to update local state if needed
          if (page.props.testStage) {
            emit('update:testStage', page.props.testStage);
          }
          if (page.props.currentItem) {
            emit('update:currentItem', page.props.currentItem);
          }
          if (page.props.currentSetIndex !== undefined) {
            emit('update:currentSetIndex', page.props.currentSetIndex);
          }
          if (page.props.currentItemIndex !== undefined) {
            emit('update:currentItemIndex', page.props.currentItemIndex);
          }
          // Reset form for the next question
          form.reset();
          form.itemId = props.currentItem.id;
          form.category = props.testStage === 'holland_codes'
            ? props.hollandCodeData[props.currentSetIndex].title
            : props.currentItem.category;
          form.testStage = props.testStage;
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
    });

    return {
      form,
      options,
      skipQuestion,
      submitAnswer,
      ariaLabel,
    };
  },
};
</script>

<style>
</style>
