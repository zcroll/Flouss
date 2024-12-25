<template>
  <StickySidebar type="degree" :model="{
    id: degree.id,
    slug: degree.slug,
    name: degree.name,
    image: degree.image,
    is_favorited: degree.is_favorited
  }">
    <template #description>
      Learn how to obtain a {{ degree.name }} degree and explore available programs.
    </template>

    <div>
      <!-- Page Header -->
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        How to Obtain a {{ degree.name }} Degree
      </h2>

      <!-- Breadcrumb Navigation -->
      <Breadcrumbs :items="[
        { name: 'Home', route: 'dashboard' },
        { name: 'Degrees', route: 'degrees.index' },
        { name: degree.name, route: 'degree.index', params: { slug: degree.slug } },
        { name: 'How to Obtain' }
      ]" />

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filter Section -->
        <aside class="mb-8">
          <div class="flex items-center justify-between gap-2 mb-4">
            <!-- Filter badges -->
            <div class="flex flex-wrap gap-2">
              <Badge v-for="city in selectedCities" :key="city" variant="primary"
                class="flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-1 rounded-lg border border-blue-300">
                {{ city }}
                <button @click="removeCity(city)" class="ml-1 hover:text-blue-600">×</button>
              </Badge>
              <Badge v-for="niveau in selectedNiveaux" :key="niveau" variant="success"
                class="flex items-center gap-1 bg-green-100 text-green-800 px-2 py-1 rounded-lg border border-green-300">
                {{ niveau }}
                <button @click="removeNiveau(niveau)" class="ml-1 hover:text-green-600">×</button>
              </Badge>
              <Badge v-for="type in selectedTypes" :key="type" variant="warning"
                class="flex items-center gap-1 bg-yellow-100 text-yellow-800 px-2 py-1 rounded-lg border border-yellow-300">
                {{ type }}
                <button @click="removeType(type)" class="ml-1 hover:text-yellow-600">×</button>
              </Badge>
            </div>

            <!-- Rest of the filter content -->
          </div>
        </aside>

        <!-- Formations List -->
        <Formation :formations="filteredFormations" @clear-filters="clearAllFilters" />
      </div>
      <BackToTop />
    </div>
  </StickySidebar>
</template>

<script setup>
import { defineComponent, ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import StickySidebar from '@/Pages/lib/StickySidebar.vue'
import Formation from '@/Components/helpers/Formation.vue'
import BackToTop from "@/Components/helpers/BackToTop.vue"
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/Components/ui/sheet"
import { Badge } from "@/Components/ui/badge"
import { Button } from "@/Components/ui/button"
import { FunnelIcon } from '@heroicons/vue/24/outline'
import Breadcrumbs from '@/Components/helpers/Breadcrumbs.vue'
import MainLayout from "@/Layouts/MainLayout.vue"

defineOptions({ layout: MainLayout })

const props = defineProps({
  degree: {
    type: Object,
    required: true,
    validator: (obj) => {
      return ['id', 'slug', 'name', 'image'].every(prop => prop in obj);
    }
  },
  formations: {
    type: Array,
    required: true,
  },
});

// Filter state
const selectedCities = ref([])
const selectedNiveaux = ref([])
const selectedTypes = ref([])

// Computed properties for unique values
const uniqueVilles = computed(() => [...new Set(props.formations.map(f => f.ville))])
const uniqueNiveaux = computed(() => [...new Set(props.formations.map(f => f.niveau))])
const uniqueTypes = computed(() => [...new Set(props.formations.map(f => f.type_etablissement))])

// Filter methods
const removeCity = (city) => {
  selectedCities.value = selectedCities.value.filter(c => c !== city)
}

const removeNiveau = (niveau) => {
  selectedNiveaux.value = selectedNiveaux.value.filter(n => n !== niveau)
}

const removeType = (type) => {
  selectedTypes.value = selectedTypes.value.filter(t => t !== type)
}

const clearAllFilters = () => {
  selectedCities.value = []
  selectedNiveaux.value = []
  selectedTypes.value = []
}

// Filtered formations
const filteredFormations = computed(() => {
  return props.formations.filter(formation => {
    const matchesVille = selectedCities.value.length === 0 || selectedCities.value.includes(formation.ville)
    const matchesNiveau = selectedNiveaux.value.length === 0 || selectedNiveaux.value.includes(formation.niveau)
    const matchesType = selectedTypes.value.length === 0 || selectedTypes.value.includes(formation.type_etablissement)
    return matchesVille && matchesNiveau && matchesType
  })
})
</script>

<style scoped>
/* Add any necessary styles */
</style>
