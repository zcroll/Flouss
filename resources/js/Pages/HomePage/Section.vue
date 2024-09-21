<template>
  <div class="bg-[#0A1E2E] text-white py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div>
          <h3 class="text-xl font-semibold mb-2 opacity-0 animate-fade-in">Discover your compatibility</h3>
          <h2 class="text-3xl font-bold mb-4 opacity-0 animate-fade-in">Match to popular and emerging careers</h2>
          <p class="mb-6 opacity-0 animate-fade-in">
            CareerHunter's <strong>unique algorithm</strong> will evaluate your test results and accurately <strong>match you to suitable careers</strong> that you'll not only love but also succeed in.
          </p>
        </div>
        <div class="upgrade-benefits__imgs">
          <picture>
            <source srcset="//cdn0.careerhunter.io/assets/upgrade/benefits-desktop-fc1563fd6a108765f92bce93371cb394c6603097397ba6420f8dc20e12869673.webp" media="(min-width: 1025px)" type="image/webp" />
            <source srcset="//cdn2.careerhunter.io/assets/upgrade/benefits-tablet-baa77d8298b3c050949d1bf6b258751d0b271ff3e4123b33f60dfb652f5231a7.webp" media="(min-width: 641px) and (max-width: 1024px)" type="image/webp" />
            <source srcset="//cdn0.careerhunter.io/assets/upgrade/benefits-mobile-008ea6f4de476c6f34d3d2bac0eacfa13b333e50311cdbcedbdf2fe854d04886.webp" media="(min-width: 320px) and (max-width: 640px)" type="image/webp" />
            <img loading="lazy" alt="Discover your compatibility" class="w-full h-auto rounded-lg shadow-lg opacity-0 animate-fade-in" src="//cdn2.careerhunter.io/assets/upgrade/benefits-desktop-37aacfccaac3b64f126e68ba81e87003d992c0d68f900789be3022494c130569.png" />
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
