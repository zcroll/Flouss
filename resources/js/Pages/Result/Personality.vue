<template>
    <AppLayout preserveScroll>
      <div class="personality-report">
        <div class="content-wrapper">
          <header class="report-header">
            <h1 class="report-title">{{ ArchetypeData.name }}'s Personality Report</h1>
            <div class="header-buttons">
              <button class="header-button">
                <i class="fas fa-print mr-2"></i> Print
              </button>
              <button class="header-button">
                <i class="fas fa-share-alt mr-2"></i> Share
              </button>
            </div>
          </header>
          
          <div class="card-container">
            <div v-for="(card, index) in cards" :key="index" class="card">
              <p>{{ card.content }}</p>
              <button class="card-button">{{ card.buttonText }}</button>
            </div>
          </div>
  
          <div class="chart-container">
            <h2>Your Personality Traits</h2>
            <div class="relative w-full h-[300px]" v-html="svgChart"></div>
          </div>
  
          <div class="insights-container">
            <div v-for="(category, index) in groupedInsights" :key="index" class="insight-item">
              <div class="insight-header" @click="toggleInsight(index)">
                <div class="icon-wrapper">
                  <i :class="getIcon(category.category)" class="insight-icon"></i>
                </div>
                <h3 class="insight-category">{{ category.category }}</h3>
                <i :class="['fas', 'toggle-icon', category.isOpen ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
              </div>
              <div :class="['insight-content', {'collapsed': !category.isOpen}]">
                <ul class="insight-list">
                  <li v-for="(insight, insightIndex) in category.insights" :key="insightIndex">
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
              insights: []
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
            content: this.ArchetypeData.description,
            buttonText: "LEARN ABOUT YOUR SKILLS"
          },
          {
            content: "You likely have a general proclivity for self reflection, which makes you particularly receptive to your environment and your place in it.",
            buttonText: "LEARN ABOUT YOUR STRENGTHS"
          }
        ],
        groupedInsights: [],
        isMobileView: false,
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
      this.checkMobileView();
      window.addEventListener('resize', this.checkMobileView);
    },
    mounted() {
      this.createRadarChart();
    },
    beforeUnmount() {
      window.removeEventListener('resize', this.checkMobileView);
    },
    methods: {
      getIcon(category) {
        switch (category.toLowerCase()) {
          case 'strengths': return 'fas fa-award';
          case 'watch out for': return 'fas fa-exclamation-triangle';
          case 'team interaction': return 'fas fa-users';
          case 'personal style': return 'fas fa-user';
          case 'ideal work environment': return 'fas fa-briefcase';
          default: return 'fas fa-question';
        }
      },
      groupInsights() {
        const grouped = {};
        this.Insights.forEach((insight) => {
          if (!grouped[insight.insight_category]) {
            grouped[insight.insight_category] = {
              category: insight.insight_category,
              insights: [],
              isOpen: true
            };
          }
          grouped[insight.insight_category].insights.push(insight.insight);
        });
        this.groupedInsights = Object.values(grouped);
      },
      toggleInsight(index) {
        if (this.isMobileView) {
          this.groupedInsights[index].isOpen = !this.groupedInsights[index].isOpen;
        }
      },
      checkMobileView() {
        this.isMobileView = window.innerWidth <= 768;
        if (!this.isMobileView) {
          this.groupedInsights.forEach(category => category.isOpen = false);
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
  @import url('https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@300;400;600;700&display=swap');
  @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
  
  .personality-report {
    min-height: 100vh;
    background-color: #f5f5f7;
    padding: 60px 30px;
    font-family: 'SF Pro Display', Helvetica, Arial, sans-serif;
  }
  
  .content-wrapper {
    background-color: #ffffff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
  }
  
  .report-title {
    color: #1d1d1f;
    font-size: 32px;
    font-weight: bold;
    margin: 0;
  }
  
  .header-button {
    background-color: #0071e3;
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 14px;
  }
  
  .header-button:hover {
    background-color: #0077ed;
  }
  
  .card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
  }
  
  .card {
    cursor: pointer;
    background-color: #ffffff;
    border-radius: 12px;
    display: flex;
    width: 100%;
    height: 100%;
    flex-direction: column;
    padding: 24px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }
  
  .card p {
    margin-top: 0;
    font-size: 16px;
    line-height: 1.5;
    color: #1d1d1f;
    margin-bottom: 20px;
    flex-grow: 1;
  }
  
  .card-button {
    background-color: transparent;
    border: none;
    color: #0071e3;
    padding: 12px 0;
    text-align: left;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: color 0.3s ease;
  }
  
  .card-button:hover {
    color: #0077ed;
  }
  
  .chart-container {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 40px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
  }
  
  .chart-container h2 {
    color: #1d1d1f;
    font-size: 24px;
    margin-bottom: 20px;
  }
  
  .chart-container svg {
    max-width: 100%;
    height: auto;
    margin: 0 auto;
  }
  
  .insights-container {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .insight-item {
    border-bottom: 1px solid #e0e0e0;
    padding: 20px 0;
  }
  
  .insight-item:last-child {
    border-bottom: none;
  }
  
  .insight-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
  }
  
  .icon-wrapper {
    background-color: #f2f2f7;
    border-radius: 50%;
    padding: 10px;
    margin-right: 15px;
  }
  
  .insight-icon {
    font-size: 20px;
    color: #0071e3;
  }
  
  .insight-category {
    font-size: 18px;
    font-weight: 600;
    color: #1d1d1f;
    margin: 0;
  }
  
  .insight-list {
    list-style-type: none;
    padding-left: 0;
    margin: 0;
  }
  
  .insight-list li {
    color: #1d1d1f;
    margin-bottom: 10px;
    line-height: 1.5;
    position: relative;
    padding-left: 20px;
  }
  
  .insight-list li::before {
    content: "•";
    color: #0071e3;
    position: absolute;
    left: 0;
  }
  
  .insight-list li:last-child {
    margin-bottom: 0;
  }
  
  .toggle-icon {
    display: none;
    margin-left: auto;
    font-size: 18px;
    color: #0071e3;
    transition: transform 0.3s ease;
  }
  
  .insight-content {
    transition: max-height 0.3s ease;
    overflow: hidden;
    max-height: 1000px;
  }
  
  @media (max-width: 768px) {
    .personality-report {
      padding: 30px 20px;
    }
  
    .content-wrapper {
      padding: 24px;
      border-radius: 16px;
    }
  
    .report-title {
      font-size: 28px;
    }
  
    .report-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 15px;
    }
  
    .card-container {
      grid-template-columns: 1fr;
    }
  
    .card {
      padding: 20px;
    }
  
    .card p {
      font-size: 16px;
    }
  
    .card-button {
      font-size: 14px;
    }
  
    .insight-header {
      cursor: pointer;
    }
  
    .toggle-icon {
      display: block;
    }
  
    .insight-content.collapsed {
      max-height: 0;
    }
  
    .insight-item[data-open="true"] .toggle-icon {
      transform: rotate(180deg);
    }
  }
  </style>