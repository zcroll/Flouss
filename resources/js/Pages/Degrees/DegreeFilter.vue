<template>
  <div>
    <div class="mb-6">
      <input
        v-model="search"
        @input="debouncedFilter"
        type="text"
        placeholder="Search degrees"
        class="w-full px-3 py-2 border rounded-md"
      />
    </div>

    <div class="mb-6">
      <vue-multiselect
        v-model="selectedIndustry"
        :options="industries"
        :searchable="false"
        :close-on-select="true"
        placeholder="Select industry"
        label="name"
        track-by="id"
        class="custom-multiselect"
        @input="filterDegrees"
      ></vue-multiselect>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { debounce } from 'lodash';
import axios from 'axios';
import VueMultiselect from 'vue-multiselect';

const props = defineProps({
  degrees: Object,
  industries: Array,
  filters: Object,
});

const emit = defineEmits(['update:degrees']);

const search = ref(props.filters.search || '');
const selectedIndustry = ref(null);

const debouncedFilter = debounce(() => {
  filterDegrees();
}, 300);

const filterDegrees = async () => {
  const params = {
    search: search.value,
    industry: selectedIndustry.value ? selectedIndustry.value.id : null,
  };

  const response = await axios.get('/degrees/filter', { params });
  emit('update:degrees', response.data);
};

onMounted(() => {
  if (props.filters.industry) {
    selectedIndustry.value = props.industries.find(i => i.id === parseInt(props.filters.industry));
  }
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>