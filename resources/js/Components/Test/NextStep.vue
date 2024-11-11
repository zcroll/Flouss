<template>
  <div class="Assessment__Item--forward-enter-done">
    <div 
      class="Assessment__Item Assessment__Interstitial Assessment__Interstitial--active"
      data-item-index="0"
      data-itemset-index="1" 
      id="rate-the-following-careers-324"
      aria-label="Rate the following careers. Press Enter to begin."
      tabindex="1"
      aria-atomic="true"
      aria-live="assertive"
    >
      <div>
        <h4 aria-hidden="true">Next Step</h4>
        <h3 aria-hidden="true">Rate the following interests</h3>
        <h5 aria-hidden="true">
          We will be showing you some interests to help us understand what jobs and careers you might enjoy.
        </h5>
        <button 
          id="interstitial-enter-button"
          class="alans-butt--grey"
          @click="$emit('continue')"
        >
          Start rating interests
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NextStep',
  emits: ['continue']
}
</script>


