<template>
  <div>
    <link
      rel="stylesheet"
      media="all"
      href="//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css"
    />
    <link rel="stylesheet" media="all" href="//cdn3.careerhunter.io/assets/main_tests-bd884139ec646feab4ac86d2f494e405033a3d476b5b9ac820fc8fbc6576c466.css" />

    <div class="flex justify-center items-center min-h-screen">
      <div class="test-page__wrapper">
        <div class="test-page__wrapper__main with-save">
          <div class="blur-box-container blur-box-container__main blur-box-container--spaced">
            <div class="blur-box blur-box__main">
              <div class="interests main-test align-center">
                <div class="main-test__wrapper">
                  <div class="inner">
                    <div class="main-test__box">
                      <div class="grid-x main-test__top">
                        <h2 class="main-test__header">
                          Career Interests Test
                        </h2>
                      </div>
                      <div>
                        <div class="rc-slider rc-slider-with-marks">
                          <div class="slider">
                            <span class="slider-mark">{{ currentPage }}</span>
                            <span class="slider-mark">{{ Math.ceil(activities.length / 6) }}</span>
                          </div>
                        </div>
                        <!-- Progress bar with conditional red points -->
                        <div class="progress-bar">
                          <div class="progress" :style="{ width: progressPercentage + '%' }"></div>
                          <div v-for="(point, index) in incompletePoints" :key="index" 
                               class="incomplete-point" 
                               :style="{ left: (point / Math.ceil(activities.length / 6)) * 100 + '%' }"
                               @click="goToPage(point)">
                          </div>
                        </div>
                      </div>
                      <div class="grid-x">
                        <p class="test__question">
                          Rank the activities in order of preference.
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
                        <button @click="previousPage" :disabled="currentPage === 1" class="button button--white before-fade-in fade-in nav-btn">
                          <span class="small-icons back-arrow-white-xs is-left"></span>
                          Previous
                        </button>
                        <button v-if="!pageInteracted && !isPageRanked && !isLastPage" @click="skipPage" class="button button--yellow before-fade-in fade-in nav-btn">
                          Skip
                        </button>
                        <button v-else-if="!isLastPage" @click="nextPage" class="button button--green before-fade-in fade-in nav-btn">
                          Next
                          <span class="small-icons next-arrow-white-xs is-right"></span>
                        </button>
                        <button v-else @click="submitTest" class="button button--blue before-fade-in fade-in nav-btn">
                          Submit
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-copy interests"></div>
            </div>
          </div>
          <div class="test-btn-container flex-center">
            <a class="text-button">Overview</a>
            <a class="text-button">Instructions</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3'

export default {
  props: {
    activities: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      currentPage: 1,
      drag: false,
      userResponses: [],
      currentActivities: [],
      incompletePoints: [],
      pageInteracted: false,
      rankedActivities: []
    }
  },
  computed: {
    progressPercentage() {
      return (this.currentPage / Math.ceil(this.activities.length / 6)) * 100;
    },
    isPageRanked() {
      const start = (this.currentPage - 1) * 6;
      return this.rankedActivities.some(activity => activity.id === this.activities[start].id);
    },
    isLastPage() {
      return this.currentPage === Math.ceil(this.activities.length / 6);
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
      const start = (this.currentPage - 1) * 6;
      const end = start + 6;
      this.currentActivities = this.activities.slice(start, end);
      this.pageInteracted = false;
    },
    onDragStart() {
      this.pageInteracted = true;
      this.removeIncompletePoint(this.currentPage);
    },
    onDragEnd() {
      this.drag = false;
      this.storeUserResponse();
    },
    nextPage() {
      if (this.currentPage < Math.ceil(this.activities.length / 6)) {
        this.storeUserResponse();
        this.currentPage++;
        this.loadCurrentActivities();
      }
    },
    submitTest() {
      this.storeUserResponse(); // Store the last page response
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
    skipPage() {
      if (this.currentPage < Math.ceil(this.activities.length / 6)) {
        this.addIncompletePoint(this.currentPage);
        this.currentPage++;
        this.loadCurrentActivities();
      }
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.loadCurrentActivities();
      }
    },
    storeUserResponse() {
      const start = (this.currentPage - 1) * 6;
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
      
      // Remove any existing entries for these activities
      this.rankedActivities = this.rankedActivities.filter(activity => 
        !rankedActivities.some(newActivity => newActivity.id === activity.id)
      );
      
      // Add the new rankings
      this.rankedActivities = [...this.rankedActivities, ...rankedActivities];
      
      this.userResponses[this.currentPage - 1] = [...this.currentActivities];
      console.log('User responses:', this.userResponses);
      console.log('Ranked activities:', this.rankedActivities);
    },
    updateActivities(newActivities) {
      const start = (this.currentPage - 1) * 6;
      const end = start + 6;
      this.activities.splice(start, 6, ...newActivities);
    },
    addIncompletePoint(page) {
      if (!this.incompletePoints.includes(page)) {
        this.incompletePoints.push(page);
      }
    },
    removeIncompletePoint(page) {
      const index = this.incompletePoints.indexOf(page);
      if (index > -1) {
        this.incompletePoints.splice(index, 1);
      }
    },
    goToPage(page) {
      this.currentPage = page;
      this.loadCurrentActivities();
    }
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
  background-color: #4CAF50;
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
  background-color: #FFD700;
  color: #000;
}

.button--blue {
  background-color: #007BFF;
  color: #fff;
}
</style>
