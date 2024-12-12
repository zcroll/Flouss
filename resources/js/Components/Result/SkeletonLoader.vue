<template>
  <div class="DashboardPage__careers">
    <div v-for="n in 4" :key="n" class="bg-white rounded-lg shadow-sm p-6 mb-4">
      <div class="flex items-start space-x-4">
        <!-- Image placeholder -->
        <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0"></div>
        
        <div class="flex-grow">
          <!-- Title placeholder -->
          <div class="h-6 bg-gray-100 rounded w-3/4 mb-3"></div>
          
          <!-- Description placeholder -->
          <div class="space-y-2 mb-3">
            <div class="h-4 bg-gray-100 rounded w-full"></div>
            <div class="h-4 bg-gray-100 rounded w-5/6"></div>
          </div>
          
          <!-- Tags placeholder -->
          <div class="flex flex-wrap gap-2">
            <div class="h-6 bg-gray-100 rounded w-20"></div>
            <div class="h-6 bg-gray-100 rounded w-24"></div>
            <div class="h-6 bg-gray-100 rounded w-16"></div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- "See More" button skeleton -->
    <div class="DashboardPage__careers__buttons">
      <div class="Button DashboardPage__button DashboardPage__button--careers bg-gray-100 w-40 h-10 rounded-lg mx-auto"></div>
    </div>
  </div>
</template> 