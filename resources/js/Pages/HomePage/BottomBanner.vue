<template>
  <link rel="stylesheet" media="all" href="/css/landingPage/index.css" />

  <div class="bottom-banner with-extra-pad">
    <img class="wave-top hideForXsmall before-fade-in fade-in" height="153" width="419" alt="Perfect career wave" src="https://cdn0.careerhunter.io/assets/upgrade/wave-top-48ad3a7b57599dd38621ac0130144bcfd25f03dabb8b48f37038558c9a91557f.png" />
    <img class="wave-top showForXsmall" height="180" width="320" alt="Perfect career wave" src="https://cdn0.careerhunter.io/assets/upgrade/wave-mobile-af782e02c4fdfe8eaa913678aa3624bca76c12dce587114b865525d93cb52433.png" />
    <div class="circle left-large hideForXsmall"></div>
    <div class="circle left-small hideForXsmall"></div>
    <div class="grid-container">
      <div class="grid-x text-center">
        <div class="small-12 cell">
          <h2 class="bottom-banner__headline-small before-fade-in fade-in">
            {{ __('bottom_banner.what_interests_you') }}
          </h2>
          <h4 class="bottom-banner__desc before-fade-in fade-in">
            {{ __('bottom_banner.take_test_description') }}
          </h4>
        </div>
        <div class="small-12 cell">
          <a class="button button--green--large before-fade-in fade-in before-fade-in fade-in" aria-label="Get Started" href="https://www.careerhunter.io/tests/interests/take">
            {{ __('bottom_banner.get_started') }}
            <span class="button__hovered"></span>
          </a>
        </div>
      </div>
      <div class="circle right-large show-for-large"></div>
      <div class="circle right-small show-for-large"></div>
      <div class="circle right-medium show-for-large"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BottomBanner'
}
</script>

<style scoped>
.bottom-banner {
  background-position: center bottom;
  position: relative;
  background-color: rgba(0, 0, 0, 0);
  background-attachment: scroll;
  background-image: radial-gradient(circle at 60% 130%, rgb(44, 57, 70), rgb(43, 56, 70), rgb(42, 55, 69), rgb(43, 56, 70) 0%, rgb(42, 55, 69), rgb(43, 56, 70) 0%, rgb(40, 53, 67), rgb(41, 54, 68), rgb(40, 53, 67) 0%, rgb(30, 42, 58) 0%, rgb(39, 52, 66), rgb(38, 51, 65), rgb(37, 49, 65) 0%, rgb(37, 50, 64) 0%, rgb(36, 49, 64) 0%, rgb(37, 49, 65) 0%, rgb(37, 50, 64) 0%, rgb(30, 42, 58) 0%, rgb(36, 49, 64) 0%, rgb(36, 49, 63), rgb(30, 42, 58) 0%, rgb(36, 48, 64), rgb(36, 49, 63), rgb(35, 48, 63) 0%, rgb(30, 42, 58) 0%, rgb(35, 63, 62) 0%, rgb(35, 48, 62) 0%, rgb(34, 47, 61) 0%, rgb(34, 46, 62), rgb(34, 47, 62), rgb(30, 42, 58) 0%, rgb(33, 47, 61), rgb(34, 46, 50), rgb(33, 46, 60) 0%, rgb(32, 46, 60), rgb(32, 44, 62), rgb(32, 45, 59), rgb(31, 43, 59), rgb(31, 44, 58), rgb(30, 42, 58) 0%, rgb(30, 43, 57), rgb(30, 42, 58), rgb(29, 41, 57), rgb(30, 42, 58), rgb(29, 43, 58) 0%, rgb(30, 42, 58), rgb(29, 41, 57), rgb(28, 40, 56), rgb(27, 39, 55), rgb(24, 36, 52), rgb(23, 37, 52), rgb(24, 36, 52), rgb(23, 36, 51), rgb(22, 34, 50), rgb(22, 35, 50) 70%);
  background-origin: padding-box;
  background-clip: border-box;
  background-size: cover;
  background-repeat: no-repeat;
}

/* Add the rest of the styles here... */
</style>