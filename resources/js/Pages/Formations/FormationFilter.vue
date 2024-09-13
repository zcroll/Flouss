<template>
  <div class="formation-filter">
    <h1 class="text-2xl font-bold mb-4">Formation Filter</h1>
    
    <div class="filter-controls mb-4">
      <input v-model="form.search" @input="debouncedFilter" placeholder="Search formations" class="p-2 border rounded mr-2">
      <select v-model="form.etablissement" @change="filter" class="p-2 border rounded">
        <option value="">All Establishments</option>
        <option v-for="etab in etablissements" :key="etab.id" :value="etab.id">{{ etab.nom }}</option>
      </select>
    </div>

    <div v-if="processing" class="text-center">
      <p>Loading...</p>
    </div>
    
    <div v-else class="formation-list">
      <div v-for="formation in formations.data" :key="formation.id" class="formation-card p-4 mb-4 bg-white shadow rounded">
        <h2 class="text-xl font-semibold">{{ formation.nom }}</h2>
        <p class="text-gray-600">{{ formation.etablissement.nom }}</p>
        <p class="mt-2">{{ formation.descriptionFr }}</p>
      </div>
    </div>

    <div class="pagination mt-4">
      <!-- Add pagination controls here -->
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { debounce } from 'lodash';
import { useForm } from '@inertiajs/vue3'
const props = defineProps({
  formations: Object,
  etablissements: Array,
  filters: Object,
});

const form = useForm({
  search: props.filters.search || '',
  etablissement: props.filters.etablissement || '',
});

const formations = ref(props.formations);
const etablissements = ref(props.etablissements);

const filter = () => {
  form.get(route('formations.index'), {
    preserveState: true,
    preserveScroll: true,
    only: ['formations'],
    onSuccess: (page) => {
      formations.value = page.props.formations;
    },
  });
};

const debouncedFilter = debounce(filter, 300);

watch(() => form.etablissement, filter);

onMounted(() => {
  if (form.search || form.etablissement) {
    filter();
  }
});
</script>

<style scoped>
.formation-filter {
  max-width: 800px;
  margin: 0 auto;
}

.formation-card {
  transition: all 0.3s ease;
}

.formation-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>