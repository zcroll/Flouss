<template>
  <div class="bg-[#0A1E2E] text-white py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div>
          <h3 class="text-xl font-semibold mb-2 opacity-0 animate-fade-in">{{ __('welcome.discover_compatibility') }}</h3>
          <h2 class="text-3xl font-bold mb-4 opacity-0 animate-fade-in">{{ __('welcome.match_to_careers') }}</h2>
          <p class="mb-6 opacity-0 animate-fade-in" v-html="__('welcome.unique_algorithm')"></p>
        </div>
        <div class="upgrade-benefits__imgs">
          <picture>
            <!-- ... (image sources remain unchanged) ... -->
            <img loading="lazy" :alt="__('welcome.discover_compatibility')" class="w-full h-auto rounded-lg shadow-lg opacity-0 animate-fade-in" src="//cdn2.careerhunter.io/assets/upgrade/benefits-desktop-37aacfccaac3b64f126e68ba81e87003d992c0d68f900789be3022494c130569.png" />
          </picture>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Section'
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.animate-fade-in {
  animation: fadeIn 1s ease-out forwards;
}
</style>
