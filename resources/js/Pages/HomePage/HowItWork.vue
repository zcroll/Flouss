<template>
  <div>
      <NavBar :canLogin="canLogin" :canRegister="canRegister" :laravelVersion="laravelVersion" :phpVersion="phpVersion" />
      <div class="how-science how-science--how" style="background-color: #0A1E2E;">
      <div class="circle top-half hide-for-small-only"></div>
      <div class="circle top-small hide-for-small-only"></div>
      <div class="circle middle-small hide-for-small-only"></div>
      <div class="circle bot-small hide-for-small-only"></div>
      <div class="green-circle top-med hide-for-small-only"></div>
      <div class="grid-container">
        <div class="grid-x align-middle">
          <div class="large-6 medium-12 small-12 cell how-science__left">
            <h2 class="blue-blk__title text-white">
              {{ __('how_it_works.science_behind_tests') }}
            </h2>
            <div class="tutorial-video show-for-small-only ">
              <div>
                <div class="preview-img">
                  <img
                    :alt="__('how_it_works.preview_image_alt')"
                    src="https://cdn0.careerhunter.io/packs/media/components/home/preview-img-6e9ac4a5a3b7af2e8c90b269a03e4a66.png"
                  />
                </div>
              </div>
            </div>
            <p class="text-white">
              {{ __('how_it_works.test_development_description') }}
            </p>
          </div>
          <div class="large-6 medium-12 small-12 cell tutorial-video hide-for-small-only">
            <div>
              <div class="preview-img">
                <img
                  :alt="__('how_it_works.preview_image_alt')"
                  src="https://cdn0.careerhunter.io/packs/media/components/home/preview-img-6e9ac4a5a3b7af2e8c90b269a03e4a66.png"
                  class="rounded-3xl border-4 border-[#fb6302]"
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
import Navbar from '@/Pages/NavBar.vue';
import Footer from '@/Components/helpers/Footer.vue';
import NavBar from "@/Pages/NavBar.vue";

export default {
  name: 'HowItWork',
  components: {
      NavBar,
    Procces,
    Navbar,
    Footer
  },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
        navigation:Array,
    },
};
</script>

<style scoped>
@import url('//cdn2.careerhunter.io/assets/application-349b29af163d5822a096212e2dd202386f20dc4d2f59953f2fd3e210070ad75c.css');
</style>
