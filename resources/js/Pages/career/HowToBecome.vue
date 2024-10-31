<template>
  <AppLayout>
    <div class="layout--sidebar__body__main">
      <!-- Breadcrumbs Navigation -->


      <StickySidebar
        :slug="occupation.slug"
        :title="occupation.name"
        :image="occupation.image"
        type="career"
        :salary="occupation.salary"
        :personality="occupation.personality || 'N/A'"
        :satisfaction="occupation.satisfaction || 'N/A'"
      >
        <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow ">
          <nav class="flex items-center space-x-2 text-sm text-gray-600">
            <Link :href="route('jobs.index')" class="hover:text-blue-600 transition-colors">{{ __('navigation.jobs') }}</Link>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <Link :href="route('career', { id: occupation.slug })" class="hover:text-blue-600 transition-colors">{{ __('stickybar.how_to_become') }}</Link>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-400">{{occupation.name}}</span>
          </nav>
   
          <section id="step-1" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0">

            <h2 class="custom-heading mb-8" itemProp="name">Steps to become a {{ occupation.name }}</h2>
            <div v-if="howToBecome.steps && howToBecome.steps.length > 0" class="space-y-4 mb-8">
              <ul class="list-custom">
                <li v-for="(step, index) in howToBecome.steps" :key="index" class="text-lg py-1 text-gray-700 leading-relaxed">
                  {{ step.title }}
                </li>
              </ul>
            </div>
            <div v-else class="space-y-4 mb-8">
              <p class="text-lg text-gray-700">No steps available for this occupation.</p>
            </div>
          </section>

          <section id="step-2" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0">

            <h2 class="custom-heading mb-8" itemProp="name">Degrees</h2>
            <div v-if="jobDegrees && jobDegrees.length > 0" class="space-y-4 mb-8">
              <div>
                <li v-for="(degree, index) in jobDegrees" :key="index" class="flex border-b border-gray-200 p-5 rounded-3xl items-center space-x-4 text-lg m-5 text-gray-700 leading-relaxed">
                  <img v-if="degree.image" :src="degree.image" :alt="degree.title" class="w-12 h-12 object-cover rounded-full">
                  <div>
                    <Link v-if="degree.slug" :href="route('degree.index', { slug: degree.slug })">{{ degree.title }}</Link>
                    <span v-else>{{ degree.title }}</span>
                  </div>
                </li>
              </div>
            </div>
            <div v-else class="space-y-4 mb-8">
              <p class="text-lg text-gray-700">No degrees available for this occupation.</p>
            </div>
          </section>

          <section id="step-3" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0">

            <h2 class="custom-heading mb-8" itemProp="name">Certifications</h2>
            <div v-if="howToBecome.certifications && howToBecome.certifications.length > 0" class="space-y-4 mb-8">
              <ul class="list-custom">
                <li v-for="(certification, index) in howToBecome.certifications" :key="index" class="text-lg py-1 text-gray-700 leading-relaxed">
                  {{ certification.title }}
                </li>
              </ul>
            </div>
            <div v-else class="space-y-4 mb-8">
              <p class="text-lg text-gray-700">No certifications available for this occupation.</p>
            </div>
          </section>

          <section v-if="howToBecome.associations && howToBecome.associations.length > 0" id="step-4" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0">
            <span class="Tag">Step 4</span>
            <h2 class="custom-heading mb-8" itemProp="name">Associations</h2>
            <div class="space-y-4 mb-8">
              <ul class="list-custom">
                <li v-for="(association, index) in howToBecome.associations" :key="index" class="text-lg py-1text-gray-700 leading-relaxed">
                  {{ association.title }}
                </li>
              </ul>
            </div>
          </section>
        </div>
      </StickySidebar>
    </div>
  </AppLayout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'
import { Link } from '@inertiajs/vue3'

export default defineComponent({
  components: {
    AppLayout,
    StickySidebar,
    Link,
  },
  props: {
    occupation: Object,
    howToBecome: Object,
    jobDegrees: Array,
    disableStepsLink: Boolean,
  }
})
</script>

<style scoped>
body {
  font-family: 'aktiv-grotesk-std', 'aktiv-grotesk', 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;
}

/* Custom list-style for larger bullets */
ul.list-custom {
  list-style: none;
  padding-left: 1.5rem;
}

ul.list-custom li {
  position: relative;
  padding-left: 2rem;
}

ul.list-custom li::before {
  content: '•';
  position: absolute;
  left: 0;
  top: -10px;
  font-size: 2.1rem;
  color: #a36fb2;
}

.trait-type {
  background-color: transparent;
  text-decoration: none;
  transition: color 0.2s ease-in-out, border-bottom 0.2s ease-in-out;
  border-bottom: 0px;
  color: rgb(36, 36, 36);
  font-weight: 300;
  font-family: aktiv-grotesk, "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>
