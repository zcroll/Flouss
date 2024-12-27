<template>
        <link type="text/css" rel="stylesheet" href="https://d5lqosquewn6c.cloudfront.net/static/compiled/styles/deprecated/pages/careers.098b42e5fd20.css">
    <main class="layout--sidebar__body__main" role="document">
      <article aria-labelledby="document-title">
        <section>
          <div id="compatibility-container"
               :data-name="careerName"
               :data-a="careerName"
               :data-slug="careerSlug"
               :data-id="careerId"
               :data-score="compatibilityScore"
               :data-intersect="intersectScore"
               :data-unlocked="isUnlocked"
               :data-article="article"
               :data-subject="subject"
               :data-share-url="shareUrl">

            <div>
              <div class="jenny-card" id="overall">
                <header>
                  <div class="Stars" :aria-label="`${starRating} out of 5`">
                    <!-- Star rating component here -->
                  </div>
                  <h2>You are a {{ compatibilityScore }} star match!</h2>
                  <p>Your compatibility score is determined by your wants and needs, skills and interests, and workplace preferences. The following insights reveal how well-suited you are to a career as a {{ careerName }}.</p>
                  <div class="jenny-card__toolbar">
                    <!-- Toolbar buttons here -->
                  </div>
                </header>

                <div class="sticky-header" :class="{ 'sticky-header--unstuck': !isSticky }">
                  <!-- Venn diagram component here -->
                </div>

                <div id="MatchInsights" data-testid="match-insights" class="match-insights match-insights--loaded">
                  <div class="match-insights__section">
                    <div class="match-insights__cards">
                      <section class="match-insight-cards match-insight-cards--visible">
                        <hr>
                        <h3 class="match-insight-cards__title">
                          The following are qualities that you do not measure high in, but this also makes you a good match. These qualities are not as important for {{ careerName }}:
                        </h3>

                        <!-- Match insight cards component here -->

                      </section>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <div id="back-top-container" data-target="document-title"></div>
      </article>

      <!-- Feedback component here -->

      <aside tabindex="0" aria-labelledby="up-next__title" aria-describedby="up-next-container" class="up-next">
        <h2 id="up-next__title">{{__('test.up_next')}}</h2>
        <div id="up-next-container"
             :data-title="upNextTitle"
             :data-variant="upNextVariant"
             :data-copy="upNextCopy"
             :data-ctalink="upNextCtaLink">
          <!-- Up next card component here -->
        </div>
      </aside>
    </main>
  </template>

  <script>
  export default {
    data() {
      return {
        careerName: 'systems architect',
        careerSlug: 'systems-architect',
        careerId: '9869',
        compatibilityScore: '4.0',
        intersectScore: '80.0',
        isUnlocked: true,
        article: 'a',
        subject: 'you',
        shareUrl: 'https://www.careerexplorer.com/careers/systems-architect/s/2dc3a21b-22d7-44e5-a29d-e999dc511eb0',
        starRating: 4,
        isSticky: false,
        upNextTitle: 'Systems architect overview',
        upNextVariant: 'overview',
        upNextCopy: 'A systems architect designs and oversees the implementation of complex systems and infrastructure solutions within an organization. These professionals possess a deep understanding of both …',
        upNextCtaLink: '/careers/systems-architect/overview/'
      }
    },
    mounted() {
      // Load external script
      const script = document.createElement('script');
      script.src = 'https://d5lqosquewn6c.cloudfront.net/static/compiled/scripts/career-section-compatibility.bundle.b4562f4ecc87.js';
      script.async = true;
      document.body.appendChild(script);
    }
  }
  </script>
