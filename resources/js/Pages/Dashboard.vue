<template>
  <AppLayout title="dashboard" >
    <div class="min-h-screen">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <WelcomeBanner 
          :username="$page.props.auth.user.name"
          :has-result="hasResult"
        />
        
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <FavoritesList
            :title="__('dashboard.liked_jobs')"
            :empty-message="__('dashboard.no_liked_jobs')"
            :browse-message="__('dashboard.discover_jobs')"
            :items="favoriteJobs"
            type="career"
            browse-link="/jobs"
            @remove="removeFavorite"
          />
          
          <FavoritesList
            :title="__('dashboard.liked_formations')"
            :empty-message="__('dashboard.no_liked_formations')"
            :browse-message="__('dashboard.discover_formations')"
            :items="favoriteDegrees"
            type="degree"
            browse-link="/degrees"
            @remove="removeFavorite"
          />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <AssessmentResults 
            :hasResult="hasResult"
            :archetype="archetype"
            :topTraits="topTraits"
            :parsed-scales="parsedScales"
            class="lg:col-span-2"
          />
          
          <AIChat
            :predefined-questions="predefinedQuestions"
            :initial-messages="chatHistory"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import WelcomeBanner from '@/Components/Dashboard/WelcomeBanner.vue';
import FavoritesList from '@/Components/Dashboard/FavoritesList.vue';
import AssessmentResults from '@/Components/Dashboard/AssessmentResults.vue';
import AIChat from '@/Components/Dashboard/AIChat.vue';

const props = defineProps({
  hasResult: Boolean,
  favoriteJobs: Array,
  favoriteDegrees: Array,
  predefinedQuestions: Array,
  archetype: Object,
  chatHistory: Array,
  topTraits: Object,
  parsedScales: Object
  
});

const removeFavorite = (id) => {
  router.delete(`/favorites/${id}`);
};
</script>