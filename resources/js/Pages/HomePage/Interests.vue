<template>
    <NavBar :canLogin="canLogin" :canRegister="canRegister" :laravelVersion="laravelVersion" :phpVersion="phpVersion" />
    <div class="bg-[#0A1E2E] text-white">
    <InterestHeader />

    <div class="max-w-[1400px] mx-auto py-20 px-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <h2 class="text-2xl font-semibold mb-5">{{ __('interests.why_take_test') }}</h2>
          <p class="mb-5 max-w-[525px]">
            {{ __('interests.test_description') }}
          </p>
          <ul class="space-y-2">
            <li v-for="point in benefitPoints" :key="point" class="flex items-start">
              <span class="w-[15px] h-[17px] mt-1 mr-2 bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-159px_-48px] flex-shrink-0"></span>
              {{ __(`interests.benefit_points.${point}`) }}
            </li>
          </ul>
        </div>

        <div>
          <h2 class="text-2xl font-semibold mb-5">{{ __('interests.about_the_test') }}</h2>
          <p class="mb-5 max-w-[525px]">
            {{ __('interests.test_details') }}
          </p>
          <p class="max-w-[525px]">
            {{ __('interests.test_access_info') }}
          </p>
        </div>
      </div>
    </div>
    <MainInterest />
    <ActivityProgressExample />
    <Industries />
    <BottomBanner />
    <Footer />
  </div>
</template>

<script>
import InterestHeader from "@/Components/InterestHeader.vue";
import Navbar from "@/Components/NavBar.vue";
import Footer from "@/Components/Footer.vue"
import MainInterest from "@/Pages/HomePage/MainInterest.vue"; 
import Industries from "@/Pages/HomePage/Industries.vue";
import NavBar from "@/Components/NavBar.vue";
import ActivityProgressExample from "@/Pages/HomePage/ActivityProgressExample.vue";
import BottomBanner from "@/Pages/HomePage/BottomBanner.vue";

export default {
  name: 'Interests',
  components: {
    NavBar,
    Navbar,
    MainInterest,
    Industries,
    InterestHeader,
    BottomBanner,
    ActivityProgressExample,  
    Footer
  },
  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    navigation: Array,
  },
  data() {
    return {
      benefitPoints: [
        'discover_activities',
        'reveal_passions',
        'informed_decision',
        'ensure_enjoyment'
      ]
    }
  }
}
</script>





