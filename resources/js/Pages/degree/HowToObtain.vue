<template>
  <AppLayout>
    <!-- Page Header -->
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        How to Obtain a {{ degree.name }} Degree
      </h2>
    </template>

    <!-- Sticky Sidebar with Degree Information -->
    <StickySidebar 
      :slug="degree.slug" 
      :title="degree.name" 
      :image="degree.image"
      type="degree"
    >   
      <div class="py-5 bg-gray-50 rounded-3xl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="p-6">
            <!-- Section Title -->
            <h2 class="custom-heading mb-10">
              Recommended Formations
            </h2>

            <!-- Section Description -->
            <aside class="block">
                    <div id="table-of-contents" class="table-of-contents rounded-lg my-4 w-full bg-[#F8EDE3] text-gray-900 relative p-7">
                      <p class="text-lg text-gray-600 text-left">
                      Based on the similarity analysis, here are the recommended formations to obtain a {{ degree.name }} degree.
                      </p>
                    </div>
              </aside>

            <!-- Formations List -->
            <div v-if="formations.length > 0" class="space-y-6">
              <div v-for="formation in formations" :key="formation.id" class="flex border-b border-gray-200 p-5 rounded-3xl items-center space-x-4 text-lg m-5 text-gray-700 leading-relaxed">
                <!-- Formation Title -->
                <h5 class="text-xl font-semibold text-slate-600 mb-2">
                  {{ formation.name }}
                </h5>

                <!-- Similarity Score -->
                <div class="flex">
                  <template v-for="star in 5" :key="star">
                    <svg
                      :class="star <= Math.ceil(formation.similarity_score * 5) ? 'text-yellow-400' : 'text-gray-300'"
                      class="h-5 w-5 flex-shrink-0"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                    >
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  </template>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </StickySidebar>
  </AppLayout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'

export default defineComponent({
  components: {
    AppLayout,
    StickySidebar,
  },
  props: {
    degree: Object,
    formations: Array,
  },
})
</script>

