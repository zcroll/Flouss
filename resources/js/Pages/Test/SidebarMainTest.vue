<template>
  <div class="assessment-progress" :class="{ minimized: isMinimized }">
    <div id="journey" class="assessment-progress--inner">
      <div class="Journey">
        <div class="Journey--maximized">
          <div class="Journey__toggle" @click="toggleMinimized">
            <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up"
              class="svg-inline--fa fa-chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
              <path fill="currentColor"
                d="M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z" />
            </svg>
          </div>
          <h2 class="Journey__head sr-only">Your Progress</h2>
          <div class="Journey__body">
            <Milestone status="complete" title="Start" :progress="100" />
            
            <template v-for="stage in testProgressStore.stageSequence" :key="stage">
              <Milestone 
                :status="getMilestoneStatus(stage)"
                :title="testProgressStore.getStageInfo(stage).name"
                :time="testProgressStore.getStageInfo(stage).time"
                :progress="testProgressStore.stageProgress(stage)" 
              />
            </template>
          </div>
        </div>
        <div class="Journey--minimized" @click="toggleMinimized">
          <div>
            <div class="Journey__head">Up Next</div>
            <div class="Journey__next-name">{{ nextMilestoneName }}</div>
          </div>
          <div class="Journey__progress">
            <MinimizedProgress 
              v-for="stage in testProgressStore.stageSequence" 
              :key="stage"
              :status="getMilestoneStatus(stage)"
              :progress="testProgressStore.stageProgress(stage)" 
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useTestProgressStore } from '@/stores/testProgressStore';
import Milestone from '@/Pages/Test/Milestone.vue';
import MinimizedProgress from '@/Pages/Test/MinimizedProgress.vue';

// Define props
const props = defineProps({
  progress: {
    type: Object,
    required: true,
    validator: (value) => {
      return (
        typeof value === 'object' &&
        'hollandCodes' in value &&
        'basicInterest' in value &&
        'degree' in value &&
        'completed' in value
      );
    },
    default: () => ({
      hollandCodes: 0,
      basicInterest: 0,
      degree: 0,
      personality: 0,
      completed: false
    })
  },
  testStage: String,
});

const testProgressStore = useTestProgressStore();
const isMinimized = ref(true);

const getMilestoneStatus = (stage) => {
  return testProgressStore.stageStatus(stage);
};

const nextMilestoneName = computed(() => {
  const currentStage = testProgressStore.currentStage;
  const nextStage = testProgressStore.getNextStage(currentStage);
  
  if (!nextStage) return testProgressStore.getStageInfo(currentStage)?.name;
  return testProgressStore.getStageInfo(nextStage)?.name;
});

const toggleMinimized = () => {
  isMinimized.value = !isMinimized.value;
};

// Watch test stage changes
watch(() => props.testStage, (newStage, oldStage) => {
  if (newStage) {
    testProgressStore.setCurrentStage(newStage);
  }
}, { immediate: true });

// Watch progress updates
watch(() => props.progress, (newProgress) => {
  if (!newProgress) return;

  // Update progress for each stage type
  Object.entries(newProgress).forEach(([key, value]) => {
    const stageMap = {
      hollandCodes: 'holland_codes',
      basicInterest: 'basic_interests',
      degree: 'degree',
      personality: 'personality'
    };

    const stage = stageMap[key];
    if (stage && value) {
      if (typeof value === 'object') {
        testProgressStore.updateStageProgress(stage, {
          currentIndex: value.currentIndex,
          validResponses: value.validResponses,
          percentage: value.percentage,
          completed: value.completed,
          current_index: value.currentIndex,
          progress_percentage: value.percentage,
          ...(stage === 'basic_interests' && value.jobMatching ? { jobMatching: value.jobMatching } : {}),
          ...(stage === 'degree' && value.degreeMatching ? { degreeMatching: value.degreeMatching } : {}),
          ...(stage === 'personality' && value.personalityReport ? { personalityReport: value.personalityReport } : {})
        });
      } else {
        testProgressStore.updateStageProgress(stage, value);
      }
    }
  });

  // Handle overall completion
  if (newProgress.completed) {
    testProgressStore.markStageComplete(props.testStage);
  }
}, { deep: true });

// Initialize on mount
onMounted(() => {
  if (props.testStage) {
    testProgressStore.setCurrentStage(props.testStage);
  }
});
</script>

<style scoped>
.Journey__toggle {
  cursor: pointer;
  padding: 0.5rem;
}

.Journey__toggle svg {
  width: 1rem;
  height: 1rem;
  transition: transform 0.2s ease;
}

.minimized .Journey__toggle svg {
  transform: rotate(180deg);
}

.Journey--minimized {
  cursor: pointer;
}
</style>
