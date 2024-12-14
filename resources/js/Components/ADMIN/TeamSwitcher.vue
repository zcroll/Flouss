<script setup>
import { ref } from 'vue'
import { useSidebar } from '@/Components/ui/sidebar'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger
} from '@/Components/ui/dropdown-menu'
import {
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem
} from '@/Components/ui/sidebar'

const props = defineProps({
  teams: {
    type: Array,
    required: true,
    default: () => []
  }
})

const { isMobile } = useSidebar()
const activeTeam = ref(props.teams[0])

const setActiveTeam = (team) => {
  activeTeam.value = team
}
</script>

<template>
  <SidebarMenu>
    <SidebarMenuItem>
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <SidebarMenuButton
            size="lg"
            :class="{'bg-sidebar-accent text-sidebar-accent-foreground': true}"
          >
            <div class="flex aspect-square h-8 w-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
              <img 
                :src="`/Icons/light/${activeTeam.icon}.svg`"
                class="h-4 w-4"
                :alt="activeTeam.name"
              />
            </div>
            <div class="grid flex-1 text-left text-sm leading-tight">
              <span class="truncate font-semibold">
                {{ activeTeam.name }}
              </span>
              <span class="truncate text-xs">{{ activeTeam.description }}</span>
            </div>
            <img 
              src="/Icons/light/angles-up-down.svg"
              class="ml-auto h-4 w-4"
              alt="Toggle menu"
            />
          </SidebarMenuButton>
        </DropdownMenuTrigger>
        <DropdownMenuContent
          class="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
          align="start"
          :side="isMobile ? 'bottom' : 'right'"
          :side-offset="4"
        >
          <DropdownMenuLabel class="text-xs text-muted-foreground">
            Teams
          </DropdownMenuLabel>
          <DropdownMenuItem
            v-for="(team, index) in teams"
            :key="team.name"
            @click="setActiveTeam(team)"
            class="gap-2 p-2"
          >
            <div class="flex h-6 w-6 items-center justify-center rounded-sm border">
              <img 
                :src="`/Icons/light/${team.icon}.svg`"
                class="h-4 w-4 shrink-0"
                :alt="team.name"
              />
            </div>
            {{ team.name }}
            <span class="ml-auto text-xs">⌘{{ index + 1 }}</span>
          </DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem class="gap-2 p-2">
            <div class="flex h-6 w-6 items-center justify-center rounded-md border bg-background">
              <img 
                src="/Icons/light/plus.svg"
                class="h-4 w-4"
                alt="Add team"
              />
            </div>
            <div class="font-medium text-muted-foreground">Add team</div>
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </SidebarMenuItem>
  </SidebarMenu>
</template>
