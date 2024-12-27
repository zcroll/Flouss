<template>
  <DropdownMenu>
    <DropdownMenuTrigger class="relative group">
      <Bell :class="[
        'h-6 w-6 group-hover:scale-110 transition-all duration-300',
        themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
      ]" />
      <span v-if="unreadCount > 0" :class="[
        'absolute -top-1 -right-1 h-3 w-3 rounded-full',
        `bg-${themeStore.currentTheme.primary}-500`
      ]" />
    </DropdownMenuTrigger>

    <DropdownMenuContent :class="[
      'w-80 backdrop-blur-xl border-none shadow-lg',
      themeStore.isDarkMode
        ? 'bg-gray-900/40 text-gray-100'
        : 'bg-white/40 text-gray-900'
    ]">
      <DropdownMenuLabel class="font-normal">
        <div class="flex flex-col space-y-1">
          <p class="text-sm font-medium leading-none">Notifications</p>
          <p :class="[
            'text-xs leading-none',
            themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
          ]">
            You have {{ unreadCount }} unread messages
          </p>
        </div>
      </DropdownMenuLabel>
      <DropdownMenuSeparator :class="[
        themeStore.isDarkMode ? 'bg-gray-700' : 'bg-gray-200'
      ]" />

      <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
        <div v-if="notifications.length === 0" class="py-6 text-center">
          <div class="flex justify-center">
            <Bell :class="[
              'h-12 w-12',
              themeStore.isDarkMode ? 'text-gray-600' : 'text-gray-400'
            ]" />
          </div>
          <p :class="[
            'mt-2 text-sm',
            themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
          ]">No notifications yet</p>
        </div>

        <DropdownMenuItem v-for="notification in notifications" :key="notification.id" :class="[
          'flex items-center gap-4 p-4 hover:backdrop-blur-lg transition-all duration-300',
          themeStore.isDarkMode
            ? `hover:bg-${themeStore.currentTheme.primary}-900/20`
            : `hover:bg-${themeStore.currentTheme.primary}-50/50`
        ]">
          <div :class="[
            'flex h-2 w-2 rounded-full',
            notification.read
              ? themeStore.isDarkMode ? 'bg-gray-600' : 'bg-gray-300'
              : `bg-${themeStore.currentTheme.primary}-500`
          ]" />
          <div class="flex flex-col gap-1 flex-1 min-w-[100px]">
            <p class="text-sm font-medium leading-none">{{ notification.title }}</p>
            <p :class="[
              'text-xs',
              themeStore.isDarkMode ? 'text-gray-400' : 'text-gray-500'
            ]">{{ notification.message }}</p>
            <p :class="[
              'text-xs',
              themeStore.isDarkMode ? 'text-gray-500' : 'text-gray-400'
            ]">{{ notification.time }}</p>
          </div>
        </DropdownMenuItem>
      </div>

      <DropdownMenuSeparator :class="[
        themeStore.isDarkMode ? 'bg-gray-700' : 'bg-gray-200'
      ]" />
      <DropdownMenuItem :class="[
        'w-full cursor-pointer hover:backdrop-blur-lg transition-all duration-300',
        themeStore.isDarkMode
          ? `hover:bg-${themeStore.currentTheme.primary}-900/20`
          : `hover:bg-${themeStore.currentTheme.primary}-50/50`
      ]">
        <button class="w-full text-center" @click="markAllAsRead">
          Mark all as read
        </button>
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>

<script setup>
import { ref } from 'vue'
import { Bell } from 'lucide-vue-next'
import { useThemeStore } from '@/stores/theme/themeStore'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'

const themeStore = useThemeStore()

// Mock data - replace with real data from your backend
const unreadCount = ref(3)
const notifications = ref([
  {
    id: 1,
    title: 'New Message',
    message: 'You have a new message from John Doe',
    time: '5 minutes ago',
    read: false
  },
  {
    id: 2,
    title: 'Profile Update',
    message: 'Your profile has been updated successfully',
    time: '1 hour ago',
    read: false
  },
  {
    id: 3,
    title: 'System Update',
    message: 'System maintenance scheduled for tomorrow',
    time: '2 hours ago',
    read: false
  }
])

const markAllAsRead = () => {
  notifications.value = notifications.value.map(notification => ({
    ...notification,
    read: true
  }))
  unreadCount.value = 0
}
</script>

<style scoped>
.dropdown-menu-content {
  max-height: 400px;
  overflow-y: auto;
}

/* Custom Scrollbar Styles */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: var(--scrollbar-color) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: var(--scrollbar-color);
  border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: var(--scrollbar-hover-color);
}
</style>