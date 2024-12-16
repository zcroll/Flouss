<script setup>
import { onMounted, watch } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';

const props = defineProps({
  locations: {
    type: Array,
    required: true
  }
});

let map;

const initMap = () => {
  if (typeof google === 'undefined') return;
  
  map = new google.maps.Map(document.getElementById('visit-map'), {
    zoom: 2,
    center: { lat: 20, lng: 0 },
    styles: [] // Add your custom map styles here
  });

  // Add markers for each location
  props.locations.forEach(location => {
    new google.maps.Marker({
      position: { lat: location.lat, lng: location.lng },
      map: map,
      title: `${location.location} (${location.count} visits)`
    });
  });
};

onMounted(() => {
  // Load Google Maps API if not already loaded
  if (!window.google) {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY`;
    script.async = true;
    script.defer = true;
    script.onload = initMap;
    document.head.appendChild(script);
  } else {
    initMap();
  }
});

watch(() => props.locations, initMap, { deep: true });
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Visit Locations</CardTitle>
    </CardHeader>
    <CardContent>
      <div id="visit-map" class="w-full h-[400px]"></div>
    </CardContent>
  </Card>
</template> 