<template>

  <StickySidebar sidebar-description="abilities"  :title="occupation.title">



  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ occupation.title }} - Abilities</h1>
    <div v-if="abilities.length">
      <ul class="space-y-2">
        <li v-for="ability in abilities" :key="ability.element_id">
          <p>{{ ability.element_name }}</p>
        </li>
      </ul>
    </div>
    <div v-else>
      <p>No abilities data available.</p>
    </div>
  </div>
  </StickySidebar>

</template>

<script>
import StickySidebar from "@/Pages/lib/StickySidebar.vue";

export default {
  components: {StickySidebar},
  props: {
    occupation: {
      type: Object,
      required: true
    },
    abilities: {
      type: Array,
      required: true
    },
    baseUri: {
      type: String,
      required: true
    }
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
