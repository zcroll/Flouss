<template>
  <div :class="`Journey__milestone Journey__milestone--${status}`">
    <div class="Journey__milestone__trail">
      <div 
        class="Journey__milestone__trail__progress" 
        :style="{
          transform: `translateX(${-100 + progress}%)`,
          opacity: getOpacity
        }"
      ></div>
    </div>
    <div class="Journey__milestone__marker"></div>
  </div>
</template>

<script>
export default {
  name: 'MinimizedProgress',
  props: {
    status: {
      type: String,
      required: true,
      validator: (value) => ['complete', 'in-progress', 'not-started'].includes(value),
    },
    progress: {
      type: Number,
      required: true,
      default: 0
    }
  },
  computed: {
    getOpacity() {
      return this.status === 'not-started' ? '0.3' : 
             this.status === 'in-progress' ? '0.7' : '1';
    }
  }
};
</script>

<style scoped>
@import '/resources/css/assessment.css';
@import '/resources/css/global.css';
</style>