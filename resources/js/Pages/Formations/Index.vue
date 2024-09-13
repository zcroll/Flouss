<template>
  <AppLayout>
    <FormationFilter
      :formations="formations"
      :etablissements="etablissements"
      :filters="filters"
    />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FormationFilter from './FormationFilter.vue';

defineProps({
  formations: Object,
  etablissements: Array,
  filters: Object,
});
</script>