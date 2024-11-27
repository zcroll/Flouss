<template>
    <div class="archetype-test-container">
      <h1 class="test-title">Archetype Theme Test</h1>
      
      <div class="archetype-grid">
        <div v-for="archetype in archetypes" :key="archetype.slug" class="test-card">
          <Folder 
            :archetype="archetype"
            :user-id="1"
          />
          <div class="archetype-info">
            <span class="theme-label">Theme: {{ getThemeClass(archetype.slug) }}</span>
            <component :is="getArchetypeComponent(archetype.slug)" />
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { defineComponent, defineAsyncComponent } from 'vue';
  import Folder from '@/Components/Result/Folder.vue';
  
  export default defineComponent({
    components: {
      Folder,
    },
    data() {
      return {
        archetypes: [
          { slug: 'Advocat', description: 'Test description for Advocat' },
          { slug: 'Architect', description: 'Test description for Architect' },
          { slug: 'Captain', description: 'Test description for Captain' },
          { slug: 'Caregiver', description: 'Test description for Caregiver' },
          { slug: 'Composer', description: 'Test description for Composer' },
          { slug: 'Designer', description: 'Test description for Designer' },
          { slug: 'Enthusiast', description: 'Test description for Enthusiast' },
          { slug: 'Visionary', description: 'Test description for Visionary' },
          { slug: 'Producer', description: 'Test description for Producer' },
          { slug: 'Protector', description: 'Test description for Protector' },
          { slug: 'Groundbreaker', description: 'Test description for Groundbreaker' },
          { slug: 'Humanitarian', description: 'Test description for Humanitarian' },
          { slug: 'Inventor', description: 'Test description for Inventor' },
          { slug: 'Mentor', description: 'Test description for Mentor' },
          { slug: 'Philosopher', description: 'Test description for Philosopher' },
          { slug: 'Kingpin', description: 'Test description for Kingpin' },
          { slug: 'Mastermind', description: 'Test description for Mastermind' },
          { slug: 'Researcher', description: 'Test description for Researcher' },
          { slug: 'Technician', description: 'Test description for Technicien' },
        ]
      };
    },
    methods: {
      getThemeClass(slug) {
        const colorMap = {
          'Advocat': 'purple-theme',
          'Architect': 'blue-theme',
          'Captain': 'yellow-theme',
          'Caregiver': 'green-theme',
          'Composer': 'purple-theme',
          'Designer': 'blue-theme',
          'Enthusiast': 'yellow-theme',
          'Visionary': 'green-theme',
          'Producer': 'blue-theme',
          'Protector': 'purple-theme',
          'Groundbreaker': 'green-theme',
          'Humanitarian': 'green-theme',
          'Inventor': 'yellow-theme',
          'Mentor': 'blue-theme',
          'Philosopher': 'green-theme',
          'Kingpin': 'green-theme',
          'Mastermind': 'yellow-theme',
          'Researcher': 'purple-theme',
          'Technicien': 'yellow-theme',
        };
        return colorMap[slug] || 'green-theme';
      },
      getArchetypeComponent(slug) {
        return defineAsyncComponent(() =>
          import(`@/Components/Result/Archetype_Avatar/${slug}.vue`)
        );
      }
    }
  });
  </script>
  
  <style scoped>
  .archetype-test-container {
    padding: 2rem;
    background: #f5f5f5;
    min-height: 100vh;
  }
  
  .test-title {
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2rem;
    color: #333;
  }
  
  .archetype-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
    max-width: 1800px;
    margin: 0 auto;
  }
  
  .test-card {
    position: relative;
  }
  
  .archetype-info {
    text-align: center;
    margin-top: 1rem;
    padding: 0.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .theme-label {
    font-size: 0.9rem;
    color: #666;
  }
  </style>