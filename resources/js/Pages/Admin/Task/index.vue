<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import search from '@/Components/ADMIN/search.vue'
import themeSwitch from '@/Components/ADMIN/themeSwitch.vue'
import profileDropdown from '@/Components/ADMIN/profileDropdown.vue'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Badge } from '@/Components/ui/badge'
import { Checkbox } from '@/Components/ui/checkbox'
import ThemeProvider from '@/Components/ADMIN/ThemeProvider.vue'
import SideBar from '@/Pages/Admin/SideBar.vue'

defineOptions({
  layout: null
})

// Mock data - replace with actual data from your backend
const tasks = ref([
  {
    id: 'TASK-8782',
    type: 'Documentation',
    title: "You can't compress the program without quantifying the open-source...",
    status: 'In Progress',
    priority: 'Medium',
  },
  {
    id: 'TASK-7878',
    type: 'Documentation',
    title: 'Try to calculate the EXE feed, maybe it will index the multi-byte pixel!',
    status: 'Backlog',
    priority: 'Medium',
  },
  {
    id: 'TASK-7839',
    type: 'Bug',
    title: 'We need to bypass the neural TCP card!',
    status: 'Todo',
    priority: 'High',
  },
  // Add more mock data as needed
])

const selectedTasks = ref(new Set())
const searchQuery = ref('')
const statusFilter = ref('')
const priorityFilter = ref('')

// Computed property for filtered tasks
const filteredTasks = computed(() => {
  return tasks.value.filter(task => {
    const matchesSearch = task.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         task.id.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesStatus = !statusFilter.value || task.status === statusFilter.value
    const matchesPriority = !priorityFilter.value || task.priority === priorityFilter.value
    return matchesSearch && matchesStatus && matchesPriority
  })
})

// Status badge variants
const getStatusVariant = (status) => {
  const variants = {
    'In Progress': 'warning',
    'Backlog': 'secondary',
    'Todo': 'default',
    'Done': 'success',
    'Canceled': 'destructive'
  }
  return variants[status] || 'default'
}

// Priority badge variants
const getPriorityVariant = (priority) => {
  const variants = {
    'High': 'destructive',
    'Medium': 'warning',
    'Low': 'secondary'
  }
  return variants[priority] || 'default'
}

// Type badge variants
const getTypeVariant = (type) => {
  const variants = {
    'Bug': 'destructive',
    'Feature': 'success',
    'Documentation': 'info'
  }
  return variants[type] || 'default'
}

const toggleSelectAll = (checked) => {
  if (checked) {
    selectedTasks.value = new Set(filteredTasks.value.map(task => task.id))
  } else {
    selectedTasks.value.clear()
  }
}

const toggleTaskSelection = (taskId) => {
  if (selectedTasks.value.has(taskId)) {
    selectedTasks.value.delete(taskId)
  } else {
    selectedTasks.value.add(taskId)
  }
}

// Add navigation data
const navigationItems = {
  title: 'MENU',
  items: [
    {
      name: 'Dashboard',
      icon: 'chart-line',
      href: '/adminn/dashboard',
      hasChildren: false
    },
    {
      name: 'Tasks',
      icon: 'list-check',
      href: '/adminn/tasks',
      hasChildren: false
    },
    {
      name: 'Settings',
      icon: 'gear',
      href: '/adminn/settings',
      hasChildren: true,
      items: [
        {
          name: 'Profile',
          icon: 'user',
          href: '/adminn/settings/profile'
        },
        {
          name: 'Security',
          icon: 'shield-check',
          href: '/adminn/settings/security'
        }
      ]
    }
  ]
}

// Define analytics IDs
const ANALYTICS = {
  views: {
    taskList: 'task-list-view',
    taskFilters: 'task-filters-section',
    taskActions: 'task-actions-section'
  },
  buttons: {
    create: 'task-create-button',
    import: 'task-import-button'
  },
  filters: {
    status: 'task-status-filter',
    priority: 'task-priority-filter'
  }
}
</script>

<template>
  <Head title="Tasks" />

  <ThemeProvider v-slot="{ theme, toggleTheme }">
    <div class="min-h-screen bg-background">
      <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex">
          <SideBar v-bind="navigationItems" />
        </div>
        <Search />
        <div className='ml-auto flex items-center space-x-4'>
          <ThemeSwitch />
          <ProfileDropdown />
        </div>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

       
          <!-- Main Content Area -->
          <main class="flex-1 overflow-x-hidden overflow-y-auto bg-background">
            <div class="p-6">
              <!-- Header -->
              <div class="flex justify-between items-center mb-6">
                <div>
                  <h1 class="text-2xl font-bold tracking-tight">Tasks</h1>
                  <p class="text-muted-foreground">Here's a list of your tasks for this month!</p>
                </div>
                <div class="flex items-center space-x-2">
                  <Button 
                    variant="outline"
                    :data-pan="ANALYTICS.buttons.import">
                    <img src="/Icons/light/download.svg" class="h-4 w-4 mr-2" alt="Import" />
                    Import
                  </Button>
                  <Button :data-pan="ANALYTICS.buttons.create">
                    <img src="/Icons/light/plus.svg" class="h-4 w-4 mr-2" alt="Create" />
                    Create
                  </Button>
                </div>
              </div>

              <!-- Filters -->
              <div class="flex items-center justify-between space-x-4 mb-6">
                <div class="flex-1">
                  <Input
                    v-model="searchQuery"
                    placeholder="Filter tasks..."
                    class="max-w-sm"
                  />
                </div>

                <div class="flex items-center space-x-2">
                  <DropdownMenu>
                    <DropdownMenuTrigger 
                      asChild
                      :data-pan="ANALYTICS.filters.status">
                      <Button variant="outline" class="ml-auto">
                        Status
                        <img src="/Icons/light/chevron-down.svg" class="ml-2 h-4 w-4" alt="Status" />
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                      <DropdownMenuItem @click="statusFilter = ''">
                        All
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="statusFilter = 'Todo'">
                        Todo
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="statusFilter = 'In Progress'">
                        In Progress
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="statusFilter = 'Done'">
                        Done
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="statusFilter = 'Canceled'">
                        Canceled
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>

                  <DropdownMenu>
                    <DropdownMenuTrigger 
                      asChild
                      :data-pan="ANALYTICS.filters.priority">
                      <Button variant="outline" class="ml-auto">
                        Priority
                        <img src="/Icons/light/chevron-down.svg" class="ml-2 h-4 w-4" alt="Priority" />
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                      <DropdownMenuItem @click="priorityFilter = ''">
                        All
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="priorityFilter = 'High'">
                        High
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="priorityFilter = 'Medium'">
                        Medium
                      </DropdownMenuItem>
                      <DropdownMenuItem @click="priorityFilter = 'Low'">
                        Low
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>

                  <Button variant="outline">
                    View
                    <img src="/Icons/light/chevron-down.svg" class="ml-2 h-4 w-4" alt="View" />
                  </Button>
                </div>
              </div>

              <!-- Table -->
              <div 
                class="rounded-md border"
                :data-pan="ANALYTICS.views.taskList">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead class="w-12">
                        <Checkbox
                          :checked="selectedTasks.size === filteredTasks.length"
                          :indeterminate="selectedTasks.size > 0 && selectedTasks.size < filteredTasks.length"
                          @update:checked="toggleSelectAll"
                        />
                      </TableHead>
                      <TableHead>Task</TableHead>
                      <TableHead>Status</TableHead>
                      <TableHead>Priority</TableHead>
                      <TableHead></TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    <TableRow v-for="task in filteredTasks" :key="task.id">
                      <TableCell class="w-12">
                        <Checkbox
                          :checked="selectedTasks.has(task.id)"
                          @update:checked="() => toggleTaskSelection(task.id)"
                        />
                      </TableCell>
                      <TableCell>
                        <div class="flex flex-col">
                          <div class="flex items-center space-x-2">
                            <Badge :variant="getTypeVariant(task.type)" class="w-24">
                              {{ task.type }}
                            </Badge>
                            <span class="text-sm text-muted-foreground">{{ task.id }}</span>
                          </div>
                          <span class="font-medium">{{ task.title }}</span>
                        </div>
                      </TableCell>
                      <TableCell>
                        <Badge :variant="getStatusVariant(task.status)">
                          {{ task.status }}
                        </Badge>
                      </TableCell>
                      <TableCell>
                        <div class="flex items-center">
                          <img 
                            :src="`/Icons/light/${task.priority.toLowerCase()}.svg`"
                            class="mr-2 h-4 w-4"
                            :alt="task.priority"
                          />
                          <span>{{ task.priority }}</span>
                        </div>
                      </TableCell>
                      <TableCell>
                        <Button variant="ghost" size="icon">
                          <img src="/Icons/light/more-vertical.svg" class="h-4 w-4" alt="More" />
                        </Button>
                      </TableCell>
                    </TableRow>
                  </TableBody>
                </Table>
              </div>

              <!-- Pagination -->
              <div class="flex items-center justify-between space-x-6 mt-4">
                <span class="text-sm text-muted-foreground">
                  {{ selectedTasks.size }} of {{ filteredTasks.length }} row(s) selected.
                </span>
                <div class="flex items-center space-x-2">
                  <Button
                    variant="outline"
                    size="sm"
                    class="h-8 w-8 p-0"
                  >
                    <span class="sr-only">Go to first page</span>
                    <img src="/Icons/light/chevrons-left.svg" class="h-4 w-4" alt="First" />
                  </Button>
                  <Button
                    variant="outline"
                    size="sm"
                    class="h-8 w-8 p-0"
                  >
                    <span class="sr-only">Go to previous page</span>
                    <img src="/Icons/light/chevron-left.svg" class="h-4 w-4" alt="Previous" />
                  </Button>
                  <Button
                    variant="outline"
                    size="sm"
                    class="h-8 w-8 p-0"
                  >
                    <span class="sr-only">Go to next page</span>
                    <img src="/Icons/light/chevron-right.svg" class="h-4 w-4" alt="Next" />
                  </Button>
                  <Button
                    variant="outline"
                    size="sm"
                    class="h-8 w-8 p-0"
                  >
                    <span class="sr-only">Go to last page</span>
                    <img src="/Icons/light/chevrons-right.svg" class="h-4 w-4" alt="Last" />
                  </Button>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>
  </ThemeProvider>
</template>

<style scoped>
.badge-documentation {
  @apply bg-blue-100 text-blue-800;
}
.badge-bug {
  @apply bg-red-100 text-red-800;
}
.badge-feature {
  @apply bg-green-100 text-green-800;
}
</style>
