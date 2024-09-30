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
      >
        
        <div class="w-full lg:w-4/4 space-y-5 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl">
          <!-- Main Role Description Section -->
          <section>
            <div v-if="degreeJobs.length > 0" class="grid grid-cols-1 gap-4 sm:grid-cols-1 ">
              <h2 class="custom-heading">{{ __('career.how_to_become') }} {{ occupation.name }} ?</h2>
              <div class="table-of-contents rounded-lg  w-full bg-[#f2e1d5] text-gray-900 relative p-5">
                <p class="text-lg font-black text-gray-500 text-left">
              {{ __('career.best_routes') }} {{ occupation.name }}.
              </p>
              </div>
 
              <div v-for="degreeJob in degreeJobs" :key="degreeJob.key" class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                <div class="flex-shrink-0">
                  <img class="h-10 w-10 rounded-xl" :src="degreeJob.degree.image" :alt="degreeJob.degree.name" />
                </div>
                <div class="min-w-0 flex-1">
                  
                    <span class="absolute inset-0" aria-hidden="true" />
                    <p class="text-2xl font-medium text-gray-900">{{ degreeJob.degree.name }}</p>
                    <p class="truncate text-md text-gray-500">{{ degreeJob.job_title}}</p>
                    <p class="font-thin text-gray-500">{{ degreeJob.job_description }}</p>
                  
                </div>
              </div>
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
    degreeJobs: Array,
    degrees: Object,
    jobInfoDuties: Array,
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
