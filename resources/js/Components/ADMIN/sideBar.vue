<script setup>
import { ref, computed, inject, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger
} from '@/Components/ui/collapsible'
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarMenuSub,
  SidebarMenuSubButton,
  SidebarMenuSubItem,
  useSidebar
} from '@/Components/ui/sidebar'
import { Badge } from '@/Components/ui/badge'
import { useAdminTabs } from '@/Composables/useAdminTabs'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  items: {
    type: Array,
    required: true
  }
})

const { state } = useSidebar()
const isOpen = ref(false)
const { activeSidebarItem, handleSidebarNavigation } = useAdminTabs()

const checkIsActive = (item) => {
  return activeSidebarItem.value === item.href || 
    (item.items && item.items.some(subItem => activeSidebarItem.value === subItem.href))
}

const handleNavigation = (item, e) => {
  e.preventDefault()
  handleSidebarNavigation(item.href)
  router.visit(item.href, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  })
}

// Watch for URL changes to update active state
watch(() => window.location.pathname, (newPath) => {
  if (newPath !== activeSidebarItem.value) {
    handleSidebarNavigation(newPath)
  }
})

const { current: theme } = inject('theme')
</script>

<template>
  <SidebarGroup>
    <SidebarGroupLabel class="sidebar-title">{{ title }}</SidebarGroupLabel>
    <SidebarMenu>
      <template v-for="item in items" :key="item.name">
        <!-- Regular menu item without children -->
        <SidebarMenuItem v-if="!item.hasChildren">
          <Link
            :href="item.href"
            :class="[
              'sidebar-item',
              checkIsActive(item) ? 'active' : ''
            ]"
            @click="handleNavigation(item, $event)"
          >
            <img 
              v-if="item.icon" 
              :src="`/Icons/${theme === 'dark' ? 'light' : 'light'}/${item.icon}.svg`" 
              :alt="item.name"
              class="h-4 w-4"
              :style="{ filter: theme === 'dark' ? 'invert(1)' : 'none' }"
            />
            <span class="ml-3">{{ item.name }}</span>
            <Badge 
              v-if="item.badge"
              variant="secondary"
              class="ml-auto text-xs"
            >
              {{ item.badge }}
            </Badge>
          </Link>
        </SidebarMenuItem>

        <!-- Collapsible menu item with children -->
        <Collapsible
          v-else
          v-model="isOpen"
          class="group/collapsible"
        >
          <CollapsibleTrigger>
            <div class="sidebar-item">
              <img 
                v-if="item.icon" 
                :src="`/Icons/${theme === 'dark' ? 'light' : 'light'}/${item.icon}.svg`" 
                :alt="item.name"
                class="h-4 w-4"
                :style="{ filter: theme === 'dark' ? 'invert(1)' : 'none' }"
              />
              <span class="ml-3">{{ item.name }}</span>
              <Badge 
                v-if="item.badge"
                variant="secondary"
                class="ml-auto text-xs"
              >
                {{ item.badge }}
              </Badge>
              <img 
                :src="`/Icons/${theme === 'dark' ? 'light' : 'light'}/chevron-right.svg`" 
                alt="chevron"
                class="ml-auto h-4 w-4 transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                :style="{ filter: theme === 'dark' ? 'invert(1)' : 'none' }"
              />
            </div>
          </CollapsibleTrigger>

          <CollapsibleContent class="CollapsibleContent">
            <SidebarMenuSub>
              <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.name">
                <Link
                  :href="subItem.href"
                  :class="[
                    'sidebar-subitem',
                    checkIsActive(subItem) ? 'active' : ''
                  ]"
                  @click="handleNavigation(subItem, $event)"
                >
                  <img 
                    v-if="subItem.icon" 
                    :src="`/Icons/${theme === 'dark' ? 'light' : 'sharp-solid'}/${subItem.icon}.svg`" 
                    :alt="subItem.name"
                    class="h-4 w-4"
                    :style="{ filter: theme === 'dark' ? 'invert(1)' : 'none' }"
                  />
                  <span class="ml-3">{{ subItem.name }}</span>
                  <Badge 
                    v-if="subItem.badge"
                    variant="secondary"
                    class="ml-auto text-xs"
                  >
                    {{ subItem.badge }}
                  </Badge>
                </Link>
              </SidebarMenuSubItem>
            </SidebarMenuSub>
          </CollapsibleContent>
        </Collapsible>
      </template>
    </SidebarMenu>
  </SidebarGroup>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

.h-4 {
  height: 1rem;
}
.w-4 {
  width: 1rem;
}

/* Typography and Styling */
.sidebar-title {
  font-family: 'Inter', sans-serif;
  font-weight: 600;
  font-size: 0.875rem;
  letter-spacing: -0.025em;
  color: var(--sidebar-muted);
  text-transform: uppercase;
  padding: 0.5rem 0.75rem;
  margin-bottom: 0.5rem;
}

.sidebar-item {
  font-family: 'Inter', sans-serif;
  font-weight: 500;
  font-size: 0.9375rem;
  letter-spacing: -0.015em;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  width: 100%;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  margin: 0.125rem 0;
  color: var(--sidebar-foreground);
  text-decoration: none;
  position: relative;
}

.sidebar-item:hover {
  background-color: hsl(var(--primary) / 0.1);
  color: hsl(var(--primary));
}

.sidebar-item.active {
  font-weight: 600;
  background-color: hsl(var(--primary) / 0.15);
  color: hsl(var(--primary));
}

.sidebar-item.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background-color: hsl(var(--primary));
  border-top-right-radius: 3px;
  border-bottom-right-radius: 3px;
}

.sidebar-subitem {
  font-family: 'Inter', sans-serif;
  font-weight: 400;
  font-size: 0.875rem;
  letter-spacing: -0.01em;
  opacity: 0.9;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  width: 100%;
  padding: 0.5rem 0.75rem;
  padding-left: 2.5rem;
  border-radius: 0.375rem;
  margin: 0.125rem 0;
  color: var(--sidebar-foreground);
  text-decoration: none;
  position: relative;
}

.sidebar-subitem:hover {
  opacity: 1;
  background-color: hsl(var(--primary) / 0.1);
  color: hsl(var(--primary));
}

.sidebar-subitem.active {
  font-weight: 500;
  opacity: 1;
  background-color: hsl(var(--primary) / 0.15);
  color: hsl(var(--primary));
}

.sidebar-subitem.active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background-color: hsl(var(--primary));
  border-top-right-radius: 3px;
  border-bottom-right-radius: 3px;
}

.CollapsibleContent {
  overflow: hidden;
}

.CollapsibleContent[data-state='open'] {
  animation: slideDown 200ms ease-out;
}

.CollapsibleContent[data-state='closed'] {
  animation: slideUp 200ms ease-out;
}

@keyframes slideDown {
  from {
    height: 0;
  }
  to {
    height: var(--radix-collapsible-content-height);
  }
}

@keyframes slideUp {
  from {
    height: var(--radix-collapsible-content-height);
  }
  to {
    height: 0;
  }
}

/* Active item icon color */
.sidebar-item.active img,
.sidebar-subitem.active img {
  filter: brightness(0) saturate(100%) invert(37%) sepia(74%) saturate(1045%) hue-rotate(206deg) brightness(91%) contrast(101%);
}
</style>
