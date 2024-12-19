<template>
    <div class="report-card" :class="colorScheme">
        <div class="report-content-wrapper">
            <!-- Desktop View -->
        <div class="report-flex" v-if="!isMobile">
          <div class="text-content" style="display: flex; justify-content: center; align-items: center;">
            <div class="text-wrapper" style="text-align: center; display: flex; flex-direction: column; align-items: center;">
              <div class="avatar" style="display: flex; justify-content: center;">
                <ArchetypeAvatar
                  v-if="archetypeDiscovery && archetypeDiscovery.slug"
                  :archetype="archetypeDiscovery.slug"
                  style="width: 100%;"
                />
                <ArchetypeAvatar
                  v-else
                  archetype="default"
                  style="width: 100%;"
                />
              </div>
              
              <div class="text-button-container" style="display: flex; flex-direction: column; align-items: center;">
                <h1 class="archetype-name" :data-text="archetypeDiscovery.slug">{{ archetypeDiscovery.slug }}</h1>
              
                <Link
                  class="view-report-button"
                  :href="`/results/${userId}/personality`"
                  tabindex="0"
                  :aria-label="__('results.click_to_view_trait_report')"
                  style="display: flex; align-items: center; justify-content: center;"
                >
                  <span>{{ __('results.view_report') }}</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="arrow-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </Link>
              </div>

            </div>
        </div>

          <div v-if="!isMobile" class="avatar-section">
            
            <div class="">
              <div class="avatars-row">
                <div
                  v-for="avatar in avatars"
                  :key="avatar.slug"
                  class="avatar-thumbnail"
                  :class="{ 'avatar-active': avatar.slug === archetypeDiscovery.slug }"
                  @click="selectAvatar(avatar.slug)"
                >
                  <div class="avatar-thumbnail-wrapper">
                    <ArchetypeAvatar
                      :archetype="avatar.slug"
                      class="avatar-thumbnail-image"
                    />
                  </div>
                  <span class="avatar-name"></span>
                </div>
              </div>
            </div>
              <div class="avatar-glow"></div>
          </div>
        </div>


            <!-- Mobile View -->
            <div class="report-flex" v-if="isMobile" style="flex-direction: row; justify-content: space-between; align-items: center;">
                <div class="text-content" style="flex: 1; text-align: center;">
                    <h1 class="archetype-name" :data-text="archetypeDiscovery.slug">{{ archetypeDiscovery.slug }}</h1>
                    <Link
                        class="view-report-button"
                        :href="`/results/${userId}/personality`"
                        tabindex="0"
                        :aria-label="__('results.click_to_view_trait_report')"
                        style="display: flex; flex-direction: column; align-items: center;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="arrow-icon" viewBox="0 0 20 20" fill="currentColor" style="width: 20px; height: 20px;">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        
                    </Link>
                  
                </div>
                <div class="avatar-section" style="flex: 1; display: flex; justify-content: center;">
                    <div class="avatar">
                        <ArchetypeAvatar
                          :archetype="archetypeDiscovery.slug"
                          style="width: 100%;"
                        />
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/vue3';
import __ from '@/lang';
import ArchetypeAvatar from './Archetype_Avatar/ArchetypeAvatar.vue';

export default defineComponent({
  name: 'Folder',
  components: {
    Link,
    ArchetypeAvatar
  },
  props: {
    userId: {
      type: String,
      required: true
    },
    archetypeDiscovery: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      avatars: [
        { slug: 'Advocat' },
        { slug: 'Architect' },
        { slug: 'Captain' },
        { slug: 'Caregiver' },
        { slug: 'Composer' },
        { slug: 'Designer' },
        { slug: 'Enthusiast' },
        { slug: 'Visionary' },
        { slug: 'Producer' },
        { slug: 'Protector' },
        { slug: 'Groundbreaker' },
        { slug: 'Humanitarian' },
        { slug: 'Inventor' },
        { slug: 'Mentor' },
        { slug: 'Creator' },
        { slug: 'Philosopher' },
        { slug: 'Kingpin' },
        { slug: 'Mastermind' },
        { slug: 'Researcher' },
        { slug: 'Technician' },
        { slug: 'Explorer' },
        { slug: 'Maverick' },
        { slug: 'Guardian' },
        { slug: 'Strategist' },
        { slug: 'Anchor' },
        { slug: 'Supporter' },
        { slug: 'Luminary' },
        { slug: 'Scholar' },
        { slug: 'Builder' },
        { slug: 'Innovator' },
        { slug: 'default' },
      ],
      isMobile: window.innerWidth <= 768
    };
  },
  computed: {
    colorScheme() {
      const colorMap = {
        // Blue theme
        'Caregiver': 'blue-theme',
        'Designer': 'blue-theme', 
        'Guardian': 'blue-theme',
        'Mentor': 'blue-theme',
        'Producer': 'blue-theme',
        'Protector': 'blue-theme',
        'Scholar': 'blue-theme',
        'Inventor': 'blue-theme',

        // Green theme
        'Advocat': 'green-theme',
        'Anchor': 'green-theme',
        'Captain': 'green-theme',
        'Composer': 'green-theme',
        'Humanitarian': 'green-theme',
        'Kingpin': 'green-theme',
        'Philosopher': 'green-theme',
        'Supporter': 'green-theme',
        'Explorer': 'green-theme',

        // Purple theme
        'Architect': 'purple-theme',
        'Groundbreaker': 'purple-theme',
        'Protector': 'purple-theme',
        'Researcher': 'purple-theme',
        'Strategist': 'purple-theme',
        'Mastermind': 'purple-theme',
        'Maverick': 'purple-theme',
        'Visionary': 'purple-theme',

        // Yellow theme
        'Creator': 'yellow-theme',
        'Builder': 'yellow-theme',
        'Enthusiast': 'yellow-theme',
        'Innovator': 'yellow-theme',
        'Luminary': 'yellow-theme',
        'Technician': 'yellow-theme',

      };
      if (!this.archetypeDiscovery || !this.archetypeDiscovery.slug) {
        return 'green-theme';
      }
      return colorMap[this.archetypeDiscovery.slug];
    }
  },
  methods: {
    selectAvatar(slug) {
      console.log(`Selected avatar: ${slug}`);
    }
  },
  mounted() {
    window.addEventListener('resize', () => {
      this.isMobile = window.innerWidth <= 768;
    });
  },
  beforeUnmount() {
    window.removeEventListener('resize', () => {
      this.isMobile = window.innerWidth <= 768;
    });
  }
});
</script>

<style>
@import 'public/css/folder.css';
@import 'public/css/career_list.css';
</style>


