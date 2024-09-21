<template>
    <NavBar :canLogin="canLogin" :canRegister="canRegister" :laravelVersion="laravelVersion" :phpVersion="phpVersion" />
    <div class="bg-[#0A1E2E] text-white">
    <InterestHeader />

    <div class="max-w-[1400px] mx-auto py-20 px-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <h2 class="text-2xl font-semibold mb-5">Why take this test?</h2>
          <p class="mb-5 max-w-[525px]">
            The Career Interests Test will help you identify your areas of interest that closely match the activities you enjoy doing in a job. This will help you make better-informed career decisions and choose a job in which you'll find the work interesting and enjoyable.
          </p>
          <ul class="space-y-2">
            <li v-for="point in benefitPoints" :key="point" class="flex items-start">
              <span class="w-[15px] h-[17px] mt-1 mr-2 bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-159px_-48px] flex-shrink-0"></span>
              {{ point }}
            </li>
          </ul>
        </div>

        <div>
          <h2 class="text-2xl font-semibold mb-5">About the test</h2>
          <p class="mb-5 max-w-[525px]">
            The test comprises 135 statements that describe various work activities and duties, presented in sets of five. You'll be asked to rank each set by level of personal preference. At the end of the test, your results will be analyzed to determine your compatibility with CareerHunter's 27 areas of interest, which include engineering, healthcare and entertainment.
          </p>
          <p class="max-w-[525px]">
            The test can be taken for free, but to gain access to your full results and your downloadable report, access must be purchased. The results from this test contribute to your final career matches on CareerHunter, if you choose to proceed to purchase full access.
          </p>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import InterestHeader from "@/Components/InterestHeader.vue";
import Navbar from "@/Components/NavBar.vue";
import Footer from "@/Components/Footer.vue"
import NavBar from "@/Components/NavBar.vue";

export default {
  name: 'Interests',
  components: {
      NavBar,
      Navbar,
    InterestHeader,
    Footer
  },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
        navigation:Array,
    },
  data() {
    return {
      benefitPoints: [
        'Discover which work activities you enjoy most',
        'Reveal if you have any unrealized passions to pursue',
        'Make an informed decision about your career aspirations',
        "Ensure you're pursuing a job or course you enjoy"
      ]
    }
  }
}
</script>





