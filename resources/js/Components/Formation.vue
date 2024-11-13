<template>
  <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-1">
    <li v-for="formation in formations" :key="formation.id" class="col-span-1 divide-y divide-gray-200 rounded-lg bg-gray-100 shadow">
      <div class="flex w-full items-center justify-between space-x-6 p-6">
        <div class="flex-1">
          <div class="flex items-center space-x-3">
            <h3 class="text-lg sm:text-xl font-medium text-gray-900">{{ formation.nom }}</h3>
            <span class="inline-flex flex-shrink-0 items-center rounded-full bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
              Recommended
            </span>
          </div>
          <p class="mt-1 text-md sm:text-lg text-gray-500">{{ formation.type_etablissement }}</p>
          <p class="mt-1 text-sm sm:text-md text-gray-500">{{ formation.diploma }}</p>
        </div>
      </div>
      <div>
        <div class="-mt-px flex divide-x divide-gray-200">
          <div class="flex w-0 flex-1">
            <a :href="`mailto:${formation.email}`" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
              {{ formation.niveau }}
            </a>
          </div>
          <div class="-ml-px flex w-0 flex-1">
            <a :href="`tel:${formation.telephone}`" class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
              {{ formation.ville }}
            </a>
          </div>
          <div class="-ml-px flex w-0 flex-1">
            <a :href="`tel:${formation.telephone}`" class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
              <EnvelopeIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </a>
          </div>
          <div class="-ml-px flex w-0 flex-1">
            <a :href="`tel:${formation.telephone}`" class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900">
              <PhoneIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </a>
          </div>
        </div>
      </div>
    </li>
  </ul>
</template>

<script setup>
import { EnvelopeIcon, PhoneIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
  formations: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
/* Add any necessary styles */
</style>
