import { ref, onMounted, onBeforeUnmount } from 'vue';

export function useScreenSize() {
  const isSmallScreen = ref(false);

  const updateScreenSize = () => {
    isSmallScreen.value = window.innerWidth < 1024;
  };

  // Debounced version of the update function
  const debouncedUpdate = (() => {
    let timeoutId;
    return () => {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(updateScreenSize, 100);
    };
  })();

  onMounted(() => {
    updateScreenSize();
    window.addEventListener('resize', debouncedUpdate);
  });

  onBeforeUnmount(() => {
    window.removeEventListener('resize', debouncedUpdate);
  });

  return {
    isSmallScreen
  };
} 