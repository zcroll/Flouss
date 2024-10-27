<template>
  <div class="DashboardPage__sharing">
    <div class="DashboardPage__sharing__wrap">
      <h2 class="DashboardPage__sharing__title">
        Share your results<br />with #discoverYou
      </h2>
      <ul class="DashboardPage__sharing__list">
        <li class="DashboardPage__sharing__item">
          <a
            class="DashboardPage__sharing__link"
            href="https://www.facebook.com/sharer/sharer.php?t=I completed the CareerExplorer test and my top career matches are  Research Chef, Food Critic, Forensic Pathologist, Cybercrime Investigator, Cryptographer  and more. Find out yours at CareerExplorer!&u=https%3A%2F%2Fwww.careerexplorer.com%2Fassessments%2Fa39b4eb9-bea7-47ab-85a4-212adad78843%2Fresults%2F%3Fshare%3D309d43c8-2242-471e-986b-7b06982ec4d3"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Share to Facebook"
          >
            <svg
              aria-hidden="true"
              focusable="false"
              data-prefix="fab"
              data-icon="facebook-f"
              class="svg-inline--fa fa-facebook-f fa-fw DashboardPage__sharing__icon"
              role="img"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 320 512"
            >
              <path
                fill="currentColor"
                d="M279.1 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.4 0 225.4 0c-73.22 0-121.1 44.38-121.1 124.7v70.62H22.89V288h81.39v224h100.2V288z"
              ></path>
            </svg>
          </a>
        </li>
        <!-- Add similar list items for other sharing options -->
      </ul>
    </div>
    <div class="DashboardPage__sharing__preview">
      <a
        href="https://www.careerexplorer.com/assessments/a39b4eb9-bea7-47ab-85a4-212adad78843/results/?share=309d43c8-2242-471e-986b-7b06982ec4d3"
      >
        <img
          class="DashboardPage__sharing__preview__image ls-is-cached lazyloaded"
          data-src="https://res.cloudinary.com/hnpb47ejt/image/upload/f_auto,q_auto/v1607555631/Rectangle_328_eu1thr.png"
          alt="Preview of shared page"
          src="https://res.cloudinary.com/hnpb47ejt/image/upload/f_auto,q_auto/v1607555631/Rectangle_328_eu1thr.png"
        />
        <label class="DashboardPage__sharing__preview__label">Preview page</label>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DataShare',
}
</script>