interface CityAnalytics {
  rank: number
  city: string
  country: string
  coordinates: {
    lat: number
    lng: number
  }
  visitCount: number
}

interface CitiesPageProps {
  cities: CityAnalytics[]
  totalVisits: number
  totalCities: number
} 