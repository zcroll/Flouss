<template>
    <div class="bg-white p-8 rounded-lg shadow-lg lg:w-7/10 md:w-9/10 sm:w-10/12 mx-auto mt-4">

        <h1 class="text-3xl font-bold mb-4">You are a <span class="text-purple-600">{{ ArchetypeData.name }}</span></h1>
        <p class="mb-4">
            Your strongest trait is <strong>{{ ArchetypeData.primary_trait }}</strong>, and your second strongest is <strong>{{ ArchetypeData.second_trait }}</strong>, which makes you a Philosopher.
        </p>
        <p class="mb-6">
            {{ArchetypeData.description
            }}
        </p>
        <div class="relative w-150 h-150 mb-8">
            <LineChart />

        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Skills You Can Focus On</h2>
            <p class="mb-6">
                {{ArchetypeData.strengths}} </p>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Tendencies To Be Careful Of</h2>
            <p class="mb-6">
              {{ArchetypeData.strengths}}
            </p>
        </div>

        <div class="mt-8">
            <h2 class="text-3xl font-bold mb-4">Your Working Style</h2>
            <p class="mb-6">
                You like analyzing information and using logic to address issues and problems. You likely prefer using
                creativity and alternative thinking to develop new ideas. You are inspired by tough problems, design, and
                the exploration of data or ideas. You are a highly productive individual. You are likely very dependable, and
                are committed to doing a job correctly and carefully. You are trustworthy, accountable and pay attention to
                details. You are likely more risk-averse.
            </p>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import LineChart from "@/Pages/Result/LineChart.vue";

export default {
    props : {
        ArchetypeData: Object
    },



    components: {
        LineChart
    }
}
</script>
