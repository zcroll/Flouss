<template>
  <!-- Add degree stage after basic interests -->
  <div class="stage degree" 
       :class="{ 
         'active': currentStage === 'degree',
         'locked': !progress.basicInterest?.completed 
       }"
  >
    <div class="stage-header">
      <span class="stage-name">Degree Assessment</span>
      <span class="stage-progress">
        {{ progress.degree?.percentage || 0 }}%
      </span>
    </div>
    <div class="progress-bar">
      <div 
        class="progress" 
        :style="{ width: `${progress.degree?.percentage || 0}%` }"
      ></div>
    </div>
  </div>
</template>

<script setup>
// Update progress watch
watch(() => props.progress, (newProgress) => {
  // ... existing holland_codes and basic_interests cases ...
  
  if (props.currentStage === 'degree') {
    testProgressStore.updateStageProgress('degree', {
      currentIndex: newProgress.degree?.currentIndex || 0,
      validResponses: newProgress.degree?.validResponses || 0,
      percentage: newProgress.degree?.percentage || 0,
      completed: newProgress.degree?.completed || false
    });
  }
}, { deep: true });
</script> 