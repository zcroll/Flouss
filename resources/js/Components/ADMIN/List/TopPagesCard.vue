<script setup>
import { computed } from 'vue'
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar'
import { Button } from '@/Components/ui/button'
import { ScrollArea } from '@/Components/ui/scroll-area'
import { 
  Card, 
  CardContent, 
  CardDescription, 
  CardFooter, 
  CardHeader, 
  CardTitle 
} from '@/Components/ui/card'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'

const props = defineProps({
  data: {
    type: Array,
    required: true,
    default: () => []
  },
  maxHeight: {
    type: String,
    default: '400px'
  }
})

const emits = defineEmits(['view-all'])

// Format visit count with K/M suffix for large numbers
const formatVisitCount = (count) => {
  if (count >= 1000000) {
    return `${(count / 1000000).toFixed(1)}M`
  }
  if (count >= 1000) {
    return `${(count / 1000).toFixed(1)}K`
  }
  return count.toString()
}

// Get first letters for avatar fallback
const getInitials = (path) => {
  return path
    .split('/')
    .filter(Boolean)
    .slice(-1)[0]
    .split('-')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

// Format the path for display
const formatPath = (path) => {
  return path
    .split('/')
    .filter(Boolean)
    .map(segment => segment.replace(/-/g, ' '))
    .join(' / ')
}

// Format timestamp
const formatDate = (timestamp) => {
  return new Date(timestamp).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Sort pages by visit count
const sortedPages = computed(() => 
  [...props.data].sort((a, b) => b.visit_count - a.visit_count)
)
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Most Visited Pages</CardTitle>
      <CardDescription>
        Top {{ data.length }} pages by visit count
      </CardDescription>
    </CardHeader>

    <CardContent>
      <ScrollArea class="h-[350px]">
        <!-- Empty State -->
        <div 
          v-if="data.length === 0" 
          class="flex flex-col items-center justify-center h-[300px] text-center"
        >
          <div class="text-muted-foreground">
            <i class="fa-regular fa-file text-4xl mb-2"></i>
            <p class="mt-2">No page visits recorded yet</p>
          </div>
        </div>

        <!-- Pages Table -->
        <div v-else>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead class="w-[60%]">Page</TableHead>
                <TableHead class="w-[25%]">Last</TableHead>
                <TableHead class="w-[15%] text-right">Visits</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow 
                v-for="page in sortedPages" 
                :key="page.path"
                class="group"
              >
                <TableCell>
                  <div class="flex items-center gap-3">
                    <Avatar class="h-8 w-8 shrink-0">
                      <AvatarImage 
                        :src="`/icons/${getInitials(page.path).toLowerCase()}.png`" 
                        :alt="formatPath(page.path)"
                      />
                      <AvatarFallback class="bg-primary/10 text-sm">
                        {{ getInitials(page.path) }}
                      </AvatarFallback>
                    </Avatar>
                    <div class="flex flex-col min-w-0">
                      <span class="text-sm font-medium truncate">
                        {{ formatPath(page.path) }}
                      </span>
                      <span class="text-xs text-muted-foreground truncate">
                        /{{ page.path }}
                      </span>
                    </div>
                  </div>
                </TableCell>
                <TableCell>
                  <div class="text-sm text-muted-foreground">
                    {{ formatDate(page.last_visit_at).split(',')[0] }}
                  </div>
                </TableCell>
                <TableCell class="text-right">
                  <div class="font-medium text-sm">
                    {{ formatVisitCount(page.visit_count) }}
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </ScrollArea>
    </CardContent>

    <CardFooter v-if="data.length > 0" class="flex justify-center border-t">
      <Button 
        variant="ghost" 
        class="w-full" 
        @click="$emit('view-all')"
      >
        View All Pages
      </Button>
    </CardFooter>
  </Card>
</template>

<style scoped>
/* Table cell styling */
:deep(th),
:deep(td) {
  padding: 0.75rem 1rem;
}

/* Hover states */
:deep(.group:hover) {
  background-color: hsl(var(--muted));
}

:deep(.group:hover) .text-muted-foreground {
  color: hsl(var(--foreground));
}

/* Fixed header */
:deep(thead) {
  position: sticky;
  top: 0;
  z-index: 1;
  background-color: hsl(var(--background));
  border-bottom: 1px solid hsl(var(--border));
}

/* Scrollbar styling */
:deep(.scrollbar) {
  width: 4px !important;
  height: 0 !important; /* Hide horizontal scrollbar */
}

:deep(.scrollbar-thumb) {
  background-color: hsl(var(--muted));
  border-radius: 9999px;
}

:deep(.scrollbar-thumb:hover) {
  background-color: hsl(var(--muted-foreground));
}

/* Table layout */
:deep(table) {
  width: 100%;
  table-layout: fixed;
  border-collapse: collapse;
}

:deep(tr:not(:last-child)) {
  border-bottom: 1px solid hsl(var(--border));
}

/* Text truncation */
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>