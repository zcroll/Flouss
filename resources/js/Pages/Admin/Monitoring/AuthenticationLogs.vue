<script setup lang="ts">
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { DataTable } from '@/Components/ui/data-table'
import { Button } from '@/Components/ui/button'
import { Calendar } from '@/Components/ui/calendar'
import { useAnalytics } from '@/Composables/useAnalytics'

const { isLoading, updateTimeRange } = useAnalytics()

const columns = [
  {
    accessorKey: 'user.name',
    header: 'User'
  },
  {
    accessorKey: 'login_at',
    header: 'Login Time',
    cell: ({ row }) => new Date(row.getValue('login_at')).toLocaleString()
  },
  {
    accessorKey: 'logout_at',
    header: 'Logout Time',
    cell: ({ row }) => row.getValue('logout_at') 
      ? new Date(row.getValue('logout_at')).toLocaleString()
      : 'Still Active'
  },
  {
    accessorKey: 'ip_address',
    header: 'IP Address'
  },
  {
    accessorKey: 'browser',
    header: 'Browser'
  },
  {
    accessorKey: 'device_type',
    header: 'Device'
  },
  {
    accessorKey: 'login_successful',
    header: 'Status',
    cell: ({ row }) => (
      <span class={row.getValue('login_successful') ? 'text-green-500' : 'text-red-500'}>
        {row.getValue('login_successful') ? 'Success' : 'Failed'}
      </span>
    )
  }
]

const dateRange = ref({
  from: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000),
  to: new Date()
})

const handleDateRangeChange = () => {
  if (dateRange.value.from && dateRange.value.to) {
    updateTimeRange({
      start_date: dateRange.value.from.toISOString().split('T')[0],
      end_date: dateRange.value.to.toISOString().split('T')[0]
    })
  }
}
</script>

<template>
  <Head title="Authentication Logs" />

  <div class="container mx-auto p-6 space-y-6">
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold tracking-tight">Authentication Logs</h1>
      
      <div class="flex items-center gap-4">
        <Calendar v-model="dateRange" mode="range" />
        <Button @click="handleDateRangeChange" :disabled="isLoading">
          Update Range
        </Button>
      </div>
    </div>

    <Card>
      <CardHeader>
        <CardTitle>Login Activity</CardTitle>
      </CardHeader>
      <CardContent>
        <DataTable 
          :columns="columns" 
          :data="analytics?.authentication_logs ?? []"
          :loading="isLoading"
        />
      </CardContent>
    </Card>
  </div>
</template> 