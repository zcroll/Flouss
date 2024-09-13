<template>
    <AppLayout :head-title="'Degree'" :show-search="true" name="Degree ">
      <div class="career-explorer">
        <div class="top-bar">
          <!-- ... existing top-bar content ... -->
        </div>

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
                <div class="job-list">
                  <div v-for="job in filteredJobs" :key="job.id" class="job-card">
                    <img :src="job.image" :alt="job.title" class="job-image">
                    <div class="job-details">
                      <h2>{{ job.title }}</h2>
                      <p>{{ job.description }}</p>
                      <div class="job-stats">
                        <span>Salary: ${{ job.salary }}</span>
                        <span>Satisfaction: {{ job.satisfaction }}%</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="filter-section">
                  <h2 class="filter-title">Filter Degrees</h2>
                  <vue-multiselect
                    v-model="selectedIndustries"
                    :options="industries"
                    :multiple="true"
                    :close-on-select="false"
                    placeholder="Select industries"
                    label="name"
                    track-by="id"
                    class="custom-multiselect"
                  ></vue-multiselect>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    </AppLayout>
</template>

<script>
import VueMultiselect from 'vue-multiselect'
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed } from 'vue';

export default {
  components: {
      AppLayout,
    VueMultiselect
  },
  setup() {
    const activeFilter = ref(null);

    const applyFilter = (filter) => {
      activeFilter.value = filter;
      // Implement filtering logic based on the selected filter
    };

    const filteredJobs = computed(() => {
      // Implement your filtering logic here, including the activeFilter
      if (activeFilter.value === 'noDegree') {
        return jobs.value.filter(job => !job.requiresDegree);
      }
      // Add other filter conditions as needed
      return jobs.value;
    });

    return {
      selectedIndustries: [],
      industries: [
        { id: 1, name: 'Technology' },
        { id: 2, name: 'Healthcare' },
        { id: 3, name: 'Finance' },
        // Add more industries as needed
      ],
      jobs: [
        {
          id: 1,
          title: 'Software Developer',
          description: 'Design and develop software applications',
          image: 'images_options/freya-card.svg',
          salary: 85000,
          satisfaction: 85,
          industryId: 1
        },
        {
          id: 2,
          title: 'Nurse Practitioner',
          description: 'Provide advanced nursing care to patients',
          image: 'images_options/vasco-card.svg',
          salary: 110000,
          satisfaction: 89,
          industryId: 2
        },
        // Add more job objects as needed
      ],
      activeFilter,
      applyFilter
    }
  },
  computed: {
    filteredJobs() {
      if (this.selectedIndustries.length === 0) {
        return this.jobs
      }
      return this.jobs.filter(job =>
        this.selectedIndustries.some(industry => industry.id === job.industryId)
      )
    }
  }
}
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
  justify-content: space-between;
  margin-top: 10px;
  font-size: 0.9em;
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

/* ... existing styles ... */
</style>
