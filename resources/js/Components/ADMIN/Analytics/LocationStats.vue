<script setup>
import { onMounted, watch, computed } from 'vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Alert, AlertTitle, AlertDescription } from '@/Components/ui/alert'

const props = defineProps({
  locationData: {
    type: Object,
    required: true,
    default: () => ({
      top_countries: {},
      visit_locations: [],
      total_countries: 0,
      total_cities: 0
    })
  },
  geoipStatus: {
    type: Object,
    required: true,
    default: () => ({
      working: false,
      message: '',
      api_key_valid: false
    })
  }
})

const hasData = computed(() => {
  return props.locationData.total_countries > 0 || props.locationData.total_cities > 0
})

const statusVariant = computed(() => {
  if (!props.geoipStatus.api_key_valid) return 'destructive'
  if (!props.geoipStatus.working) return 'warning'
  return 'default'
})
</script>

<template>
  <div class="space-y-6">
    <!-- GeoIP Status Alert -->
    <Alert :variant="statusVariant">
      <AlertTitle>GeoIP Service Status</AlertTitle>
      <AlertDescription>
        <template v-if="!geoipStatus.api_key_valid">
          Please configure your GeoIP API key in the environment settings.
        </template>
        <template v-else>
          {{ geoipStatus.message }}
          <div v-if="geoipStatus.test_location" class="mt-2 text-sm">
            <div class="font-medium">Test Location:</div>
            <div>IP: {{ geoipStatus.test_location.ip }}</div>
            <div>City: {{ geoipStatus.test_location.city }}</div>
            <div>Country: {{ geoipStatus.test_location.country }}</div>
          </div>
        </template>
      </AlertDescription>
    </Alert>

    <div v-if="hasData" class="grid gap-6 grid-cols-1 lg:grid-cols-2">
      <!-- Countries Card -->
      <Card>
        <CardHeader>
          <CardTitle>Top Visiting Countries</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div v-for="(stats, country) in locationData.top_countries" 
                 :key="country" 
                 class="flex justify-between items-center p-2 hover:bg-muted rounded-lg">
              <div class="flex items-center gap-2">
                <span class="font-medium">{{ country }}</span>
                <span class="text-xs text-muted-foreground">
                  ({{ stats.percent_total }}%)
                </span>
              </div>
              <div class="flex items-center gap-4">
                <span class="text-sm text-muted-foreground">
                  {{ stats.cities }} cities
                </span>
                <span class="font-bold">
                  {{ stats.visits }} visits
                </span>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Recent Locations Card -->
      <Card>
        <CardHeader>
          <CardTitle>Recent Visitor Locations</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div v-for="location in locationData.visit_locations" 
                 :key="location.location" 
                 class="flex justify-between items-center p-2 hover:bg-muted rounded-lg">
              <div class="flex flex-col">
                <span class="font-medium">{{ location.location }}</span>
                <span v-if="location.last_visit" class="text-xs text-muted-foreground">
                  Last visit: {{ location.last_visit }}
                </span>
              </div>
              <span class="text-sm font-semibold">
                {{ location.count }} visits
              </span>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Stats Summary -->
      <Card class="lg:col-span-2">
        <CardHeader>
          <CardTitle>Location Statistics</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid gap-4 md:grid-cols-3">
            <div class="space-y-2">
              <p class="text-sm font-medium text-muted-foreground">Total Countries</p>
              <p class="text-2xl font-bold">{{ locationData.total_countries }}</p>
            </div>
            <div class="space-y-2">
              <p class="text-sm font-medium text-muted-foreground">Total Cities</p>
              <p class="text-2xl font-bold">{{ locationData.total_cities }}</p>
            </div>
            <div class="space-y-2">
              <p class="text-sm font-medium text-muted-foreground">Top Country</p>
              <p class="text-2xl font-bold">
                {{ Object.keys(locationData.top_countries)[0] || 'N/A' }}
              </p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <div v-else class="text-center py-12">
      <p class="text-muted-foreground">No location data available yet.</p>
    </div>
  </div>
</template> 