<template>
  <AppLayout>
    <div class="container mx-auto">
      
      <!-- Breadcrumbs Navigation -->
      <div class="breadcrumbs-nav breadcrumbs-nav--tests">
        <Link :href="route('jobs.index')">{{ __('navigation.jobs') }}</Link>
        <div class="small-icon arrow-breadcrumbs"></div>
        <Link :href="route('career', { id: occupation.slug })">{{ __('stickybar.how_to_become') }}</Link>
        <div class="small-icon arrow-breadcrumbs"></div>
        <span>{{occupation.name}}</span>
      </div>

      <StickySidebar
        :slug="occupation.slug"
        :title="occupation.name"
        :image="occupation.image"
        type="career"
        :salary="occupation.salary"
        :personality="occupation.personality || 'N/A'"
        :satisfaction="occupation.satisfaction || 'N/A'"
        :job-steps="occupation.jobSteps"
        :disableStepsLink="disableStepsLink"
      >
        
        <div v-if="!occupation || !howToBecome || !jobDegrees" class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl">
          <p class="text-lg text-gray-700">No data available for this occupation.</p>
        </div>
        
        <div v-else class="w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl">
          <h2 class="custom-heading mb-8">{{ howToBecome.title }}</h2>
          <section id="step-1" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0" style="box-sizing: border-box; display: block; transition: box-shadow 0.33s, transform 0.33s; background-color: rgb(255, 255, 255); padding: 30px; border-radius: 19px; margin-top: 40px; box-shadow: rgba(0, 0, 0, 0.12) 0px 12px 48px 0px;">
              <span class="Tag" style="box-sizing: border-box; border-radius: 6.5px; display: inline-block; color: rgb(102, 57, 116); background-color: rgb(229, 224, 234); font-weight: 400; letter-spacing: -0.15px; line-height: 1.2; margin-right: 10px; padding: 8px 12px; font-size: 16px; font-family: 'aktiv-grotesk', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                Step 1
              </span>
              <h2 class="custom-heading mb-8" itemProp="name" style="box-sizing: border-box; line-height: 1.16; letter-spacing: -0.7px; font-weight: 300; font-size: 1.55em; margin: 12px 0px 27px; padding: 0px; margin-bottom: 27px; padding-top: 0px;">
               Steps to become a {{ occupation.name }}
              </h2>
              <div v-if="howToBecome.steps && howToBecome.steps.length > 0" class="space-y-4 mb-8">
                <ul class="list-custom">
                  <li v-for="(step, index) in howToBecome.steps" :key="index" class="text-lg text-gray-700 leading-relaxed">
                    {{ step.title }}
                  </li>
                </ul>
              </div>
              <div v-else class="space-y-4 mb-8">
                <p class="text-lg text-gray-700">No steps available for this occupation.</p>
              </div>
            </section>
            <section id="step-2" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0" style="box-sizing: border-box; display: block; transition: box-shadow 0.33s, transform 0.33s; background-color: rgb(255, 255, 255); padding: 30px; border-radius: 19px; margin-top: 40px; box-shadow: rgba(0, 0, 0, 0.12) 0px 12px 48px 0px;">
              <span class="Tag" style="box-sizing: border-box; border-radius: 6.5px; display: inline-block; color: rgb(102, 57, 116); background-color: rgb(229, 224, 234); font-weight: 400; letter-spacing: -0.15px; line-height: 1.2; margin-right: 10px; padding: 8px 12px; font-size: 16px; font-family: 'aktiv-grotesk', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                Step 2
              </span>
              <h2 class="custom-heading mb-8" itemProp="name" style="box-sizing: border-box; line-height: 1.16; letter-spacing: -0.7px; font-weight: 300; font-size: 1.55em; margin: 12px 0px 27px; padding: 0px; margin-bottom: 27px; padding-top: 0px;">
                Degrees
              </h2>
              <div v-if="jobDegrees && jobDegrees.length > 0" class="space-y-4 mb-8">
                <ul class="list-custom">
                  <li v-for="(degree, index) in jobDegrees" :key="index" class="flex items-center space-x-4 text-lg text-gray-700 leading-relaxed">
                    <img v-if="degree.image" :src="degree.image" :alt="degree.title" class="w-12 h-12 object-cover rounded-full">
                    <div>
                      <Link v-if="degree.slug" :href="route('degree.index', { slug: degree.slug })">{{ degree.title }}</Link>
                      <span v-else>{{ degree.title }}</span>
                    </div>
                  </li>
                </ul>
              </div>
              <div v-else class="space-y-4 mb-8">
                <p class="text-lg text-gray-700">No degrees available for this occupation.</p>
              </div>
            </section>

            <section id="step-3" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0" style="box-sizing: border-box; display: block; transition: box-shadow 0.33s, transform 0.33s; background-color: rgb(255, 255, 255); padding: 30px; border-radius: 19px; margin-top: 40px; box-shadow: rgba(0, 0, 0, 0.12) 0px 12px 48px 0px;">
              <span class="Tag" style="box-sizing: border-box; border-radius: 6.5px; display: inline-block; color: rgb(102, 57, 116); background-color: rgb(229, 224, 234); font-weight: 400; letter-spacing: -0.15px; line-height: 1.2; margin-right: 10px; padding: 8px 12px; font-size: 16px; font-family: 'aktiv-grotesk', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                Step 3
              </span>
              <h2 class="custom-heading mb-8" itemProp="name" style="box-sizing: border-box; line-height: 1.16; letter-spacing: -0.7px; font-weight: 300; font-size: 1.55em; margin: 12px 0px 27px; padding: 0px; margin-bottom: 27px; padding-top: 0px;">
                Certifications
              </h2>
              <p class="text-sm text-gray-500 mb-8">
                These certifications are based on global requirements for the job. If they are not accepted in your country, try to find an alternative.
              </p>
              <div v-if="howToBecome.certifications && howToBecome.certifications.length > 0" class="space-y-4 mb-8">
                <ul class="list-custom">
                  <li v-for="(certification, index) in howToBecome.certifications" :key="index" class="text-lg text-gray-700 leading-relaxed">
                    {{ certification.title }}
                  </li>
                </ul>
              </div>
              <div v-else class="space-y-4 mb-8">
                <p class="text-lg text-gray-700">No certifications available for this occupation.</p>
              </div>
            </section>

            <section v-if="howToBecome.associations && howToBecome.associations.length > 0" id="step-4" class="how-to-step Box" itemProp="step" itemType="http://schema.org/HowToStep" tabIndex="0" style="box-sizing: border-box; display: block; transition: box-shadow 0.33s, transform 0.33s; background-color: rgb(255, 255, 255); padding: 30px; border-radius: 19px; margin-top: 40px; box-shadow: rgba(0, 0, 0, 0.12) 0px 12px 48px 0px;">
              <span class="Tag" style="box-sizing: border-box; border-radius: 6.5px; display: inline-block; color: rgb(102, 57, 116); background-color: rgb(229, 224, 234); font-weight: 400; letter-spacing: -0.15px; line-height: 1.2; margin-right: 10px; padding: 8px 12px; font-size: 16px; font-family: 'aktiv-grotesk', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                Step 4
              </span>
              <h2 class="custom-heading mb-8" itemProp="name" style="box-sizing: border-box; line-height: 1.16; letter-spacing: -0.7px; font-weight: 300; font-size: 1.55em; margin: 12px 0px 27px; padding: 0px; margin-bottom: 27px; padding-top: 0px;">
                Associations
              </h2>
              <div class="space-y-4 mb-8">  
                <ul class="list-custom">
                  <li v-for="(association, index) in howToBecome.associations" :key="index" class="text-lg text-gray-700 leading-relaxed">
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