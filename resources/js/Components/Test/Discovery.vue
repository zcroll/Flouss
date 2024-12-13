<template>
  <section class="Discovery" aria-live="polite" aria-atomic="true" aria-relevant="text" role="presentation">
    <div class="DiscoveryButton__inner">
      <div class="DiscoveryConfetti" id="dicovery-confetti" data-testid="dicovery-confetti" aria-hidden="true">
        <div class="DiscoveryConfetti__inner">
          <div class="DiscoveryConfetti__content"></div>
        </div>
      </div>
      <button
        class="DiscoveryButton"
        @click="handleShowResults"
        v-if="!showArchetype"
        :style="isHollandCodes ? { backgroundImage: `url(${archetypeDiscovery.image_url})` } : { backgroundImage: 'url(https://res.cloudinary.com/hnpb47ejt/image/upload/v1561590833/flch4qwruatfzibnlgs1.svg)' }"
        data-testid="discovery-button"
        type="button"
        tabindex="0"
        aria-label="Click here for your discovery!"
      >
        <div class="DiscoveryButton__content">
          <div class="DiscoveryButton__content__inner">
            <p class="DiscoveryButton__content__text">
              {{ isHollandCodes ? 'See your personality type!' : 'See your job matches!' }}
            </p>
          </div>
        </div>
      </button>
    </div>
    <div id="discovery-dialog-container" class="yodel dialog-container Dialog-container">
      <Archetype 
        v-if="showArchetype && isHollandCodes" 
        :archetype-discovery="archetypeDiscovery" 
        @close="handleClose" 
      />
      <MatchResult 
        v-if="showArchetype && (isBasicInterests || isDegree)" 
        :job-matching="jobMatching" 
        :degree-matching="degreeMatching"
        @close="handleClose" 
      />
    </div>
  </section>
</template>

<script setup>
import Archetype from '../helpers/Archetype.vue';
import MatchResult from './MatchResult.vue';
import { ref, computed } from 'vue';
import { useTestStageStore } from '@/stores/testStageStore';
import { useBasicInterestStore } from '@/stores/basicInterestStore';

const testStageStore = useTestStageStore();
const basicInterestStore = useBasicInterestStore();

const props = defineProps({
  archetypeDiscovery: {
    type: Object,
    default: null
  },
  jobMatching: {
    type: Object,
    default: null
  },
  degreeMatching: {
    type: Object,
    default: null
  },
  currentStage: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['close']);

const showArchetype = ref(false);

const isHollandCodes = computed(() => props.currentStage === 'holland_codes');
const isBasicInterests = computed(() => props.currentStage === 'basic_interests');
const isDegree = computed(() => props.currentStage === 'degree');

const handleShowResults = () => {
  showArchetype.value = true;
};

const handleClose = () => {
  showArchetype.value = false;
  emit('close');
};
</script>

<style scoped>
</style>