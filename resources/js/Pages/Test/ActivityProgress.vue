<template>
  <div>
    <link
      rel="stylesheet"
      media="all"
      href="/css/landingPage/index.css"
    />

      <link rel="stylesheet" media="all" href="/css/landingPage/main-test.css"/>

    <div class="flex justify-center items-center min-h-screen">
      <div class="test-page__wrapper">
        <div class="test-page__wrapper__main with-save">
          <div v-if="!showOverview" class="blur-box-container blur-box-container__main blur-box-container--spaced">
            <div class="blur-box blur-box__main">
              <div class="interests main-test align-center">
                <div class="main-test__wrapper">
                  <div class="inner">
                    <div class="main-test__box">
                      <div class="grid-x main-test__top">
                        <h2 class="main-test__header">
                          {{ __('test.career_interests_test') }}
                        </h2>
                      </div>
                      <div>
                        <div class="rc-slider rc-slider-with-marks">
                          <div class="slider">
                            <span class="slider-mark">{{ currentQuestion }}</span>
                            <span class="slider-mark">{{ Math.ceil(activities.length / 6) }}</span>
                          </div>
                        </div>
                        <!-- Progress bar with conditional red points -->
                        <div class="progress-bar">
                          <div class="progress" :style="{ width: progressPercentage + '%' }"></div>
                          <div v-for="(point, index) in incompletePoints" :key="index"
                               class="incomplete-point"
                               :style="{ left: (point * 100 / Math.ceil(activities.length / 6)) + '%' }"
                               @click="goToQuestion(point)">
                          </div>
                        </div>
                      </div>
                      <div class="grid-x">
                        <p class="test__question">
                          {{ __('test.rank_activities') }}
                        </p>
                      </div>
                      <draggable
                        v-model="currentActivities"
                        group="activities"
                        @start="onDragStart"
                        @end="onDragEnd"
                        item-key="id"
                        class="colored-ordered-list"
                      >
                        <template #item="{element, index}">
                          <div class="colored-list-item">
                            <span class="item" :class="`number-${index + 1}`">{{ index + 1 }}</span>
                            <div class="grid-x grid-padding-x">
                              <div class="test-statement">
                                <div class="grid-x test-statement__select">
                                  <div class="cell auto test-statement__option test-statement__option--no-img">
                                    <p class="test-statement__option__text">
                                      {{ element.name }}
                                    </p>

                                  </div>
                                  <div class="cell shrink">
                                    <div class="remove">
                                      <span class="small-icons drag-drop-icon"></span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </template>
                      </draggable>
                      <div class="grid-x fixed-grid">
                        <button @click="previousQuestion" :disabled="currentQuestion === 1" class="button button--white before-fade-in fade-in nav-btn">
                          <span class="small-icons back-arrow-white-xs is-left"></span>
                          {{ __('test.previous') }}
                        </button>
                        <button v-if="!questionInteracted && !isQuestionRanked && !isLastQuestion" @click="skipQuestion" class="button button--yellow before-fade-in fade-in nav-btn">
                          {{ __('test.skip') }}
                        </button>
                        <button v-else-if="!isLastQuestion" @click="nextQuestion" class="button button--green before-fade-in fade-in nav-btn">
                          {{ __('test.next') }}
                          <span class="small-icons next-arrow-white-xs is-right"></span>
                        </button>
                        <button v-else @click="submitTest" class="button button--blue before-fade-in fade-in nav-btn">
                          {{ __('test.submit') }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-copy interests"></div>
            </div>
          </div>
          <div v-else>
            <Overview
              :rankedActivities="rankedActivities"
              :totalActivities="activities.length"
              :incompletePoints="incompletePoints"
              :currentQuestion="currentQuestion"
              @go-to-question="goToQuestion"
              @go-back-to-test="toggleOverview"
              @save-for-later="saveForLater"
              @submit-answers="submitTest"
            />
          </div>
          <div class="test-btn-container flex-center">
            <a @click="toggleOverview" class="text-button">{{ showOverview ? __('test.return_to_test') : __('test.overview') }}</a>
            <a class="text-button">{{ __('test.instructions') }}</a>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showIncompleteModal" class="popup__content">
      <div class="grid-x confirmation-content">
        <div class="cell">
          <div class="large-icon unanswered-questions mar-auto"></div>
          <h3 class="popup__title">{{ __('test.unanswered_questions') }}</h3>
          <p class="popup__text">
            {{ __('test.incomplete_warning') }}
          </p>
        </div>
        <div class="cell mar-top-20">
          <button @click="closeIncompleteModal" class="button button--blue-off before-fade-in fade-in button--bigger mar-right-10">
            {{ __('test.no_take_me_back') }}
            <span class="button__hovered"></span>
          </button>
          <button @click="confirmSubmit" class="button button--green before-fade-in fade-in button--bigger">
            {{ __('test.yes_im_done') }}
            <span class="small-icons next-arrow-white-l"></span>
            <span class="button__hovered"></span>
          </button>
        </div>
      </div>
      <a @click="closeIncompleteModal" class="popup__close">×</a>
    </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3'
import Overview from './Overview.vue'
import draggable from 'vuedraggable'
import __ from '@/lang'

export default {
  components: {
    Overview,
    draggable
  },
  props: {
    activities: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      currentQuestion: 1,
      drag: false,
      userResponses: [],
      currentActivities: [],
      incompletePoints: [],
      questionInteracted: false,
      rankedActivities: [],
      showOverview: false,
      showIncompleteModal: false
    }
  },
  computed: {
    progressPercentage() {
      return (this.currentQuestion / Math.ceil(this.activities.length / 6)) * 100;
    },
    isQuestionRanked() {
      const start = (this.currentQuestion - 1) * 6;
      return this.rankedActivities.some(activity => activity.id === this.activities[start].id);
    },
    isLastQuestion() {
      return this.currentQuestion === Math.ceil(this.activities.length / 6);
    }
  },
  watch: {
    currentActivities: {
      handler(newVal) {
        this.updateActivities(newVal);
      },
      deep: true
    }
  },
  created() {
    this.loadCurrentActivities();
  },
  methods: {
    loadCurrentActivities() {
      const start = (this.currentQuestion - 1) * 6;
      const end = start + 6;
      this.currentActivities = this.activities.slice(start, end);
      this.questionInteracted = false;
    },
    onDragStart() {
      this.questionInteracted = true;
      this.removeIncompletePoint(this.currentQuestion);
    },
    onDragEnd() {
      this.drag = false;
      this.storeUserResponse();
    },
    nextQuestion() {
      if (this.currentQuestion < Math.ceil(this.activities.length / 6)) {
        this.storeUserResponse();
        this.currentQuestion++;
        this.loadCurrentActivities();
      }
    },
    submitTest() {
      this.storeUserResponse();
      if (this.incompletePoints.length > 0) {
        this.showIncompleteModal = true;
      } else {
        this.performSubmit();
      }
    },
    closeIncompleteModal() {
      this.showIncompleteModal = false;
    },
    confirmSubmit() {
      this.showIncompleteModal = false;
      this.performSubmit();
    },
    performSubmit() {
      const form = useForm({
        responses: this.rankedActivities
      });

      form.post(route('test.submit-answer'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          console.log('Test submitted successfully');
        },
        onError: (errors) => {
          console.error('Error submitting test:', errors);
        }
      });
    },
    skipQuestion() {
      if (this.currentQuestion < Math.ceil(this.activities.length / 6)) {
        this.addIncompletePoint(this.currentQuestion);
        this.currentQuestion++;
        this.loadCurrentActivities();
      }
    },
    previousQuestion() {
      if (this.currentQuestion > 1) {
        this.currentQuestion--;
        this.loadCurrentActivities();
      }
    },
    storeUserResponse() {
      const start = (this.currentQuestion - 1) * 6;
      const rankedActivities = this.currentActivities.map((activity, index) => {
        let score;
        switch(index) {
          case 0: score = 5; break;
          case 1: score = 4; break;
          case 2: score = 3; break;
          case 3: score = 2; break;
          case 4: score = 1; break;
          case 5: score = 0; break;
          default: score = 0;
        }
        return { id: activity.id, score: score, category: activity.category };
      });

      this.rankedActivities = this.rankedActivities.filter(activity =>
        !rankedActivities.some(newActivity => newActivity.id === activity.id)
      );

      this.rankedActivities = [...this.rankedActivities, ...rankedActivities];

      this.userResponses[this.currentQuestion - 1] = [...this.currentActivities];
      console.log('User responses:', this.userResponses);
      console.log('Ranked activities:', this.rankedActivities);
    },
    updateActivities(newActivities) {
      const start = (this.currentQuestion - 1) * 6;
      const end = start + 6;
      this.activities.splice(start, 6, ...newActivities);
    },
    addIncompletePoint(question) {
      if (!this.incompletePoints.includes(question)) {
        this.incompletePoints.push(question);
      }
    },
    removeIncompletePoint(question) {
      const index = this.incompletePoints.indexOf(question);
      if (index > -1) {
        this.incompletePoints.splice(index, 1);
      }
    },
    goToQuestion(question) {
      this.currentQuestion = question;
      this.loadCurrentActivities();
      this.showOverview = false;
    },
    saveForLater() {
      console.log('Saving for later...');
    },
    toggleOverview() {
      this.showOverview = !this.showOverview;
    },
    __
  }
}
</script>

<style scoped>
.progress-bar {
  width: 100%;
  height: 10px;
  background-color: #e0e0e0;
  border-radius: 5px;
  margin-top: 10px;
  position: relative;
}

.progress {
  height: 100%;
  background-color: #fb6303;
  border-radius: 5px;
  transition: width 0.3s ease-in-out;
}

.incomplete-point {
  position: absolute;
  top: -5px;
  width: 20px;
  height: 20px;
  background-color: red;
  border-radius: 50%;
  cursor: pointer;
}

.button--yellow {
  background-color: #fb6303;
  color: #000;
}

.button--blue {
  background-color: #007BFF;
  color: #fff;
}

.popup__content {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  z-index: 1000;
}

.popup__close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 24px;
  cursor: pointer;
}

.test-statement__option__category {
  font-size: 14px;
  color: #888;
  margin-top: 5px;
}
</style>
