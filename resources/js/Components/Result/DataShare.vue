<template>
  <div class="DashboardPage__sharing ">
    <div class="DashboardPage__sharing__wrap">
      <h2 class="DashboardPage__sharing__title">
        {{ __('results.share_results_title') }}<br />{{ __('results.share_results_subtitle') }}
      </h2>
      <ul class="DashboardPage__sharing__list">
        <li class="DashboardPage__sharing__item">
          <a
            class="DashboardPage__sharing__link"
            href="https://www.facebook.com/sharer/sharer.php?t={{ __('results.share_message') }}&u=https%3A%2F%2Fwww.gennz.site%2Fassessments%2Fa39b4eb9-bea7-47ab-85a4-212adad78843%2Fresults%2F%3Fshare%3D309d43c8-2242-471e-986b-7b06982ec4d3"
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
        href=""
      >
        <img
          class="DashboardPage__sharing__preview__image ls-is-cached lazyloaded"
          data-src="/results_folder/share_results.png"
          alt="{{ __('results.preview_image_alt') }}"
          src="/results_folder/share_results.png"
        />
        <label class="DashboardPage__sharing__preview__label">{{ __('results.coming_soon') }}</label>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DataShare',
}
</script>

<style scoped>
.DashboardPage__sharing {
  display: flex;
  gap: 2rem;
  padding: 2rem;
  background: linear-gradient(145deg, #ffffff, #f0f0f0);
  border-radius: 20px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

.DashboardPage__sharing__wrap {
  flex: 1;
}

.DashboardPage__sharing__title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #2d3748;
  margin-bottom: 1.5rem;
  line-height: 1.4;
}

.DashboardPage__sharing__list {
  display: flex;
  gap: 1rem;
  list-style: none;
  padding: 0;
}

.DashboardPage__sharing__item {
  transition: transform 0.2s ease;
}

.DashboardPage__sharing__item:hover {
  transform: translateY(-2px);
}

.DashboardPage__sharing__link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  background: #ffffff;
  border-radius: 50%;
  color: #4a5568;
  box-shadow: 0 4px 6px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}

.DashboardPage__sharing__link:hover {
  background: #4299e1;
  color: white;
}

.DashboardPage__sharing__icon {
  width: 20px;
  height: 20px;
}

.DashboardPage__sharing__preview {
  flex: 1;
  position: relative;
}

.DashboardPage__sharing__preview__image {
  width: 100%;
  height: auto;
  border-radius: 12px;
  transition: transform 0.3s ease;
}

.DashboardPage__sharing__preview__image:hover {
  transform: scale(1.02);
}

.DashboardPage__sharing__preview__label {
  position: absolute;
  bottom: 1rem;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  .DashboardPage__sharing {
    flex-direction: column;
  }
  
  .DashboardPage__sharing__title {
    font-size: 1.5rem;
  }
}
</style>