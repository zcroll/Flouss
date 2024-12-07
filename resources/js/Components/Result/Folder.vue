
<template>
    <div class="report-card" :class="colorScheme">
        <div class="report-content-wrapper">
            <!-- Desktop View -->
        <div class="report-flex" v-if="!isMobile">
          <div class="text-content">
            <div class="text-wrapper" style="text-align: center;">
              <div class="avatar">
                <component 
                  v-if="archetype && archetype.slug"
                  :is="getAvatarComponent('Architect')"
                  style="width: 100%;"
                />
                <component 
                  v-else
                  :is="getAvatarComponent('Architect')"
                  style="width: 100%;"
                />
              </div>
              
              <div class="text-button-container">
              <h1 class="archetype-name" :data-text="archetype.slug">Architect</h1>
              
                <Link
                  class="view-report-button"
                  :href="`/results/${userId}/personality`"
                  tabindex="0"
                  :aria-label="__('results.click_to_view_trait_report')"
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
                  :class="{ 'avatar-active': avatar.slug === archetype.slug }"
                  @click="selectAvatar(avatar.slug)"
                >
                  <div class="avatar-thumbnail-wrapper">
                    <component 
                      :is="getAvatarComponent(avatar.slug)"
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
                    <h1 class="archetype-name" :data-text="archetype.slug">{{ archetype.slug }}</h1>
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
                        <component 
                            :is="getAvatarComponent(archetype.slug)"
                            style="width: 100%;"
                        />
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { defineComponent, defineAsyncComponent } from 'vue';
import { Link } from '@inertiajs/vue3';
import __ from '@/lang';

export default defineComponent({
  name: 'Folder',
  components: {
    Link,
  },
  props: {
    userId: {
      type: String,
      required: true
    },
    archetype: {
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
        { slug: 'Supporter' },
        { slug: 'Maverick' },
        { slug: 'Guardian' },
        { slug: 'Strategist' },
        { slug: 'Anchor' },
        { slug: 'Luminary' },
        { slug: 'Scholar' },
        { slug: 'Builder' },
        { slug: 'Innovator' },
      ],
      isMobile: window.innerWidth <= 768
    };
  },
  computed: {
    colorScheme() {
      const colorMap = {
        'Advocat': 'green-theme',
        'Architect': 'purple-theme',
        'Captain': 'green-theme',
        'Caregiver': 'blue-theme',
        'Composer': 'green-theme',
        'Designer': 'blue-theme',
        'Enthusiast': 'yellow-theme',
        'Visionary': 'yellow-theme',
        'Producer': 'blue-theme',
        'Protector': 'purple-theme',
        'Groundbreaker': 'purple-theme',
        'Humanitarian': 'green-theme',
        'Inventor': 'yellow-theme',
        'Mentor': 'blue-theme',
        'Philosopher': 'green-theme',
        'Kingpin': 'green-theme',
        'Mastermind': 'yellow-theme',
        'Researcher': 'purple-theme',
        'Protector': 'blue-theme',
        'Technician': 'yellow-theme',
        'Creator': 'yellow-theme',
        'Explorer': 'purple-theme',
        'Supporter': 'green-theme',
        'Maverick': 'yellow-theme',
        'Guardian': 'blue-theme',
        'Strategist': 'purple-theme',
        'Anchor': 'green-theme',
        'Luminary': 'yellow-theme',
        'Scholar': 'blue-theme',
        'Builder': 'purple-theme',
        'Innovator': 'yellow-theme',
      };
      return colorMap[this.archetype.slug] || 'green-theme'; // default to green
    }
  },
  methods: {
    getAvatarComponent(slug) {
      return defineAsyncComponent(() => 
        import(`./Archetype_Avatar/${slug}.vue`)
          .catch(() => import('./Archetype_Avatar/Default.vue'))
      );
    },
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


