<template>
  <div class="gradientblock">
    <section class="home__madefor wf-section">
      <div class="home__madefor__container">
        <h2 class="home__madefor__h2">{{ __('guardien.title') }}<br></h2>
        <div class="home__madefor__grid">
          <div class="successcard">
            <div class="successcard__image">
              <img src="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5ebd8cbdc18f94735f195c21_5e7b93aef10c214efa2ceb13_people-1-p-500.jpeg" alt="Working professionals" loading="lazy" class="lazy-loaded-image">
            </div>
            <div class="successcard__copy">
              <h3 class="successcard__heading">{{ __('guardien.working_professionals_label') }}<br></h3>
              <p class="successcard__p">{{ __('guardien.working_professionals_description') }}</p>
            </div>
          </div>
          <div class="successcard">
            <div class="successcard__image">
              <img src="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5ebd8d3409b0510e3f67a9cd_5e7b93af7941d95d790809cd_people-2-p-500.jpeg" alt="College grads" loading="lazy" class="lazy-loaded-image">
            </div>
            <div class="successcard__copy">
              <h3 class="successcard__heading">{{ __('guardien.college_students_label') }}<br></h3>
              <p class="successcard__p">{{ __('guardien.college_students_description') }}<br></p>
            </div>
          </div>
          <div class="successcard">
            <div class="successcard__image">
              <img src="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5ebd8bc45001bee4e4f9d979_5e7b93afd4a2af368b564b2f_people-3%202.jpg" alt="Career Changers" loading="lazy" class="lazy-loaded-image">
            </div>
            <div class="successcard__copy">
              <h3 class="successcard__heading">{{ __('guardien.career_changers_label') }}<br></h3>
              <p class="successcard__p">{{ __('guardien.career_changers_description') }}<br></p>
            </div>
          </div>
          <div class="successcard">
            <div class="successcard__image">
              <img src="https://uploads-ssl.webflow.com/5ce8520e4bc6885dbf33246c/5ebd8d9d39dec0b1f5ee27ac_5e7b93af7941d9aaa20809dd_people-4%20(1).jpg" alt="High School Students" loading="lazy" class="lazy-loaded-image">
            </div>
            <div class="successcard__copy">
              <h3 class="successcard__heading">{{ __('guardien.high_school_students_label') }}<br></h3>
              <p class="successcard__p">{{ __('guardien.high_school_students_description') }}<br></p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import __ from '@/lang';
</script>
