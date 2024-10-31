<template>
  <section id="steps" class="home__steps wf-section">
    <div class="home__steps__container">
      <h2 class="home__steps__h2">
        {{ __('steps.description') }}<br>
      </h2>
      <div class="home__steps__grid">
        <div class="step">
          <h3 class="step__heading">
            <span class="step__header__span">01</span> {{ __('steps.assessment') }}
          </h3>
          <p class="step__p">
            {{ __('steps.assessment_description') }}
          </p>
        </div>
        <div class="step">
          <h3 class="step__heading">
            <span class="step__header__span">02</span> {{ __('steps.matches') }}
          </h3>
          <p class="step__p">
            {{ __('steps.matches_description') }}<br>
          </p>
        </div>
        <div class="step">
          <h3 class="step__heading">
            <span class="step__header__span">03</span> {{ __('steps.library') }}
          </h3>
          <p class="step__p">
            {{ __('steps.library_description') }}<br>
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import __ from '@/lang';
</script>
