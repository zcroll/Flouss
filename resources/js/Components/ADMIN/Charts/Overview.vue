<script setup>
import { ref } from 'vue'
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
)

const data = ref({
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  datasets: [
    {
      label: 'Revenue',
      data: Array.from({length: 12}, () => Math.floor(Math.random() * 5000) + 1000),
      backgroundColor: 'hsl(var(--primary))',
      borderRadius: 4,
      barThickness: 20,
    }
  ]
})

const options = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false
    },
    tooltip: {
      mode: 'index',
      intersect: false,
      callbacks: {
        label: function(context) {
          let label = context.dataset.label || '';
          if (label) {
            label += ': ';
          }
          if (context.parsed.y !== null) {
            label += new Intl.NumberFormat('en-US', { 
              style: 'currency', 
              currency: 'USD' 
            }).format(context.parsed.y);
          }
          return label;
        }
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        callback: function(value) {
          return '$' + value.toLocaleString();
        },
        color: 'hsl(var(--muted-foreground))'
      },
      grid: {
        color: 'hsl(var(--border))'
      }
    },
    x: {
      grid: {
        display: false
      },
      ticks: {
        color: 'hsl(var(--muted-foreground))'
      }
    }
  }
}
</script>

<template>
  <div class="w-full h-[350px] p-4 bg-card rounded-lg">
    <Bar 
      :data="data" 
      :options="options"
    />
  </div>
</template>
