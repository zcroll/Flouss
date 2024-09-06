<template>
  <AppLayout>
    <StickySidebar :slug="occupation.slug" :title="occupation.name" :image="occupation.image">
      <div class="w-full lg:w-4/4 space-y-1">


          {{jobInfoTypes}}}
          {{jobInfoDuties}}}
          {{jobInfoDetail}}}
          {{workplaces}}

      </div>
    </StickySidebar>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { defineProps } from 'vue';
import StickySidebar from "@/Pages/lib/StickySidebar.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
  occupation: {
    type: Object,
    required: true,
  },

  jobInfoDetail: {
    type: Object,
    required: true,
  },
  jobInfoDuties: {
    type: Object,
    required: true,
  },
  jobInfoTypes: {
    type: Object,
    required: true,
  },
  workplaces: {
    type: Array,
    required: true,
  },
});

const jobInfo = computed(() => props.jobInfoDetail.description);

</script>

<style scoped>
/* add your styles here */
</style>
