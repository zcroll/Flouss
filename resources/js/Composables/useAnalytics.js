import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

export function useAnalytics() {
    const page = usePage()
    const isLoading = ref(false)

    const analytics = computed(() => page.props.analytics)
    const dateRange = computed(() => page.props.dateRange)
    const filters = computed(() => page.props.filters)

    const timeAnalysis = computed(() => analytics.value?.time_analysis ?? {
        hourly: {},
        peak_hour: null,
        quiet_hour: null,
        daily_average: 0,
        weekly_comparison: {
            current_week: 0,
            previous_week: 0,
            percentage_change: null
        }
    })

    const updateTimeRange = async (range) => {
        isLoading.value = true
        
        await router.post(route('admin.analytics.update-time'), range, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                isLoading.value = false
            }
        })
    }

    const formatHour = (hour) => {
        return new Date(2000, 0, 1, hour).toLocaleTimeString('en-US', {
            hour: 'numeric',
            hour12: true
        })
    }

    return {
        analytics,
        dateRange,
        filters,
        timeAnalysis,
        isLoading,
        updateTimeRange,
        formatHour
    }
} 