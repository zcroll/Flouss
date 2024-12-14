<script setup>
import { ref } from 'vue'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger
} from '@/Components/ui/dropdown-menu'
import { Button } from '@/Components/ui/button'

const user = ref({
  name: 'John Doe',
  email: 'john@example.com',
  image: '/Icons/light/avatar.svg'
})

const handleLogout = () => {
  // Implement logout logic
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger asChild>
      <Button variant="ghost" class="relative h-8 w-8 rounded-full">
        <!-- <img
          :src="user.image"
          :alt="user.name"
          class="h-8 w-8 rounded-full object-cover"
        /> -->
      </Button>
    </DropdownMenuTrigger>
    
    <DropdownMenuContent class="w-56" align="end">
      <DropdownMenuLabel class="font-normal">
        <div class="flex flex-col space-y-1">
          <p class="text-sm font-medium leading-none">{{ user.name }}</p>
          <p class="text-xs leading-none text-muted-foreground">
            {{ user.email }}
          </p>
        </div>
      </DropdownMenuLabel>
      
      <DropdownMenuSeparator />
      
      <DropdownMenuItem>
        Profile
      </DropdownMenuItem>
      <DropdownMenuItem>
        Settings
      </DropdownMenuItem>
      
      <DropdownMenuSeparator />
      
      <DropdownMenuItem @click="handleLogout">
        Log out
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
