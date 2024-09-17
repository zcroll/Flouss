<template>
  <AppLayout preserveScroll :head-title="`Hello ${$page.props.auth.user.name}`">
      <div class="w-full min-h-screen py-10 px-5 font-sans">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            
              <div class="lg:col-span-2 bg-white shadow-lg rounded-2xl p-10">
        <header class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-light tracking-wide text-gray-800">{{ ArchetypeData.name }}'s Personality Report</h1>
          <div class="flex space-x-3">
            <button class="relative isolate inline-flex items-center justify-center gap-x-2 rounded-lg border text-base/6 font-semibold px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing.3)-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] sm:text-sm/6 focus:outline-none data-[focus]:outline data-[focus]:outline-2 data-[focus]:outline-offset-2 data-[focus]:outline-blue-500 data-[disabled]:opacity-50 [&>[data-slot=icon]]:-mx-0.5 [&>[data-slot=icon]]:my-0.5 [&>[data-slot=icon]]:size-5 [&>[data-slot=icon]]:shrink-0 [&>[data-slot=icon]]:text-[--btn-icon] [&>[data-slot=icon]]:sm:my-1 [&>[data-slot=icon]]:sm:size-4 forced-colors:[--btn-icon:ButtonText] forced-colors:data-[hover]:[--btn-icon:ButtonText] border-zinc-950/10 text-zinc-950 data-[active]:bg-zinc-950/[2.5%] data-[hover]:bg-zinc-950/[2.5%] dark:border-white/15 dark:text-white dark:[--btn-bg:transparent] dark:data-[active]:bg-white/5 dark:data-[hover]:bg-white/5 [--btn-icon:theme(colors.zinc.500)] data-[active]:[--btn-icon:theme(colors.zinc.700)] data-[hover]:[--btn-icon:theme(colors.zinc.700)] dark:data-[active]:[--btn-icon:theme(colors.zinc.400)] dark:data-[hover]:[--btn-icon:theme(colors.zinc.400)]">
              <i class="fas fa-print mr-2"></i> Print
            </button>
            <button class="relative isolate inline-flex items-center justify-center gap-x-2 rounded-lg border text-base/6 font-semibold px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing.3)-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] sm:text-sm/6 focus:outline-none data-[focus]:outline data-[focus]:outline-2 data-[focus]:outline-offset-2 data-[focus]:outline-blue-500 data-[disabled]:opacity-50 [&>[data-slot=icon]]:-mx-0.5 [&>[data-slot=icon]]:my-0.5 [&>[data-slot=icon]]:size-5 [&>[data-slot=icon]]:shrink-0 [&>[data-slot=icon]]:text-[--btn-icon] [&>[data-slot=icon]]:sm:my-1 [&>[data-slot=icon]]:sm:size-4 forced-colors:[--btn-icon:ButtonText] forced-colors:data-[hover]:[--btn-icon:ButtonText] border-zinc-950/10 text-zinc-950 data-[active]:bg-zinc-950/[2.5%] data-[hover]:bg-zinc-950/[2.5%] dark:border-white/15 dark:text-white dark:[--btn-bg:transparent] dark:data-[active]:bg-white/5 dark:data-[hover]:bg-white/5 [--btn-icon:theme(colors.zinc.500)] data-[active]:[--btn-icon:theme(colors.zinc.700)] data-[hover]:[--btn-icon:theme(colors.zinc.700)] dark:data-[active]:[--btn-icon:theme(colors.zinc.400)] dark:data-[hover]:[--btn-icon:theme(colors.zinc.400)]">
              <i class="fas fa-share-alt mr-2"></i> Share
            </button>
          </div>
        </header>


        <div class="mx-auto px-4 sm:px-6 md:max-w-2xl md:px-4 lg:max-w-4xl lg:px-12">
          <h2 id="personality-profile" class="inline-flex items-center rounded-full px-4 py-1 text-blue-600 ring-1 ring-inset ring-blue-600">
            <span class="font-mono text-sm" aria-hidden="true">01</span>
            <span class="ml-3 h-3.5 w-px bg-blue-600/20"></span>
            <span class="ml-3 text-base font-medium tracking-tight">Your Personality Profile</span>
          </h2>

          <h1 class="mt-8 font-display text-4xl font-bold tracking-tight text-slate-900">You are a {{ ArchetypeData.name }}</h1>

          <p class="mt-4 text-lg tracking-tight text-slate-700">
            Your strongest trait is {{ ArchetypeData.primary_trait }}, and your second strongest is {{ ArchetypeData.secondary_trait }}, which makes you a {{ ArchetypeData.name }}.
          </p>

          <p class="mt-4 text-lg tracking-tight text-slate-700">
            {{ ArchetypeData.description }}
          </p>

          <div class="mt-16">
            
            <!-- Replace this with your actual chart component -->
          </div>

          <h2 id="strengths-and-growth" class="mt-16 inline-flex items-center rounded-full px-4 py-1 text-blue-600 ring-1 ring-inset ring-blue-600">
            <span class="font-mono text-sm" aria-hidden="true">02</span>
            <span class="ml-3 h-3.5 w-px bg-blue-600/20"></span>
            <span class="ml-3 text-base font-medium tracking-tight">Your Strengths and Areas for Growth</span>
          </h2>

          <h3 class="mt-8 font-display text-3xl font-bold tracking-tight text-slate-900">Skills You Can Focus On</h3>
          <p class="mt-4 text-lg tracking-tight text-slate-700">
            {{ ArchetypeData.strengths }}
          </p>

          <h3 class="mt-8 font-display text-3xl font-bold tracking-tight text-slate-900">Tendencies To Be Careful Of</h3>
          <p class="mt-4 text-lg tracking-tight text-slate-700">
            {{ ArchetypeData.weaknesses }}
          </p>

          <h2 id="working-style" class="mt-16 inline-flex items-center rounded-full px-4 py-1 text-blue-600 ring-1 ring-inset ring-blue-600">
            <span class="font-mono text-sm" aria-hidden="true">03</span>
            <span class="ml-3 h-3.5 w-px bg-blue-600/20"></span>
            <span class="ml-3 text-base font-medium tracking-tight">Your Working Style</span>
          </h2>

          <h3 class="mt-8 font-display text-3xl font-bold tracking-tight text-slate-900">Your Working Style</h3>
          <p class="mt-4 text-lg tracking-tight text-slate-700">
            {{ ArchetypeData.personality_paragraph }}
          </p>

          <h2 id="detailed-insights" class="mt-16 inline-flex items-center rounded-full px-4 py-1 text-blue-600 ring-1 ring-inset ring-blue-600">
            <span class="font-mono text-sm" aria-hidden="true">04</span>
            <span class="ml-3 h-3.5 w-px bg-blue-600/20"></span>
            <span class="ml-3 text-base font-medium tracking-tight">Detailed Insights</span>
          </h2>

          <div class="mt-8">
            <div v-for="(category, index) in groupedInsights" :key="index" class="mt-8">
              <h3 class="font-display text-3xl font-bold tracking-tight text-slate-900">{{ category.category }}</h3>
              <ol role="list" class="mt-8 divide-y divide-slate-300/30 rounded-2xl bg-slate-50 px-6 py-3 text-base tracking-tight sm:px-8 sm:py-7">
                <li v-for="(insight, insightIndex) in category.insights" :key="insightIndex" class="flex justify-between py-3">
                  <span class="font-medium text-slate-900">{{ insight }}</span>
                </li>
              </ol>
            </div>
          </div>
        </div>


      </div>
              <div class="lg:col-span-1">
                <div class="sticky top-20  rounded-2xl p-6">
                  <div class="grid gap-4">
                    <div v-for="(card, index) in cards" :key="index" class="bg-gray-900 rounded-xl p-4 shadow-lg ">
                      <p class="text-sm font-serif text-zinc-100 mb-3">{{ card.content.substring(0, 100) }}...</p>
                      <button @click="scrollToSection(card.sectionId)" class="text-xs font-semibold bg-zinc-200 text-black rounded-lg px-3 py-1 hover:bg-zinc-500 transition-colors">
                        {{ card.buttonText }}
                      </button>
                    </div>
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
import LineChart from "./LineChart.vue";

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
          buttonText: `learn about your ${this.ArchetypeData.name.toLowerCase()}s`,
          sectionId: 'personality-profile'
        },
        {
          content: this.ArchetypeData.strengths,
          buttonText: "learn about your skills",
          sectionId: 'strengths-and-growth'
        },
        {
          content: this.ArchetypeData.personality_paragraph,
          buttonText: "learn about your strengths",
          sectionId: 'working-style'
        }
      ],
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

      // this.svgChart = `
      //   <svg version="1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="300" height="250">
      //     <style>
      //       .axis { stroke: #888; stroke-width: 1; }
      //       .shape { fill: rgba(168, 8, 247, 0.5); stroke: rgba(168, 85, 247, 1); stroke-width: 2; }
      //     </style>
      //     <rect width="100%" height="100%" fill="#f8f8f8" />
      //     ${stringify(chart)}
      //   </svg>
      // `;
    },
    scrollToSection(sectionId) {
      const section = document.getElementById(sectionId);
      if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
      }
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
