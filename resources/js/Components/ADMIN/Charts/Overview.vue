<script setup>
import { ref, computed } from 'vue'
import { Bar } from 'vue-chartjs'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
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

const props = defineProps({
  data: {
    type: Array,
    required: true,
    default: () => []
  },
  title: {
    type: String,
    default: 'Overview'
  },
  description: {
    type: String,
    default: ''
  }
})

const chartData = computed(() => ({
  labels: props.data.map(item => item.date),
  datasets: [{
    label: 'Visits',
    data: props.data.map(item => item.total_visits),
    backgroundColor: 'hsl(var(--primary))',
    borderRadius: 4,
    barThickness: 20,
  }]
}))

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
      backgroundColor: 'hsl(var(--background))',
      titleColor: 'hsl(var(--foreground))',
      bodyColor: 'hsl(var(--foreground))',
      borderColor: 'hsl(var(--border))',
      borderWidth: 1,
      padding: 12,
      bodyFont: {
        family: 'Inter'
      },
      callbacks: {
        label: (context) => `${context.parsed.y.toLocaleString()} visits`
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        color: 'hsl(var(--muted-foreground))',
        font: {
          family: 'Inter'
        }
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
        color: 'hsl(var(--muted-foreground))',
        font: {
          family: 'Inter'
        }
      }
    }
  }
}
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>{{ title }}</CardTitle>
      <CardDescription>{{ description }}</CardDescription>
    </CardHeader>
    <CardContent>
      <div class="h-[350px]">
        <Bar 
          :data="chartData" 
          :options="options"
        />
      </div>
    </CardContent>
  </Card>
</template>
