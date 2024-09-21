<template>
  <div class="bg-[#0A1E2E] text-white">
    <div class="relative bg-cover bg-[70%_20%] w-full"
         style="background-image: linear-gradient(rgba(10, 30, 46, 0.4), rgba(10, 30, 46, 0.4)), url('https://cdn3.careerhunter.io/assets/tests/about-interests-50127efa743e66154cbe0823f55ea8f6e89bc208d1692fb4747b4b819b6f8966.jpg')">
      <div class="max-w-[1400px] mx-auto py-[150px] px-4">
        <div class="flex items-center text-xs text-white/50 mb-4">
          <a href="https://www.careerhunter.io/tests" class="text-white/50">Tests</a>
          <div class="mx-1 w-[5px] h-[8px] bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons-7cc80b2d8d0866fe6edfeebcee73b8de768dd87dd98623091ca10af7b72bbe6d.svg')] bg-[-568px_-42px]"></div>
          <span>Career Interests</span>
        </div>

        <div class="flex flex-wrap">
          <div class="w-full lg:w-7/12">
            <div class="flex items-center flex-wrap mb-4">
              <div class="w-[45px] h-[38px] bg-[url('https://cdn2.careerhunter.io/assets/sprites/large_icons-27cb6f771f1a68e026130a616e69b9cf5fa3ef6cbcb9dfcf2b423519ccf2e740.svg')] bg-[-455px_-16px] mr-4"></div>
              <h1 class="text-3xl font-semibold">Career Interests Test</h1>
            </div>

            <div class="flex text-sm text-white/80 mb-5">
              <div class="flex items-center mr-4">
                <span class="w-[18px] h-[19px] bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-266px_-48px] mr-2"></span>
                27 questions
              </div>
              <div class="flex items-center mr-4">
                <span class="w-[18px] h-[18px] bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-302px_-48px] mr-2"></span>
                15 minutes
              </div>
              <div class="flex items-center">
                <span class="w-[17px] h-[18px] bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-336px_-48px] mr-2"></span>
                No time limit
              </div>
            </div>

            <p class="max-w-[518px] text-lg mb-5">
              Discover your areas of interest based on the work activities that you find most interesting and enjoyable.
            </p>
          </div>

          <div class="w-full lg:w-5/12 text-right">
            <div class="flex justify-end items-center mb-6">
              <a href="https://www.careerhunter.io/tests/interests/take"
                 class="button">
                Try for Free
                <span class="inline-block w-[17px] h-[13px] ml-2 bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-258px_-16px] align-middle"></span>
              </a>
              <a href="https://www.careerhunter.io/checkout/interests"
                 class=" button button--green">
                Get Access
                <span class="inline-block w-[20px] h-[15px] ml-2 bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-323px_-15px] align-middle"></span>
              </a>
            </div>

            <p class="text-2xl text-[#59CE61] mb-1">Test price: 150.99 MAD</p>
            <p class="text-sm text-white/50 mb-4">Includes 2 takes</p>
          </div>
        </div>

        <div class="flex justify-between items-center">
          <div class="bg-white/20 rounded-full px-3 py-1 flex items-center">
            <span class="w-4 h-4 bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons-7cc80b2d8d0866fe6edfeebcee73b8de768dd87dd98623091ca10af7b72bbe6d.svg')] bg-[-482px_-33px] mr-2"></span>
            <p class="text-xs text-white/80">Part of a series of tests that match you to suitable careers.</p>
          </div>
          <a href="https://www.careerhunter.io/sample_reports/Career%20Interests%20Sample%20Report%20-%20CareerHunter.pdf"
             target="_blank"
             class="text-sm text-white underline flex items-center">
            View sample report
            <span class="w-4 h-4 ml-1 bg-[url('https://cdn3.careerhunter.io/assets/sprites/small_icons_01-6bcc3606a70b883b3aac6954e60c3487e99faacba6fa7fa27f577aa7cfc7cfb0.svg')] bg-[-397px_-49px]"></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InterestHeader'
}
</script>
