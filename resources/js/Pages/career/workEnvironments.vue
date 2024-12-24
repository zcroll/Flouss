<template>
  <Head title="Work Environments" />
  <StickySidebar
    :slug="occupation.slug"
    :title="occupation.name"
    :image="occupation.image"
    type="career"
    :salary="occupation.salary"
    :personality="occupation.personality || 'N/A'"
    :satisfaction="occupation.satisfaction || 'N/A'"
    :id="occupation.id"
    :isFavorited="occupation.is_favorited"
  >
    <template #description>
      Explore the various work environments and settings where {{ occupation.name }}s typically work.
    </template>

    <!-- Main Content -->
    <div class="space-y-8">
      <Breadcrumbs 
        :items="[
          { name: 'Home', route: 'dashboard' },
          { name: 'Jobs', route: 'jobs.index' },
          { name: occupation.name, route: 'career', params: { id: occupation.id } },
          { name: 'Work Environments' }
        ]"
        class="mb-8"
      />

      <!-- Table of Contents -->
      <aside class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
          {{ __('career.in_this_article') }}
        </h2>
        
        <div class="space-y-4">
          <div v-for="(items, category, index) in groupedByCategory" 
               :key="category" 
               class="space-y-2">
            <button 
              class="w-full text-left flex items-center justify-between text-gray-900 font-medium hover:text-yellow-500 transition-colors"
              @click="toggleSection(index)"
            >
              <span>{{ category }}</span>
              <svg 
                class="w-5 h-5 transition-transform duration-200"
                :class="{ 'rotate-180': openSections[index] }"
                xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 20 20" 
                fill="currentColor"
              >
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            
            <transition name="slide">
              <ul v-show="openSections[index]" class="pl-4 space-y-2">
                <li v-for="item in items" :key="item.id">
                  <a 
                    :href="`#section-${item.id}`"
                    class="text-gray-600 hover:text-yellow-500 transition-colors"
                    @click.prevent="highlightAndScroll(item.id)"
                  >
                    {{ item.name }}
                  </a>
                </li>
              </ul>
            </transition>
          </div>
        </div>
      </aside>

      <!-- Work Environments List -->
      <div class="space-y-6">
        <div v-for="environment in workEnvironments" 
             :key="environment.id"
             :id="`section-${environment.id}`"
             class="bg-white/50 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-sm transition-all duration-300"
             :class="{ 'highlight': isHighlighted(environment.id) }">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">
            {{ environment.name }}
          </h3>
          
          <p class="text-gray-600 mb-6">{{ environment.description }}</p>
          
          <div v-if="environment.score" class="relative h-8 bg-gray-100 rounded-full overflow-hidden">
            <div 
              class="absolute inset-y-0 left-0 bg-gradient-to-r from-yellow-400 to-yellow-500 transition-all duration-1000"
              :style="{ width: `${environment.score}%` }"
            >
              <span class="absolute inset-0 flex items-center justify-end pr-4 text-white font-medium">
                {{ environment.score }}%
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <BackToTop />
  </StickySidebar>
</template>

<script setup>
import { ref, computed } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from '@inertiajs/vue3';
import BackToTop from "@/Components/helpers/BackToTop.vue";
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue';
import MainLayout from "@/Layouts/MainLayout.vue";

defineOptions({ layout: MainLayout });

const props = defineProps({
    occupation: {
        type: Object,
        required: true,
    },
    workEnvironments: {
        type: Array,
        required: true,
    },
});

// Add state for tracking highlighted section
const highlightedId = ref(null);

const groupedByCategory = computed(() => {
    return props.workEnvironments.reduce((acc, item) => {
        (acc[item.category] = acc[item.category] || []).push(item);
        return acc;
    }, {});
});

const openSections = ref(Object.keys(groupedByCategory.value).map(() => false));

const toggleSection = (index) => {
    openSections.value[index] = !openSections.value[index];
};

// Add isHighlighted method
const isHighlighted = (id) => highlightedId.value === id;

const highlightAndScroll = (id) => {
    const element = document.getElementById(`section-${id}`);
    if (element) {
        // Set the highlighted ID
        highlightedId.value = id;
        
        // Clear the highlight after animation
        setTimeout(() => {
            highlightedId.value = null;
        }, 2000);

        // Smooth scroll to the element
        element.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start',
            inline: 'nearest'
        });
    }
};
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease-out;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.highlight {
  @apply ring-2 ring-yellow-400 ring-offset-2;
  animation: glow 2s ease-out;
}

@keyframes glow {
  0% { 
    box-shadow: 0 0 0 rgba(250, 204, 21, 0.5);
    transform: scale(1);
  }
  50% { 
    box-shadow: 0 0 20px rgba(250, 204, 21, 0.5);
    transform: scale(1.02);
  }
  100% { 
    box-shadow: 0 0 0 rgba(250, 204, 21, 0.5);
    transform: scale(1);
  }
}

/* Add smooth scrolling to the whole page */
html {
  scroll-behavior: smooth;
}

/* Add fade-in animation for sections */
section {
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
