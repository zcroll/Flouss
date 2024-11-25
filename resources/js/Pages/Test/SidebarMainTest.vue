<template>
  <div class="assessment-progress" :class="{ minimized: isMinimized }">
    <div id="journey" class="assessment-progress--inner">
      <div class="Journey">
        <div class="Journey--maximized">
          <div class="Journey__toggle" @click="toggleMinimized">
            <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up" class="svg-inline--fa fa-chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
              <path fill="currentColor" d="M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z" />
            </svg>
          </div>
          <h2 class="Journey__head sr-only">Your Progress</h2>
          <div class="Journey__body">
            <Milestone status="complete" title="Start" :progress="100" />
            <Milestone
              :status="milestones[0]?.status || 'not-started'"
              :title="milestones[0]?.name || 'Your personality archetype'"
              :time="milestones[0]?.time || '~ 3 mins'"
              :progress="milestones[0]?.progress || 0"
            />
            <Milestone
              :status="milestones[1]?.status || 'not-started'"
              :title="milestones[1]?.name || 'Your career matches'"
              :time="milestones[1]?.time || '~ 3 mins'"
              :progress="milestones[1]?.progress || 0"
            />
            <Milestone
              :status="milestones[2]?.status || 'not-started'"
              :title="milestones[2]?.name || 'Your degree matches'"
              :time="milestones[2]?.time || '~ 1 min'"
              :progress="milestones[2]?.progress || 0"
            />
            <Milestone
              :status="milestones[3]?.status || 'not-started'"
              :title="milestones[3]?.name || 'Your Results'"
              :time="milestones[3]?.time || '~ 20 mins'"
              :description="milestones[3]?.description || ['Final career matches and insights', 'Personality report', 'Trait report']"
              :progress="milestones[3]?.progress || 0"
            />
          </div>
        </div>
        <div class="Journey--minimized">
            <div>
              <div class="Journey__head">Up Next</div>
              <div class="Journey__next-name">{{ nextMilestone?.name || 'Your personality archetype' }}</div>
            </div>
            <div class="Journey__right">
              <div class="Journey__toggle" @click="toggleMinimized">
                <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="chevron-up" class="svg-inline--fa fa-chevron-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                  <path fill="currentColor" d="M443.8 330.8C440.6 334.3 436.3 336 432 336c-3.891 0-7.781-1.406-10.86-4.25L224 149.8l-197.1 181.1c-6.5 6-16.64 5.625-22.61-.9062c-6-6.5-5.594-16.59 .8906-22.59l208-192c6.156-5.688 15.56-5.688 21.72 0l208 192C449.3 314.3 449.8 324.3 443.8 330.8z" />
                </svg>
              </div>
            </div>
          <div class="Journey__progress">
            <MinimizedProgress
              v-for="(milestone, index) in milestones"
              :key="index"
              :status="milestone.status"
              :progress="milestone.progress"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch, onMounted } from 'vue';
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
    const milestones = ref([
      {
        name: 'Your personality archetype',
        time: '~ 3 mins',
        status: 'not-started',
        progress: 0,
      },
      {
        name: 'Your career matches',
        time: '~ 3 mins',
        status: 'not-started',
        progress: 0,
      },
      {
        name: 'Your degree matches',
        time: '~ 1 min',
        status: 'not-started',
        progress: 0,
      },
      {
        name: 'Your Results',
        time: '~ 20 mins',
        description: ['Final career matches and insights', 'Personality report', 'Trait report'],
        status: 'not-started',
        progress: 0,
      },
    ]);

    // Update milestone progress based on props
    watch(() => props.progress, (newProgress) => {
      if (newProgress) {
        // Update Holland Codes milestone
        milestones.value[0].progress = newProgress.hollandCodes || 0;
        milestones.value[0].status = newProgress.hollandCodes === 100 ? 'complete' : 'in-progress';

        // Update Basic Interest milestone
        milestones.value[1].progress = newProgress.basicInterest || 0;
        milestones.value[1].status = newProgress.basicInterest === 100 ? 'complete' : 
          newProgress.hollandCodes === 100 ? 'in-progress' : 'not-started';

        // Update status of remaining milestones based on completion
        if (newProgress.completed) {
          milestones.value[2].status = 'in-progress';
          milestones.value[3].status = 'not-started';
        }
      }
    }, { immediate: true });

    const nextMilestone = computed(() => {
      return milestones.value.find(m => m.status !== 'complete') || milestones.value[0];
    });

    const toggleMinimized = () => {
      isMinimized.value = !isMinimized.value;
    };

    onMounted(() => {
      console.log('Progress:', props.progress);
    });

    return {
      isMinimized,
      milestones,
      nextMilestone,
      toggleMinimized,
      mdiChevronUp,
    };
  },
};
</script>

<style scoped>

</style>
