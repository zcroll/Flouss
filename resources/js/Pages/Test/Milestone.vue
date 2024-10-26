<template>
  <div :class="`Journey__milestone Journey__milestone--${status}`">
    <div class="Journey__milestone__map">
      <MilestoneProgress 
        :status="status" 
        :progress="progress + '%'" 
      />
    </div>
    <div class="Journey__milestone__body">
      <h3 class="Journey__milestone__name">{{ title }}</h3>
      <div class="Journey__milestone__time" v-if="time">
        <div class="Journey__milestone__time__text">{{ time }}</div>
      </div>
      <div v-if="description">
        <p class="Journey__milestone__description" v-for="(desc, index) in description" :key="index">{{ desc }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import MilestoneProgress from '@/Pages/Test/MilestoneProgress.vue';

export default {
  name: 'Milestone',
  components: {
    MilestoneProgress
  },
  props: {
    status: {
      type: String,
      required: true,
      validator: (value) => ['complete', 'in-progress', 'not-started'].includes(value),
    },
    title: {
      type: String,
      required: true,
    },
    time: {
      type: String,
    },
    description: {
      type: Array,
    },
    progress: {
      type: Number,
      required: true,
      default: 0,
    },
  },
  computed: {
    milestoneData() {
      return {
        status: this.status,
        progress: this.progress + '%'
      };
    }
  }
};
</script>

<style scoped>
.Journey__milestone__map {
  position: relative;
  width: 24px;
  margin-right: 16px;
}

.Journey__milestone__map__trail__progress {
  transition: transform 0.5s ease-in-out;
}
</style>
