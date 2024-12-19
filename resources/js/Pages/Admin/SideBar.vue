<script setup>
import { ref, inject } from 'vue'
import { Link } from '@inertiajs/vue3'
import NavGroup from '@/Components/ADMIN/sideBar.vue'
import TeamSwitcher from '@/Components/ADMIN/TeamSwitcher.vue'
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarRail,
  SidebarProvider
} from '@/Components/ui/sidebar'

const { current: theme } = inject('theme')

const sidebarData = ref({
  teams: [
    {
      id: 1,
      name: 'Acme Inc',
      description: 'Enterprise', 
      icon: 'building'
    }
  ],
  navGroups: [
    {
      title: 'GENERAL',
      items: [
        { 
          name: 'Dashboard', 
          icon: 'chart-mixed', 
          href: '/adminn/dashboard',
          hasChildren: true,
          items: [
            { 
              name: 'Overview', 
              href: '/adminn/dashboard',
              icon: 'chart-mixed'
            },
            { 
              name: 'Analytics', 
              href: '/adminn/dashboard/analytics',
              icon: 'chart-bar'
            },
            { 
              name: 'Reports', 
              href: '/adminn/dashboard/reports',
              icon: 'file-lines'
            }
          ]
        },
        { 
          name: 'Tasks', 
          icon: 'list-check', 
          href: '/adminn/tasks',
          isActive: false
        },
        { 
          name: 'Users', 
          icon: 'users', 
          href: '/adminn/users',
          isActive: false
        }
      ]
    },
    {
      title: 'SETTINGS',
      items: [
        { 
          name: 'Settings', 
          icon: 'gear', 
          href: '/adminn/settings', 
          hasChildren: true,
          items: [
            { name: 'General', href: '/adminn/settings/general' },
            { name: 'Security', href: '/adminn/settings/security' },
            { name: 'Notifications', href: '/adminn/settings/notifications' }
          ]
        },
        { 
          name: 'Help Center', 
          icon: 'circle-question', 
          href: '/adminn/help'
        }
      ]
    }
  ],
  user: {
    name: 'satnaing',
    email: 'satnaing@gmail.com',
    avatar: '/path/to/avatar.jpg'
  }
})
</script>

<template>
  <SidebarProvider>
    <Sidebar collapsible="icon" variant="floating" >
      <SidebarHeader>
        <TeamSwitcher :teams="sidebarData.teams" />
      </SidebarHeader>

      <SidebarContent>
        <NavGroup
          v-for="group in sidebarData.navGroups"
          :key="group.title"
          v-bind="group"
        />
      </SidebarContent>

      <SidebarFooter>
        <!-- <NavUser :user="sidebarData.user" /> -->
      </SidebarFooter>

      <SidebarRail />
    </Sidebar>
  </SidebarProvider>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

.admin-sidebar {
  font-family: 'Inter', sans-serif;
  --sidebar-background: hsl(var(--background));
  --sidebar-foreground: hsl(var(--foreground));
  --sidebar-border: hsl(var(--border));
  --sidebar-muted: hsl(var(--muted-foreground));
  --sidebar-width: 280px;
  --sidebar-width-collapsed: 4rem;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
  border-radius: 0.5rem;
  height: 100vh;
  position: sticky;
  top: 0;
  left: 0;
  margin: 0;
  background-color: var(--sidebar-background);
  border-right: 1px solid var(--sidebar-border);
  z-index: 50;
  width: var(--sidebar-width);
  min-width: var(--sidebar-width);
  transition: all 0.2s ease-in-out;
  color: var(--sidebar-foreground);
}

.admin-sidebar[data-variant="floating"] {
  margin: 1rem;
  height: calc(100vh - 2rem);
}

.admin-sidebar[data-state="collapsed"] {
  width: var(--sidebar-width-collapsed);
  min-width: var(--sidebar-width-collapsed);
}
</style>