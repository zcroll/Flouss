<template>
  <AppLayout>
    <div class=" p-1 w-full max-w-4xl mx-auto mt-4">
        <h1 class="text-3xl font-bold mb-4">You are a <span class="text-purple-600">{{ ArchetypeData.name }}</span></h1>
        <p class="mb-4">
            Your strongest trait is <strong>{{ ArchetypeData.primary_trait }}</strong>, and your second strongest is <strong>{{ ArchetypeData.secondary_trait }}</strong>, which makes you a Philosopher.
        </p>
        <p class="mb-6">
            {{ ArchetypeData.description }}
        </p>
        <div class="relative w-150 h-150 mb-8">
            <LineChart :data="data" :options="options"/>
        </div>
        <h2 class="text-2xl font-bold mb-4 flex items-center">Skills You Can Focus On</h2>
        <p>{{ArchetypeData.strengths}}}.</p>
        <h2 class="text-2xl font-bold mb-4 flex items-center">Tendencies To Be Careful Of</h2>
        <p>{{ ArchetypeData.weaknesses }}</p>
        <h2 class="text-2xl font-bold mb-4 flex items-center">Your Working Style</h2>
        <p>{{ ArchetypeData.personality_paragraph }}</p>


        <div v-for="(category, index) in groupedInsights" :key="index" class="mb-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <i :class="getIcon(category.category)" class="mr-3 text-gray-600"></i>
                {{ category.category }}
            </h2>
            <ul class="list-disc pl-8 space-y-4">
                <li v-for="(insight, insightIndex) in category.insights" :key="insightIndex" class="text-lg">
                    {{ insight }}
                </li>
            </ul>
        </div>
    </div>
  </AppLayout>
</template>

<script>
import LineChart from "@/Pages/Result/LineChart.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
export default {
    props: {
        ArchetypeData: Object,
        Insights: Array,
        firstScore:Object
    },
    components: {
      AppLayout,
        LineChart
    },
    computed: {
        groupedInsights() {
            const grouped = {};
            this.Insights.forEach((insight) => {
                if (!grouped[insight.insight_category]) {
                    grouped[insight.insight_category] = {
                        category: insight.insight_category,
                        insights: []
                    };
                }
                grouped[insight.insight_category].insights.push(insight.insight);
            });
            return Object.values(grouped);
        }
    },
    methods: {
        getIcon(category) {
            switch (category.toLowerCase()) {
                case 'strengths':
                    return 'fas fa-award';
                case 'watch out for':
                    return 'fas fa-exclamation-triangle';
                case 'team interaction':
                    return 'fas fa-users';
                case 'personal style':
                    return 'fas fa-user';
                case 'ideal work environment':
                    return 'fas fa-briefcase';
                case 'values':
                    return 'fas fa-heart';
                default:
                    return 'fas fa-question';
            }
        }
    },
    data() {
        return {
            data: {
                labels: ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'],
       datasets: [
                    {
                        label: 'Your Score',
                        data: [0.5, 0.8, 0.7, 0.9, 0.6, 0.4],
                        backgroundColor: 'rgba(168 85 247)',
                        borderColor: 'rgba(168 85 247)',
                        borderWidth: 3,
                        borderCapStyle: 'round',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'bevel',
                        pointBackgroundColor: 'rgba(168 85 247)',
                        pointBorderColor: 'rgba(168 85 247)',
                        pointBorderWidth: 2,
                        pointHitRadius: 2,
                        pointHoverBackgroundColor: 'rgba(168 85 247)',
                        pointHoverBorderColor: 'rgba(168 85 247)',
                        pointHoverBorderWidth: 2,
                        pointHoverRadius: 22,
                        pointRadius: 5,
                        pointRotation: 0,
                        pointStyle: 'rectRounded',
                        spanGaps: true,
                        hoverBackgroundColor: 'rgba(168 85 247)',
                        hoverBorderCapStyle: 'round',
                        hoverBorderColor: 'rgba(168 85 247)',
                        hoverBorderDash: [5, 5],
                        hoverBorderDashOffset: 0.0,
                        hoverBorderJoinStyle: 'miter',
                        hoverBorderWidth: 1,
                        clip: 6,
                        fill: true,
                        order: 0,
                        tension: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scale: {
                    type: 'bar',
                    pointLabels: {
                        display: true
                    }
                }
            }
        }
    }
}
</script>

<style>

</style>
