<script setup>
import Button from '@/Components/Button.vue'
import Container from '@/Components/Container.vue'
import FadeIn from '@/Components/FadeIn.vue'
import { Link } from '@inertiajs/vue3'
// import Offices from '@/Components/Offices.vue'
</script>

<template>
  <Container class="mt-24 sm:mt-32 lg:mt-40 mb-16">
    <FadeIn class="mx-6 rounded-[2rem] bg-[radial-gradient(circle_at_top_left,#FEF0BD_20%,#f5b2c6_40%,#EE8CCA_60%,#c76eeb_80%)] px-6 py-20 sm:mx-0 sm:py-32 md:px-12">
      <div class="mx-auto max-w-4xl">
        <div class="max-w-xl">
          <h2 class="font-display text-3xl font-medium text-black [text-wrap:balance] sm:text-4xl">
            {{ __('navigation.contact_us') }}
          </h2>
          <div class="mt-6 flex">
            <Button :href="route('formations.index')">
              {{ __('navigation.get_started') }}
            </Button>
          </div>
          <div class="mt-10 border-t border-black/10 pt-10 rounded-[2rem]">
            <a
              href="mailto:yahyanireda@gennz.site"
              class="font-display text-base font-semibold text-black hover:underline"
            >
              yahyanireda@gennz.site
            </a>
            <!-- <Offices
              :invert="false" 
              class="mt-6 grid grid-cols-1 gap-8 sm:grid-cols-2"
            /> -->
          </div>
        </div>
      </div>
    </FadeIn>
  </Container>
</template>