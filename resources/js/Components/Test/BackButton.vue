<template>
  <button class="BackButton" :disabled="!canGoBack || loading || disabled" @click="handleBack"
    data-testid="back-button">
    <svg aria-hidden="true" focusable="false" class="svg-inline--fa fa-arrow-left BackButton__icon" role="img"
      xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
      <path fill="currentColor"
        d="M447.1 256C447.1 273.7 433.7 288 416 288H109.3l105.4 105.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H416C433.7 224 447.1 238.3 447.1 256z">
      </path>
    </svg>
    <span class="BackButton__text">Back</span>

    <!-- Loading spinner -->
    <div v-if="loading" class="BackButton__spinner"></div>
  </button>
</template>

<script setup>
import { computed } from 'vue';
import { useHollandCodeStore } from '@/stores/hollandCodeStore';

const hollandCodeStore = useHollandCodeStore();

const props = defineProps({
  loading: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['go-back']);

const canGoBack = computed(() => hollandCodeStore.canGoBack);

const handleBack = async () => {
  if (!canGoBack.value || props.loading || props.disabled) {
    return;
  }
  emit('go-back');
};
</script>

<style scoped>
.BackButton {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border: none;
  background: transparent;
  color: #666;
  cursor: pointer;
  transition: color 0.2s;
}

.BackButton:hover:not(:disabled) {
  color: #333;
}

.BackButton:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.BackButton__icon {
  width: 16px;
  height: 16px;
}

.BackButton__text {
  font-size: 14px;
  font-weight: 500;
}

/* Add loading spinner styles */
.BackButton__spinner {
  width: 16px;
  height: 16px;
  border: 2px solid #666;
  border-top: 2px solid transparent;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-left: 8px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>