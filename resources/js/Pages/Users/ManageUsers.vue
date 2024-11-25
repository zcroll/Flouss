<template>
  <div class="container mx-auto p-4">
    <!-- Search Form -->
    <div class="mb-4">
      <input 
        type="text" 
        v-model="search" 
        @input="handleSearch"
        placeholder="Search users..."
        class="border p-2 rounded"
      />
    </div>

    <!-- Users List -->
    <div class="space-y-4">
      <div v-for="user in users" :key="user.id" class="border p-4 rounded">
        <h3>{{ user.name }}</h3>
        <p>{{ user.email }}</p>
        <div class="mt-2 space-x-2">
          <button 
            @click="updateUserStatus(user.id, 'active')"
            class="bg-green-500 text-white px-4 py-2 rounded"
          >
            Activate
          </button>
          <button 
            @click="deleteUser(user.id)"
            class="bg-red-500 text-white px-4 py-2 rounded"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const search = ref('')
const users = defineProps({
  users: Array
})

// Example of using router.get with preserveState
const handleSearch = () => {
  router.get(
    '/users', 
    { search: search.value },
    {
      preserveState: true, // Preserve the component state
      preserveScroll: true, // Keep the scroll position
      only: ['users'], // Only refresh the users data
      onBefore: (visit) => {
        console.log('Search starting...', visit)
      },
      onSuccess: (page) => {
        console.log('Search completed!', page)
      },
      onError: (errors) => {
        console.error('Search failed:', errors)
      }
    }
  )
}

// Example of using router.delete with confirmation
const deleteUser = (userId) => {
  router.delete(`/users/${userId}`, {
    onBefore: () => confirm('Are you sure you want to delete this user?'),
    onSuccess: () => {
      // Show success message or handle success
      console.log('User deleted successfully!')
    }
  })
}

// Example of using router.post with data
const updateUserStatus = (userId, status) => {
  router.post(`/users/${userId}/status`, {
    status: status
  }, {
    preserveScroll: true,
    onSuccess: (page) => {
      console.log('Status updated successfully!')
    },
    onError: (errors) => {
      console.error('Failed to update status:', errors)
    }
  })
}
</script> 