<script setup>
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/ui/card'
import { MapPin } from 'lucide-vue-next'

defineProps({
  cities: {
    type: Array,
    required: true,
    default: () => []
  },
  totalVisits: {
    type: Number,
    required: true,
    default: 0
  },
  totalCities: {
    type: Number,
    required: true,
    default: 0
  }
})
</script>

<template>
  <div class="space-y-6">
    <!-- Overview Cards -->
    <div class="grid gap-4 md:grid-cols-2">
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center">
            <MapPin class="mr-2 h-5 w-5" />
            Total Visits
          </CardTitle>
          <CardDescription>Across all cities</CardDescription>
        </CardHeader>
        <CardContent>
          <p class="text-2xl font-bold">{{ totalVisits }}</p>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle class="flex items-center">
            <MapPin class="mr-2 h-5 w-5" />
            Total Cities
          </CardTitle>
          <CardDescription>Unique cities tracked</CardDescription>
        </CardHeader>
        <CardContent>
          <p class="text-2xl font-bold">{{ totalCities }}</p>
        </CardContent>
      </Card>
    </div>

    <!-- Cities Table -->
    <Card>
      <CardHeader>
        <CardTitle class="flex items-center">
          <MapPin class="mr-2 h-5 w-5" />
          City Visit Rankings
        </CardTitle>
        <CardDescription>Detailed breakdown of visits by city</CardDescription>
      </CardHeader>
      <CardContent>
        <div class="relative overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase bg-muted">
              <tr>
                <th class="px-6 py-3">Rank</th>
                <th class="px-6 py-3">City</th>
                <th class="px-6 py-3">Country</th>
                <th class="px-6 py-3">State/Province</th>
                <th class="px-6 py-3">Visit Count</th>
                <th class="px-6 py-3">Location</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-border">
              <tr v-for="city in cities" 
                  :key="`${city.city}-${city.country}`" 
                  class="bg-card hover:bg-muted/50">
                <td class="px-6 py-4">{{ city.rank }}</td>
                <td class="px-6 py-4">{{ city.city }}</td>
                <td class="px-6 py-4">{{ city.country }}</td>
                <td class="px-6 py-4">{{ city.state_prov || '-' }}</td>
                <td class="px-6 py-4">{{ city.visitCount }}</td>
                <td class="px-6 py-4">
                  {{ city.coordinates.lat.toFixed(4) }}, {{ city.coordinates.lng.toFixed(4) }}
                </td>
              </tr>
              <tr v-if="cities.length === 0">
                <td colspan="6" class="px-6 py-4 text-center text-muted-foreground">
                  No city data available
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </CardContent>
    </Card>
  </div>
</template>