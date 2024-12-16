import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

export function useAnalytics() {
  const page = usePage()
  const analytics = computed(() => page.props.analytics)
  const dateRange = computed(() => page.props.dateRange)
  const filters = computed(() => page.props.filters)
  const isLoading = ref(false)

  const updateTimeRange = async (range: { 
    start_date: string, 
    end_date: string,
    period?: 'daily' | 'weekly' | 'monthly' 
  }) => {
    isLoading.value = true
    
    await router.post(route('admin.analytics.update-time'), range, {
      preserveState: true,
      preserveScroll: true,
      onFinish: () => {
        isLoading.value = false
      }
    })
  }

  return {
    analytics,
    dateRange,
    filters,
    isLoading,
    updateTimeRange
  }
} 