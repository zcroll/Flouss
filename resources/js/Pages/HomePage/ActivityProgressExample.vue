<template>
  <div>
    <link
      rel="stylesheet"
      media="all"
      href="/css/landingPage/index.css"
    />
    <link 
      rel="stylesheet" 
      media="all" 
      href="/css/landingPage/main-test.css" />
  
    
    <div class="test-image test-image--parallax interests">
      <div class="test-page">
        <div class="test-page__wrapper--sample">
          <div class="test-page__wrapper__main">
            <div class="test-sample-desc">
              <h2 class="before-fade-in fade-in">
                {{ __('activity_progress.test_sample') }}
              </h2>
              <p class="before-fade-in fade-in">
                {{ __('activity_progress.sample_description') }}
              </p>
            </div>
            <div class="blur-box-container blur-box-container__main blur-box-container--spaced">
              <div class="blur-box blur-box__main">
                <div class="main-test main-test--draggable align-center">
                  <!-- Content of the main test area goes here -->
                  <div class="main-test__wrapper">
                    <div class="inner">
                      <div class="main-test__box">
                        <h2 class="main-test__header">
                          {{ __('activity_progress.career_interests_test_example') }}
                        </h2>
                        <!-- Progress bar -->
                        <div class="progress-bar">
                          <div class="progress" :style="{ width: progressPercentage + '%' }"></div>
                        </div>
                        <p class="test__question">
                          {{ __('activity_progress.rank_activities') }}
                        </p>
                        <!-- Draggable list -->
                        <draggable
                          v-model="currentActivities"
                          group="activities"
                          item-key="id"
                          class="colored-ordered-list"
                        >
                          <template #item="{element, index}">
                            <div class="colored-list-item">
                              <span class="item" :class="`number-${index + 1}`">{{ index + 1 }}</span>
                              <div class="test-statement">
                                <p class="test-statement__option__text">
                                  {{ __(`activity_progress.activities.${element.key}`) }}
                                </p>
                              </div>
                              <span class="drag-handle-icon">&#8942;</span>
                            </div>
                          </template>
                        </draggable>
                        <!-- Navigation buttons -->
                        <div class="grid-x fixed-grid">
                          <button @click="previousPage" :disabled="currentPage === 1" class="button button--white transition duration-150 ease-in-out hide-for-small-only">
                            {{ __('activity_progress.previous') }}
                          </button>
                          <button v-if="!isLastPage" @click="nextPage" class="button button--green">
                            {{ __('activity_progress.next') }}
                          </button>
                          <button v-else @click="submitTest" class="button button--blue">
                            {{ __('activity_progress.submit') }}
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="bg-copy interests"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import draggable from 'vuedraggable';

export default {
  name: 'ActivityProgressExample',
  components: {
    draggable,
  },
  setup() {
    const activities = [
      { id: 1, key: 'develop_mobile_app' },
      { id: 2, key: 'write_short_story' },
      { id: 3, key: 'design_logo' },
      { id: 4, key: 'analyze_financial_data' },
      { id: 5, key: 'plan_marketing_campaign' },
    ];

    const currentPage = ref(1);
    const currentActivities = ref(activities.slice(0, 5));

    const progressPercentage = computed(() => {
      return (currentPage.value / Math.ceil(activities.length / 5)) * 100;
    });

    const isLastPage = computed(() => {
      return currentPage.value === Math.ceil(activities.length / 5);
    });

    function nextPage() {
      if (!isLastPage.value) {
        currentPage.value++;
        loadCurrentActivities();
      }
    }

    function previousPage() {
      if (currentPage.value > 1) {
        currentPage.value--;
        loadCurrentActivities();
      }
    }

    function loadCurrentActivities() {
      const start = (currentPage.value - 1) * 5;
      const end = start + 5;
      currentActivities.value = activities.slice(start, end);
    }

    function submitTest() {
      console.log('Test submitted');
      // Add submission logic here
    }

    return {
      currentPage,
      currentActivities,
      progressPercentage,
      isLastPage,
      nextPage,
      previousPage,
      submitTest,
    };
  },
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

.colored-list-item {
  padding: 10px 0;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.test-statement {
  padding: 5px 0;
  flex-grow: 1;
}

.test-statement__option__text {
  margin: 0;
}

.drag-handle-icon {
  font-size: 20px;
  color: #888;
  cursor: move;
  user-select: none;
}
</style>
