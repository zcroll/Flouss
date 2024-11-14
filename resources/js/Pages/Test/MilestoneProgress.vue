<template>
  <div :class="`Journey__milestone Journey__milestone--${status}`">
      <div class="Journey__milestone__map__marker">
        <div v-if="status === 'complete'" class="Journey__milestone__map__marker__icon fas fa-check"></div>
      </div>
      <div class="Journey__milestone__map__trail">
        <div 
          class="Journey__milestone__map__trail__progress" 
          :style="{
            transform: `translateY(${-100 + computedProgress}%)`,
            opacity: status === 'not-started' ? '0.3' : 
                    status === 'in-progress' ? '0.7' :
                    status === 'complete' ? '1' : '0.3'
          }"
        ></div>
      </div>
  </div>
</template>


<script>
export default {
  name: 'MilestoneProgress',
  props: {
    status: {
      type: String,
      required: true,
      validator: (value) => ['complete', 'in-progress', 'not-started'].includes(value),
    },
    progress: {
      type: String,
      required: true,
      default: '0%'
    }
  },
  computed: {
    computedProgress() {
      // Remove % and convert to number
      return parseInt(this.progress.replace('%', ''));
    }
  }
};
</script>

<style scoped>
@import '/resources/css/assessment.css';
@import '/resources/css/global.css';
</style>