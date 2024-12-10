<script setup>
import { ref, inject, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  as: {
    type: String,
    default: 'li'
  },
  href: {
    type: String,
    required: true
  },
  active: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['activated']);
const navGroup = inject('navGroup');
const itemRef = ref(null);

onMounted(() => {
  if (props.active) {
    navGroup.setActiveItem(itemRef.value);
  }
});

watch(() => props.active, (isActive) => {
  if (isActive) {
    navGroup.setActiveItem(itemRef.value);
  }
});

const navigate = async (e) => {
  e.preventDefault();
  navGroup.setActiveItem(itemRef.value);
  await new Promise(resolve => setTimeout(resolve, 300)); // Wait for animation
  emit('activated');
  router.visit(props.href);
};
</script>

<template>
  <component :is="as">
    <a
      ref="itemRef"
      :href="href"
      :class="[
        'nav-link',
        active 
          ? 'text-white/90' 
          : 'text-white/90'
      ]"
      @click="navigate"
    >
      <slot />
    </a>
  </component>
</template>

<style scoped>
.nav-link {
  @apply inline-block transition-all duration-300;
  font-family: 'Satoshi', sans-serif;
  font-weight: 500;
  letter-spacing: -0.01em;
}

/* Default (1280px and up) */
.nav-link {
  font-size: 0.875rem; /* 14px */
  padding: 0.375rem 1rem;
}

/* 1065px to 1279px */
@media (min-width: 1065px) and (max-width: 1279px) {
  .nav-link {
    font-size: 0.75rem; /* 12px */
    padding: 0.375rem 0.75rem;
  }
}

/* 768px to 1064px */
@media (min-width: 768px) and (max-width: 1064px) {
  .nav-link {
    font-size: 0.6875rem; /* 11px */
    padding: 0.375rem 0.625rem;
  }
}

/* 640px to 767px */
@media (min-width: 640px) and (max-width: 767px) {
  .nav-link {
    font-size: 0.625rem; /* 10px */
    padding: 0.375rem 0.5rem;
  }
}
</style> 