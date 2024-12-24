<template>
  <div 
    class="career-card group"
    :aria-labelledby="`career-${job.slug}`" 
    @click="$emit('select', job)"
  >
    <!-- Image Container -->
    <div class="image-container">
      <img 
        :src="job.image" 
        :alt="job.career" 
        class="career-image"
        role="presentation" 
        aria-hidden="true" 
      />
    </div>

    <!-- Content -->
    <div class="content-wrap">
      <div :id="`career-${job.slug}`" class="career-name">
        <Link 
          :href="`/career/${job.slug}`" 
          class="career-link"
          tabindex="0"
          aria-label="Click here to view your career details."
        >
          {{ job.name }}
        </Link>
      </div>
    </div>

    <!-- Arrow Icon -->
    <div class="arrow-container">
      <svg 
        class="arrow-icon"
        xmlns="http://www.w3.org/2000/svg" 
        viewBox="0 0 24 24" 
        fill="none" 
        stroke="currentColor" 
        stroke-width="2" 
        stroke-linecap="round" 
        stroke-linejoin="round"
      >
        <path d="M9 18l6-6-6-6" />
      </svg>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  job: {
    type: Object,
    required: true
  }
});

defineEmits(['select']);
</script>

<style scoped>
.career-card {
  display: flex;
  align-items: center;
  padding: 1rem;
  background: rgba(255, 255, 255, 0.7);
  border: 1px solid rgba(0, 0, 0, 0.1);
  border-radius: 1rem;
  cursor: pointer;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  position: relative;
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  opacity: 0;
  transform: translateY(20px);
  animation: slideIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

@keyframes slideIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Add delay for each card */
.career-card:nth-child(1) { animation-delay: 0.1s; }
.career-card:nth-child(2) { animation-delay: 0.2s; }
.career-card:nth-child(3) { animation-delay: 0.3s; }
.career-card:nth-child(4) { animation-delay: 0.4s; }
.career-card:nth-child(5) { animation-delay: 0.5s; }
/* ... add more if needed */

.career-card:hover {
  background: rgba(255, 255, 255, 0.9);
  transform: translateY(-2px);
  border-color: rgba(250, 204, 21, 0.3);
  box-shadow: 
    0 8px 20px rgba(0, 0, 0, 0.1),
    0 0 0 1px rgba(250, 204, 21, 0.3);
}

.image-container {
  width: 60px;
  height: 60px;
  border-radius: 0.75rem;
  overflow: hidden;
  flex-shrink: 0;
  background: rgba(250, 204, 21, 0.1);
  border: 1px solid rgba(250, 204, 21, 0.2);
}

.career-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.career-card:hover .career-image {
  transform: scale(1.1);
}

.content-wrap {
  flex: 1;
  padding: 0 1.5rem;
  min-width: 0; /* Prevents text overflow issues */
}

.career-name {
  font-size: 1rem;
  font-weight: 500;
  color: #374151;
}

.career-link {
  display: block;
  text-decoration: none;
  color: inherit;
  transition: color 0.2s ease;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.career-link:hover {
  color: #facc15;
}

.arrow-container {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.arrow-icon {
  width: 20px;
  height: 20px;
  color: #9ca3af;
  transition: all 0.3s ease;
}

.career-card:hover .arrow-icon {
  color: #facc15;
  transform: translateX(4px);
}

/* Focus styles for accessibility */
.career-card:focus-within {
  outline: 2px solid #facc15;
  outline-offset: 2px;
}

/* Add shine effect */
.career-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 50%;
  height: 100%;
  background: linear-gradient(
    120deg,
    transparent,
    rgba(255, 255, 255, 0.3),
    transparent
  );
  transition: 0.5s;
}

.career-card:hover::after {
  left: 100%;
}
</style> 