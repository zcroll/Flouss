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
            <Milestone :status="getMilestoneStatus('holland_codes')"
              title="Your personality archetype" time="~ 3 mins"
              :progress="testProgressStore.stageProgress('holland_codes')" />
            <Milestone :status="getMilestoneStatus('basic_interests')"
              title="Your career matches" time="~ 3 mins"
              :progress="testProgressStore.stageProgress('basic_interests')" />
            <Milestone :status="getMilestoneStatus('workplace')"
              title="Your degree matches" time="~ 1 min"
              :progress="testProgressStore.stageProgress('workplace')" />
            <Milestone :status="getMilestoneStatus('personality')"
              title="Your Results" time="~ 20 mins"
              :description="['Final career matches and insights', 'Personality report', 'Trait report']"
              :progress="testProgressStore.stageProgress('personality')" />
          </div>
        </div>
        <div class="Journey--minimized">
          <div>
            <div class="Journey__head">Up Next</div>
            <div class="Journey__next-name">{{ nextMilestone?.name || 'Your personality archetype' }}</div>
          </div>
          <div class="Journey__right">
            <div class="Journey__toggle" @click="toggleMinimized">
              <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up"
                class="svg-inline--fa fa-chevron-up" role="img" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512">
                <path fill="currentColor"
                  d="M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z" />
              </svg>
            </div>
          </div>
          <div class="Journey__progress">
            <MinimizedProgress v-for="stage in ['holland_codes', 'basic_interests', 'workplace', 'personality']" 
              :key="stage" 
              :status="getMilestoneStatus(stage)"
              :progress="testProgressStore.stageProgress(stage)" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch, onMounted } from 'vue';
import { useTestProgressStore } from '@/stores/testProgressStore';
import Milestone from '@/Pages/Test/Milestone.vue';
import MilestoneProgress from '@/Pages/Test/MilestoneProgress.vue';
import SvgIcon from '@/Components/helpers/SvgIcon.vue';
import { mdiChevronUp } from '@mdi/js';
import MinimizedProgress from '@/Pages/Test/MinimizedProgress.vue';

export default {
  name: 'SidebarMainTest',
  components: {
    Milestone,
    MilestoneProgress,
    SvgIcon,
    MinimizedProgress,
  },
  props: {
    progress: {
      type: Object,
      required: true,
      validator: (value) => {
        return (
          typeof value === 'object' &&
          'hollandCodes' in value &&
          'basicInterest' in value &&
          'completed' in value
        );
      },
      default: () => ({
        hollandCodes: 0,
        basicInterest: 0,
        completed: false
      })
    },
    testStage: String,
  },
  setup(props) {
    const isMinimized = ref(true);
    const testProgressStore = useTestProgressStore();

    // Debug helper
    const logProgressUpdate = (stage, data, source) => {
      console.group(`Progress Update [${stage}] from ${source}`);
      console.log('Data:', data);
      console.log('Current Store State:', testProgressStore.dumpState());
      console.groupEnd();
    };

    // Watch test stage changes
    watch(() => props.testStage, (newStage, oldStage) => {
      if (newStage) {
        console.group('Test Stage Change');
        console.log('From:', oldStage);
        console.log('To:', newStage);
        console.log('Store State Before:', testProgressStore.dumpState());
        
        testProgressStore.setCurrentStage(newStage);
        
        console.log('Store State After:', testProgressStore.dumpState());
        console.groupEnd();
      }
    }, { immediate: true });

    // Watch progress changes
    watch(() => props.progress, (newProgress, oldProgress) => {
      if (newProgress) {
        console.group('Progress Update');
        console.log('Previous:', oldProgress);
        console.log('New:', newProgress);
        console.log('Current Stage:', props.testStage);

        // Update Holland Codes progress
        if ('hollandCodes' in newProgress) {
          const hollandProgress = {
            percentage: newProgress.hollandCodes,
            completed: newProgress.hollandCodes === 100,
            currentIndex: 0,
            validResponses: 0
          };
          logProgressUpdate('holland_codes', hollandProgress, 'props');
          testProgressStore.updateStageProgress('holland_codes', hollandProgress);
        }

        // Update Basic Interest progress
        if ('basicInterest' in newProgress) {
          if (typeof newProgress.basicInterest === 'object') {
            // Use controller's progress data directly
            const basicInterestProgress = {
              currentIndex: newProgress.basicInterest.currentIndex,
              validResponses: newProgress.basicInterest.validResponses,
              percentage: newProgress.basicInterest.percentage,
              completed: newProgress.basicInterest.completed
            };
            logProgressUpdate('basic_interests', basicInterestProgress, 'controller');
            testProgressStore.updateStageProgress('basic_interests', basicInterestProgress);
          } else {
            // Handle legacy number-only progress
            const basicInterestProgress = {
              percentage: newProgress.basicInterest,
              completed: newProgress.basicInterest === 100,
              currentIndex: 0,
              validResponses: 0
            };
            logProgressUpdate('basic_interests', basicInterestProgress, 'legacy');
            testProgressStore.updateStageProgress('basic_interests', basicInterestProgress);
          }
        }

        // Handle stage completion
        if (newProgress.completed) {
          console.log('Stage completed:', props.testStage);
          testProgressStore.markStageComplete(props.testStage);
        } else {
          console.log('Stage not completed, resetting transition');
          testProgressStore.resetStageTransition(props.testStage);
        }

        console.log('Final Store State:', testProgressStore.dumpState());
        console.groupEnd();
      }
    }, { immediate: true });

    const getMilestoneStatus = (stage) => {
      const status = testProgressStore.isStageComplete(stage) ? 'complete' :
        stage === testProgressStore.currentStageName ? 
          testProgressStore.canTransitionFromStage(stage) ? 'complete' : 'in-progress' :
        testProgressStore.isStageComplete(getPreviousStage(stage)) ? 'in-progress' : 'not-started';

      console.log(`Milestone status for ${stage}:`, {
        isComplete: testProgressStore.isStageComplete(stage),
        isCurrent: stage === testProgressStore.currentStageName,
        canTransition: testProgressStore.canTransitionFromStage(stage),
        previousComplete: testProgressStore.isStageComplete(getPreviousStage(stage)),
        finalStatus: status
      });

      return status;
    };

    const getPreviousStage = (stage) => {
      const stages = ['holland_codes', 'basic_interests', 'workplace', 'personality'];
      const index = stages.indexOf(stage);
      return index > 0 ? stages[index - 1] : null;
    };

    const nextMilestone = computed(() => {
      const stages = ['holland_codes', 'basic_interests', 'workplace', 'personality'];
      const nextStage = stages.find(stage => {
        const isCurrentStage = stage === testProgressStore.currentStageName;
        const canTransition = testProgressStore.canTransitionFromStage(stage);
        const isComplete = testProgressStore.isStageComplete(stage);

        console.log(`Next milestone check for ${stage}:`, {
          isCurrentStage,
          canTransition,
          isComplete,
          shouldShow: isCurrentStage ? !canTransition : !isComplete
        });

        return isCurrentStage ? !canTransition : !isComplete;
      });

      const result = nextStage ? {
        name: {
          'holland_codes': 'Your personality archetype',
          'basic_interests': 'Your career matches',
          'workplace': 'Your degree matches',
          'personality': 'Your Results'
        }[nextStage]
      } : null;

      console.log('Next milestone result:', { nextStage, result });
      return result;
    });

    const toggleMinimized = () => {
      isMinimized.value = !isMinimized.value;
    };

    onMounted(() => {
      console.group('Component Mounted');
      console.log('Initial Stage:', props.testStage);
      console.log('Initial Progress:', props.progress);
      console.log('Initial Store State:', testProgressStore.dumpState());
      console.groupEnd();
    });

    return {
      isMinimized,
      testProgressStore,
      nextMilestone,
      toggleMinimized,
      mdiChevronUp,
      getMilestoneStatus
    };
  },
};
</script>

<style scoped></style>
