<template>
  <AppLayout preserveScroll>
    <div class="min-h-screen bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 py-10 px-5 font-sans">
      <div class="bg-white shadow-lg rounded-2xl p-10 max-w-6xl mx-auto">
        <header class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold m-0 text-purple-800">{{ ArchetypeData.name }}'s Personality Report</h1>
          <div class="flex space-x-3">
            <button class="bg-blue-200 border-none text-blue-800 py-2 px-4 rounded-lg cursor-pointer transition duration-300 ease-in-out hover:bg-blue-300 text-sm">
              <i class="fas fa-print mr-2"></i> Print
            </button>
            <button class="bg-green-200 border-none text-green-800 py-2 px-4 rounded-lg cursor-pointer transition duration-300 ease-in-out hover:bg-green-300 text-sm">
              <i class="fas fa-share-alt mr-2"></i> Share
            </button>
          </div>
        </header>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <div v-for="(card, index) in cards" :key="index" class="cursor-pointer bg-gray-50 rounded-lg flex flex-col p-5 transition duration-300 ease-in-out transform hover:translate-y-[-3px] hover:shadow-md text-gray-800">
            <p class="mt-0 text-base leading-6 mb-4 flex-grow">{{ card.content }}</p>
            <button class="bg-transparent border-none text-blue-800 py-2 text-left cursor-pointer text-sm font-semibold transition duration-300 ease-in-out hover:text-blue-600">{{ card.buttonText }}</button>
          </div>
        </div>

        <div class="mb-8">
          <h1 class="text-3xl font-bold mb-4 text-purple-800">You are a {{ ArchetypeData.name }}</h1>
          
          <p class="text-gray-700 text-base leading-6 mb-3">
            Your strongest trait is {{ ArchetypeData.primary_trait }}, and your second strongest is {{ ArchetypeData.secondary_trait }}, which makes you a {{ ArchetypeData.name }}.
          </p>
          
          <p class="text-gray-700 text-base leading-6 mb-3">
            {{ ArchetypeData.description }}
          </p>

          <div class="mb-8">
            <!-- Replace this with your actual chart component -->
            <img src="path_to_your_chart_image" alt="Personality Traits Chart" class="max-w-full h-auto mx-auto">
          </div>

          <h1 class="text-3xl font-bold mb-4 text-purple-800">Skills You Can Focus On</h1>
          <p class="text-gray-700 text-base leading-6 mb-8">
            {{ ArchetypeData.strengths }}
          </p>

          <h1 class="text-3xl font-bold mb-4 text-purple-800">Tendencies To Be Careful Of</h1>
          <p class="text-gray-700 text-base leading-6 mb-8">
            {{ ArchetypeData.weaknesses }}
          </p>
          <h1 class="text-3xl font-bold mb-4 text-purple-800">Your Working Style</h1>
          <p class="text-gray-700 text-base leading-6 mb-8">
            {{ ArchetypeData.personality_paragraph }}
          </p>

          <div class="mb-8">
            <div v-for="(category, index) in groupedInsights" :key="index" class="mb-2 border-b border-gray-200 last:border-b-0">
              <div class="flex items-center py-3 cursor-pointer" @click="toggleInsight(index)">
                <div class="bg-gray-200 rounded-full p-2 mr-3">
                  <!-- <img :src="require(`~/resources/images/insights/${getIcon(category.category)}.svg`)"  class="w-6 h-6"> -->
                </div>
                <h3 class="text-lg font-semibold m-0 text-gray-800 flex-grow">{{ category.category }}</h3>
                <span class="text-sm text-gray-500">{{ category.insights.length }} Insights Available</span>
                <i class="fas fa-chevron-right ml-3 text-gray-400 transition-transform duration-300 ease-in-out" :class="{ 'rotate-90': category.isOpen }"></i>
              </div>
              <ul v-if="category.isOpen" class="list-none pl-12 py-3">
                <li v-for="(insight, insightIndex) in category.insights" :key="insightIndex" class="text-gray-700 text-base leading-6 mb-2">
                  {{ insight }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { radar } from 'svg-radar-chart'
import stringify from 'virtual-dom-stringify'
import { smoothing } from 'svg-radar-chart/smoothing.js'

export default {
  props: {
    ArchetypeData: Object,
    Insights: Array,
    userId: Number,
    firstScore: Object
  },
  components: {
    AppLayout
  },
  computed: {
    groupedInsights() {
      const grouped = {};
      this.Insights.forEach((insight) => {
        if (!grouped[insight.insight_category]) {
          grouped[insight.insight_category] = {
            category: insight.insight_category,
            insights: [],
            isOpen: false
          };
        }
        grouped[insight.insight_category].insights.push(insight.insight);
      });
      return Object.values(grouped);
    }
  },
  data() {
    return {
      cards: [
        {
          content: `You are a ${this.ArchetypeData.name}. Your strongest trait is ${this.ArchetypeData.primary_trait}, and second strongest trait is ${this.ArchetypeData.secondary_trait}.`,
          buttonText: `LEARN ABOUT ${this.ArchetypeData.name.toUpperCase()}S`
        },
        {
          content: "You love being creative with your ideas. You have an active imagination and like to interpret the world around you before expressing your findings to others.",
          buttonText: "LEARN ABOUT YOUR SKILLS"
        },
        {
          content: "You likely have a general proclivity for self reflection, which makes you particularly receptive to your environment and your place in it.",
          buttonText: "LEARN ABOUT YOUR STRENGTHS"
        }
      ],
      groupedInsights: [],
      svgChart: '',
      randomScores: {
        Realistic: Math.random(),
        Investigative: Math.random(),
        Artistic: Math.random(),
        Social: Math.random(),
        Enterprising: Math.random(),
        Conventional: Math.random()
      }
    }
  },
  created() {
    this.groupInsights();
  },
  mounted() {
    this.createRadarChart();
  },
  methods: {
    getIcon(category) {
      switch (category.toLowerCase()) {
        case 'strengths': return 'strengths';
        case 'watch out for': return 'watch-out-for';
        case 'team interaction': return 'team-interaction';
        case 'personal style': return 'personal-style';
        case 'ideal work environment': return 'ideal-work-environment';
        default: return 'default-icon';
      }
    },
    groupInsights() {
      const grouped = {};
      this.Insights.forEach((insight) => {
        if (!grouped[insight.insight_category]) {
          grouped[insight.insight_category] = {
            category: insight.insight_category,
            insights: [],
            isOpen: false
          };
        }
        grouped[insight.insight_category].insights.push(insight.insight);
      });
      this.groupedInsights = Object.values(grouped);
    },
    toggleInsight(index) {
      this.groupedInsights[index].isOpen = !this.groupedInsights[index].isOpen;
    },
    createRadarChart() {
      const chart = radar({
        Realistic: 'Realistic',
        Investigative: 'Investigative',
        Artistic: 'Artistic',
        Social: 'Social',
        Enterprising: 'Enterprising',
        Conventional: 'Conventional'
      }, [
        {
          class: 'user-score',
          Realistic: this.randomScores.Realistic,
          Investigative: this.randomScores.Investigative,
          Artistic: this.randomScores.Artistic,
          Social: this.randomScores.Social,
          Enterprising: this.randomScores.Enterprising,
          Conventional: this.randomScores.Conventional
        }
      ], {
        axes: true,
        scales: 1,
        captions: true,
        captionsPosition: 1.2,
        smoothing: smoothing(0.9),
        axisProps: () => ({ className: 'axis' }),
        scaleProps: () => ({ className: 'scale', fill: 'none' }),
        shapeProps: () => ({ className: 'shape' }),
        captionProps: () => ({
          className: 'caption',
          textAnchor: 'middle',
          fontSize: 12,
          fontFamily: 'SF Pro Display, sans-serif',
        }),
      });

      this.svgChart = `
        <svg version="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="300" height="250">
          <style>
            .axis { stroke: #888; stroke-width: 1; }
            .shape { fill: rgba(168, 8, 247, 0.5); stroke: rgba(168, 85, 247, 1); stroke-width: 2; }
          </style>
          <rect width="100%" height="100%" fill="#f8f8f8" />
          ${stringify(chart)}
        </svg>
      `;
    }
  }
}
</script>

<style scoped>
@media (max-width: 640px) {
  .mb-8 > div > div {
    flex-wrap: wrap;
  }
  .mb-8 > div > div > span {
    width: 100%;
    margin-left: 3rem;
    margin-top: 0.5rem;
  }
}
</style>