<template>
  <div>
    <Navbar />
    <div class="how-science how-science--how" style="background-color: #f5f5f5;">
      <div class="circle top-half hide-for-small-only"></div>
      <div class="circle top-small hide-for-small-only"></div>
      <div class="circle middle-small hide-for-small-only"></div>
      <div class="circle bot-small hide-for-small-only"></div>
      <div class="green-circle top-med hide-for-small-only"></div>
      <div class="grid-container">
        <div class="grid-x align-middle">
          <div class="large-6 medium-12 small-12 cell how-science__left">
            <h2 class="blue-blk__title text-black">
              The science behind our tests
            </h2>
            <div class="tutorial-video show-for-small-only ">
              <div>
                <div class="preview-img">
                  <img

                    alt="preview image for the tutorial video"
                    src="https://cdn0.careerhunter.io/packs/media/components/home/preview-img-6e9ac4a5a3b7af2e8c90b269a03e4a66.png"
                  />
                </div>
              </div>
            </div>
            <p class="text-black">
              Each of CareerHunter's six tests were professionally developed
              by a team of experienced psychologists, psychometricians and
              career experts, using scientific sampling protocols and
              methodologies.
            </p>
            <p class="text-black">
              Our innovative and proprietary technology has undergone
              extensive validity and reliability testing, and our tests and
              their output are continually monitored for accuracy to provide
              users with reliable insights.
            </p>
          </div>
          <div class="large-6 medium-12 small-12 cell tutorial-video hide-for-small-only">
            <div>
              <div class="preview-img">
                <img
                  alt="preview image for the tutorial video"
                  src="https://cdn0.careerhunter.io/packs/media/components/home/preview-img-6e9ac4a5a3b7af2e8c90b269a03e4a66.png"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Procces />
    <Footer />
  </div>
</template>

<script>
import Procces from './Procces.vue';
import Navbar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
export default {
  name: 'HowItWork',
  components: {
    Procces,
    Navbar,
    Footer
  }
};
</script>

<style scoped>
@import url('//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css');
</style>
