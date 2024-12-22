<template>
  <Head>
    <meta name="description" content="We are a development studio working at the intersection of design and technology." />
  </Head>

  <!-- Header Section -->
  <div class="absolute left-0 right-0 top-2 z-40 pt-14">
    <Container>
      <div class="flex items-center justify-between">
        <Link
          href="/"
        >
          <Logo
            class="hidden sm:block"
            :invert="invert"
            :filled="logoHovered"
          />
        </Link>
        <div class="flex items-center gap-x-8">
          <template v-if="$page.props.auth?.user">
            <Button :href="route('dashboard')">
              {{__('navigation.dashboard')}}
            </Button>
          </template>
          <template v-else>
            <Button :href="route('holland-codes.index')">
              {{__('navigation.take_test')}}
            </Button>
          </template>
        </div>
      </div>
    </Container>
  </div>

  <Container class="mt-24 sm:mt-32 md:mt-56">
    <FadeIn class="max-w-3xl">
      <h1 class="font-display text-5xl font-medium tracking-tight text-neutral-950 [text-wrap:balance] sm:text-7xl">
        {{ __('navigation.unlock_future') }}
      </h1>
      <p class="mt-6 text-xl text-neutral-600">
        {{ __('navigation.splash_callout') }}   </p>
    </FadeIn>
  </Container>

  <!-- Clients Section -->
  <div class="mt-24 rounded-4xl bg-neutral-950 py-20 sm:mt-32 sm:py-32 lg:mt-56">
    <Container>
      <FadeIn class="flex items-center gap-x-8">
        <h2 class="text-center font-display text-sm font-semibold tracking-wider text-white sm:text-left">
          {{ __('navigation.assests') }}
        </h2>
        <div class="h-px flex-auto bg-neutral-800" />
      </FadeIn>
      
      <FadeInStagger :faster="true">
        <ul role="list" class="mt-10 grid grid-cols-2 gap-x-8 gap-y-10 lg:grid-cols-4">
          <li>
            <FadeIn class="flex flex-col items-center">
              <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <div class="text-3xl font-bold text-white">
                <FadeIn 
                  :count-to="1000" 
                  prefix="+"
                  :duration="2000"
                />
              </div>
              <div class="text-sm text-white/70 mt-1">{{ __('navigation.degrees') }}</div>
            </FadeIn>
          </li>

          <li>
            <FadeIn class="flex flex-col items-center">
              <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div class="text-3xl font-bold text-white">
                <FadeIn 
                  :count-to="1500" 
                  prefix="+"
                  :duration="2000"
                />
              </div>
              <div class="text-sm text-white/70 mt-1">{{ __('navigation.formations') }}</div>
            </FadeIn>
          </li>

          <li>
            <FadeIn class="flex flex-col items-center">
              <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <div class="text-3xl font-bold text-white">
                <FadeIn 
                  :count-to="1200" 
                  prefix="+"
                  suffix=""
                  :duration="2000"
                />
              </div>
              <div class="text-sm text-white/70 mt-1">{{ __('navigation.jobs') }}</div>
            </FadeIn>
          </li>

          <li>
            <FadeIn class="flex flex-col items-center">
              <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7m-9-7v7m18-7v7" />
                </svg>
              </div>
              <div class="text-3xl font-bold text-white">
                <FadeIn 
                  :count-to="200" 
                  prefix="+"
                  suffix=""
                  :duration="2000"
                />
              </div>
              <div class="text-sm text-white/70 mt-1">{{ __('navigation.schools') }}</div>
            </FadeIn>
          </li>
        </ul>
      </FadeInStagger>
    </Container>
  </div>



  <!-- Testimonial Section -->
  <Testimonial>
  {{ __('navigation.testimonial') }}
  </Testimonial>

  <!-- Services Section -->
  <div>
    <SectionIntro
      :eyebrow="__('navigation.personnality_test')"
      :title="__('navigation.services')"
      class="mt-24 sm:mt-32 lg:mt-40"
    >
      <p>
{{ __('navigation.services_description') }}      </p>
    </SectionIntro>

    <Container class="mt-16">
      <div class="lg:flex lg:items-center lg:justify-end">
        <div class="flex justify-center lg:w-1/2 lg:justify-end lg:pr-12">
          <FadeIn class="w-[33.75rem] flex-none lg:w-[45rem]">
            <StylizedImage
              :src="imageLaptop"
              sizes="(min-width: 1024px) 41rem, 31rem"
              class="justify-center lg:justify-end"
            />
          </FadeIn>
        </div>
        <List class="mt-16 lg:mt-0 lg:w-1/2 lg:min-w-[33rem] lg:pl-4">
          <ListItem :title="__('navigation.title_item_1')">
            {{ __('navigation.item_1') }}
          </ListItem>
          <ListItem :title="__('navigation.title_item_2')">
            {{ __('navigation.item_2') }}
          </ListItem>
          <ListItem :title="__('navigation.title_item_3')">
            {{ __('navigation.item_3') }}
          </ListItem>
          <ListItem :title="__('navigation.title_item_4')">
            {{ __('navigation.item_4') }}
          </ListItem>
        </List>
      </div>
    </Container>
  </div>

  <!-- Contact Section -->
  <ContactSection />
  <Footer />

</template>

<script setup>
import { ref, onMounted, h, computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import ContactSection from '@/Components/ContactSection.vue'
import Container from '@/Components/Container.vue'
import FadeIn from '@/Components/FadeIn.vue'
import FadeInStagger from '@/Components/FadeInStagger.vue'
import List from '@/Components/List.vue'
import ListItem from '@/Components/ListItem.vue'
import SectionIntro from '@/Components/SectionIntro.vue'
import StylizedImage from '@/Components/StylizedImage.vue'
import Testimonial from '@/Components/Testimonial.vue'
import Button from '@/Components/Button.vue'
import Logo from '@/Components/Logo.vue'
import Footer from '@/Components/helpers/Footer.vue'

import imageLaptop from '/public/landingPage/home/woman_home.jpeg'

import { loadCaseStudies } from '@/lib/mdx'

// Navigation state
const logoHovered = ref(false)
const expanded = ref(false)
const invert = ref(false)


// Methods
const onToggle = () => {
  expanded.value = !expanded.value
}

// Icon component
const icon = {
  render() {
    return h('svg', {
      viewBox: '0 0 24 24',
      'aria-hidden': 'true',
    }, [
      h('path', { d: 'M2 6h20v2H2zM2 16h20v2H2z' })
    ])
  }
}



const caseStudies = ref([])

// Safely access auth user with computed property
const isAuthenticated = computed(() => {
  return !!usePage().props.auth?.user
})

onMounted(async () => {
  try {
    // Load first 3 case studies
    const allCaseStudies = await loadCaseStudies()
    caseStudies.value = allCaseStudies.slice(0, 3)
  } catch (error) {
    console.error('Failed to load case studies:', error)
    caseStudies.value = []
  }
})
</script>

