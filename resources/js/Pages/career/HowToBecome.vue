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
        :id="occupation.id"
        :isFavorited="occupation.is_favorited"
      >
        <div class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white shadow ">
          <Breadcrumbs
            :items="[
              { name: 'Home', route: 'dashboard' },
              { name: 'Jobs', route: 'jobs.index' },
              { name: occupation.name, route: 'career', params: { id: occupation.id } },
              { name: 'How to Become' }
            ]"
          />

          <section id="step-1" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0">

            <h2 class="heading-type pb-5" itemProp="name">{{__('career.steps')}}{{ occupation.name }}</h2>
            <div v-if="howToBecome.steps && howToBecome.steps.length > 0" class="space-y-4 mb-8">
              <ul class="list-custom">
                <li v-for="(step, index) in howToBecome.steps" :key="index" class="text-l text-gray-700 pt-3">
                  {{ step.title }}
                </li>
              </ul>
            </div>
            <div v-else class="space-y-4 mb-8">
              <p class="text-lg text-gray-700">{{ __('career.no_steps') }}</p>
            </div>
          </section>

          <section id="step-2" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0">

            <h2 class="heading-type pb-5" itemProp="name">{{ __('career.degrees') }}</h2>
            <div v-if="jobDegrees && jobDegrees.length > 0" class="space-y-4 mb-8">
              <div>
                <ul class="space-y-4">
                  <li v-for="(degree, index) in jobDegrees" :key="index" class="flex items-center space-x-4 p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                    <img v-if="degree.image" :src="degree.image" :alt="degree.title" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                    <div class="flex-1">
                      <Link
                        v-if="degree.slug"
                        :href="route('degree.index', { slug: degree.slug })"
                        class="text-lg font-medium text-gray-900transition-colors duration-200"
                      >
                        {{ degree.title }}
                      </Link>
                      <span v-else class="text-lg font-medium text-gray-900">{{ degree.title }}</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </li>
                </ul>

              </div>
            </div>
            <div v-else class="space-y-4 mb-8">
              <p class="text-lg text-gray-700">{{ __('career.no_degrees') }}</p>
            </div>
          </section>



          <section v-if="howToBecome.associations && howToBecome.associations.length > 0" id="step-4" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0">
            <span class="Tag">Step 4</span>
            <h2 class="heading-type pb-5" itemProp="name">{{ __('career.associations') }}</h2>
            <div class="space-y-4 mb-8">
              <ul class="list-custom">
                <li v-for="(association, index) in howToBecome.associations" :key="index" class="text-lg py-1text-gray-700 leading-relaxed">
                  {{ association.title }}
                </li>
              </ul>
            </div>
          </section>
            <BackToTop />
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
import BackToTop from "@/Components/helpers/BackToTop.vue"
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue'

export default defineComponent({
  components: {
    BackToTop,
    AppLayout,
    StickySidebar,
    Link,
    Breadcrumbs,
  },
  props: {
    occupation: {
      type: Object,
      required: true
    },
    howToBecome: {
      type: Object,
      required: true
    },
    jobDegrees: {
      type: Array,
      required: true
    },
    disableStepsLink: {
      type: Boolean,
      required: true
    }
  }
})
</script>

<style scoped>
@import '/public/css/items_description.css';
</style>
