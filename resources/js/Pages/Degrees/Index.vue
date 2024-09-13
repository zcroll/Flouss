<template>
  <AppLayout :head-title="'Degree'" :show-search="true" name="Degree ">
    <div class="career-explorer">
      <div class="content-wrapper">
        <div class="predefined-filters">
          <h2>FOR YOU</h2>
          <h3>Your top matches</h3>
          <h4>GENERAL</h4>
          <ul>
            <li><a href="#" @click.prevent="applyFilter('highPaying')">High paying careers</a></li>
            <li><a href="#" @click.prevent="applyFilter('attainable')">Attainable careers</a></li>
            <li><a href="#" @click.prevent="applyFilter('partTime')">Part-time careers</a></li>
            <li><a href="#" @click.prevent="applyFilter('noDegree')">Careers that don't require a degree</a></li>
          </ul>
        </div>

        <div class="main-content">
          <div class="job-explorer">
            <div class="content-wrapper">
              <div class="filter-section">
                <h2 class="filter-title">Filter Degrees</h2>
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search degrees by name or field"
                  class="search-input"
                  @input="debouncedSearch"
                />
                <!-- ... (keep other filter inputs) ... -->
              </div>
              <div class="job-list">
                <div v-for="degree in filteredDegrees" :key="degree.id" class="job-card">
                  <img :src="degree.image" :alt="degree.name" class="job-image">
                  <div class="job-details">
                    <h2>{{ degree.name }}</h2>
                    <p>{{ degree.slug }}</p>
                    <div class="job-stats">
                      <span>Salary: ${{ degree.salary }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <Pagination :links="degrees.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import VueMultiselect from 'vue-multiselect';
import debounce from 'lodash/debounce';

const props = defineProps({
    degrees: Object,
    filters: Object,
});

const search = ref(props.filters.search);
const selectedIndustries = ref([]);
const activeFilter = ref('');
const searchQuery = ref('');

const debouncedSearch = debounce(() => {
    router.get('/degrees', { search: searchQuery.value }, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch(searchQuery, (value) => {
    debouncedSearch();
});

const applyFilter = (filter) => {
  activeFilter.value = filter;
};

const filteredDegrees = computed(() => {
  let filtered = props.degrees.data;

  // Apply other filters
  if (activeFilter.value === 'highPaying') {
    filtered = filtered.sort((a, b) => b.salary - a.salary);
  } else if (activeFilter.value === 'attainable') {
    filtered = filtered.sort((a, b) => a.degree_level - b.degree_level);
  } else if (activeFilter.value === 'partTime') {
    // Implement part-time filtering logic if applicable
  } else if (activeFilter.value === 'noDegree') {
    filtered = filtered.filter(degree => degree.degree_level === 0);
  }

  if (selectedIndustries.value.length > 0) {
    filtered = filtered.filter(degree => 
      selectedIndustries.value.some(industry => 
        degree.specializations.includes(industry.name)
      )
    );
  }

  return filtered;
});

const industries = [
  { id: 1, name: 'Technology' },
  { id: 2, name: 'Healthcare' },
  { id: 3, name: 'Finance' },
  // Add more industries as needed
];

onMounted(() => {
  searchQuery.value = search.value;
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
.job-explorer {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.content-wrapper {
  display: flex;
  gap: 20px;
}

.job-list {
  flex: 3;
}

.filter-section {
  background-color: #f5f5f5;
  padding: 20px;
  border-radius: 8px;
}

.filter-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 15px;
  font-family: 'Arial', sans-serif;
}

.search-input {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.job-card {
  display: flex;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 20px;
  transition: all 0.3s ease;
}

.job-card:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  transform: scale(1.02);
}

.job-image {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
  margin-right: 15px;
}

.job-details {
  flex: 1;
}

.job-stats {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 10px;
  font-size: 0.9em;
  color: #666;
}

.additional-info {
  margin-top: 10px;
  font-size: 0.8em;
  color: #666;
}

@media (max-width: 768px) {
  .content-wrapper {
    flex-direction: column-reverse;
  }

  .job-card {
    flex-direction: column;
  }

  .job-image {
    width: 100%;
    margin-right: 0;
    margin-bottom: 15px;
  }
}

/* Custom styles for vue-multiselect */
::v-deep .custom-multiselect {
  font-family: 'Arial', sans-serif;
}

::v-deep .custom-multiselect .multiselect__tags {
  background-color: #ffffff;
}

::v-deep .custom-multiselect .multiselect__tag {
  color: #ffffff;
}

::v-deep .custom-multiselect .multiselect__option--highlight {
  color: #ffffff;
}

.career-explorer {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.predefined-filters {
  width: 200px;
  padding-right: 20px;
  border-right: 1px solid #e0e0e0;
}

.predefined-filters h2 {
  font-size: 14px;
  color: #888;
  margin-bottom: 10px;
}

.predefined-filters h3 {
  font-size: 18px;
  color: #333;
  margin-bottom: 15px;
}

.predefined-filters h4 {
  font-size: 12px;
  color: #888;
  margin-top: 20px;
  margin-bottom: 10px;
}

.predefined-filters ul {
  list-style-type: none;
  padding: 0;
}

.predefined-filters li {
  margin-bottom: 10px;
}

.predefined-filters a {
  color: #333;
  text-decoration: none;
  font-size: 14px;
}

.predefined-filters a:hover {
  color: #007bff;
}

.main-content {
  flex: 1;
}
</style>