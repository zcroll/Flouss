<template>
  <div class="bg-[#0A1E2E] text-white py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div>
          <h3 class="text-xl font-semibold mb-2 opacity-0 animate-fade-in">{{ __('welcome.discover_compatibility') }}</h3>
          <h2 class="text-3xl font-bold mb-4 opacity-0 animate-fade-in">{{ __('welcome.match_to_careers') }}</h2>
        </div>
        <div class="upgrade-benefits__imgs">
          <div class="text-white rounded-3xl p-6 overflow-hidden"
               :class="['bg-gradient-to-br from-[#132535] via-[#172f44] to-[#18354f]']">
            <div class="relative z-10">
              <div class="flex flex-col items-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4">
                  <img src="https://res.cloudinary.com/hnpb47ejt/image/upload/c_fill,f_auto,h_160,q_auto,w_160/v1679693459/tpd0eh6eu1a6sh4mfqo4.jpg" alt="Icon" class="w-16 h-16 rounded-full">
                </div>
                <h3 class="text-xl font-bold mb-6">{{ __('career.occupation_name') }}</h3>
                <div class="grid grid-cols-2 gap-4 mb-6 w-full">
                  <div class="bg-white bg-opacity-20 rounded-lg text-center p-2">
                    <h5 class="text-md font-bold">Aperçu</h5>
                  </div>
                  <div class="bg-white bg-opacity-5 rounded-lg text-center p-2">
                    <h5 class="text-md font-bold">Environnement de travail</h5>
                  </div>
                  <div class="bg-white bg-opacity-5 rounded-lg text-center p-2">
                    <h5 class="text-md font-bold">Personnalité</h5>
                  </div>
                  <div class="bg-white bg-opacity-5 rounded-lg text-center p-2">
                    <h5 class="text-md font-bold">Comment Devenir</h5>
                  </div>
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                  <Link
                    v-for="(link, index) in hardcodedLinks"
                    :key="index"
                    :href="link.url"
                    :class="{
                      'active': $page.url === link.url,
                      'disabled-link': $page.url === link.url
                    }"
                    :aria-disabled="$page.url === link.url"
                    class="py-2 px-4 bg-white bg-opacity-20 rounded-lg text-center transition-colors duration-200 hover:bg-opacity-30"
                  >
                    <span class="text-sm font-semibold">{{ link.text }}</span>
                  </Link>
                </div>
              </div>
            </div>
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10">
              <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
              <div class="absolute bottom-0 right-0 w-60 h-60 bg-white rounded-full transform translate-x-1/2 translate-y-1/2"></div>
            </div>
          </div>

          <!-- Wrapping div for overflow -->
          <div class="mt-10 w-full lg:w-4/4 space-y-12 px-6 lg:px-16 py-12 bg-white rounded-3xl shadow-2xl max-h-[790px] overflow-y-auto">
            <!-- Main Role Description Section -->
            <section class="space-y-8">
              <h2 class="custom-heading">{{ __('career.what_is_a') }} {{ __('career.occupation_name') }}</h2>
              <div class="space-y-6">
                <div class="space-y-4">
                  <p class="text-md text-gray-700 leading-relaxed">{{ __('career.occupation_description_1') }}</p>
                </div>
                <div class="space-y-4">
                  <p class="text-md text-gray-700 leading-relaxed">{{ __('career.occupation_description_2') }}</p>
                </div>
              </div>
            </section>

            <aside id="table-of-contents-container" class="block">
              <div
                id="table-of-contents"
                class="table-of-contents rounded-lg my-4 w-full bg-[#F8EDE3] text-gray-900 relative p-9"
                role="directory"
                tabindex="0"
                title="Table of contents"
              >
                <p class="custom-heading">
                  {{ __('career.in_this_article') }}
                </p>
                <ol class="list-none m-0 p-0 text-base leading-6 font-light tracking-tight">
                  <li class="relative mb-0 text-xl leading-10 hover:underline">
                    <a href="#responsibilities-duties" class="trait-type">
                      {{ __('career.responsibilities_and_duties') }}
                    </a>
                  </li>
                  <li class="relative mb-0 text-xl leading-10 hover:underline">
                    <a href="#related-jobs" class="trait-type">
                      {{ __('career.related_jobs') }}
                    </a>
                  </li>
                </ol>
              </div>
            </aside>

            <!-- Duties and Responsibilities Section -->
            <section id="responsibilities-duties" class="space-y-8 mt-12">
              <h2 class="text-2xl font-extralight text-gray-900 border-b-2 pb-2 border-gray-200">
                {{ __('career.core_duties_and_responsibilities') }}
              </h2>
              <ul class="list-custom">
                <li
                  v-for="(duty, index) in jobInfoDuties"
                  :key="duty.id"
                  class="text-l text-gray-700 pt-3"
                >
                  {{ duty }}
                </li>
              </ul>
            </section>

            <!-- Job Types Section -->
            <section class="space-y-8 mt-12">
              <h2 id="related-jobs" class="text-2xl font-extralight text-gray-900 border-b-2 pb-2 border-gray-200">
                psychatre
              </h2>
              <div v-for="(jobType, index) in jobInfoTypes" :key="jobType.id" class="space-y-4">
                <h3 class="text-l font-black text-gray-800">{{ jobType.job_title }}</h3>
                <p class="text-md text-gray-700">{{ jobType.description }}</p>
              </div>
            </section>

            <!-- Conclusion Section -->
            <!-- <section class="mt-12 space-y-4 text-center">
              <p class="text-xl text-gray-600">Interested in learning more about this career? Explore our <a href="#" class="text-blue-600 hover:text-blue-500 underline">Career Guide</a>.</p>
            </section> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Section',
  data() {
    return {
      hardcodedLinks: [
        { text: "Aperçu", url: "/overview" },
        { text: "Environnement de travail", url: "/work-environments" },
        { text: "Personnalité", url: "/personality" },
        { text: "Comment devenir", url: "/how-to-become" },
      ],
      jobInfoDuties: [
        "Diagnostiquer les troubles de santé mentale",
        "Élaboration de plans de traitement",
        "Fournir une thérapie",
        "Prescription de médicaments",
        "Gestion des crises",
        "Collaborer avec d'autres professionnels de la santé",
        "Mener des recherches et enseigner"
      ],
      jobInfoTypes: [
        { job_title: "Psychiatres généraux", description: "Les psychiatres généralistes sont des médecins qui prodiguent des soins psychiatriques complets pour un large éventail de problèmes de santé mentale. Ils diagnostiquent et traitent divers troubles, tels que la dépression, l'anxiété, le trouble bipolaire, la schizophrénie et les troubles de la personnalité. Ils peuvent utiliser une combinaison de thérapie et de médicaments pour gérer ces conditions." },
        { job_title: "Psychiatres pour enfants et adolescents", description: "Les psychiatres pour enfants et adolescents sont spécialisés dans le diagnostic et le traitement des troubles de santé mentale chez les enfants et les adolescents. Ils ont une formation spécialisée dans la compréhension des besoins développementaux et psychologiques uniques des jeunes. Ils abordent des problèmes tels que le trouble déficitaire de l'attention/hyperactivité (TDAH), les troubles du spectre autistique, l'anxiété, la dépression, les troubles du comportement et d'autres problèmes de santé mentale spécifiques à ce groupe d'âge." },
        { job_title: "Psychiatres gériatriques", description: "Les psychiatres gériatriques se concentrent sur les besoins en santé mentale des personnes âgées. Ils possèdent une expertise dans le diagnostic et le traitement des problèmes de santé mentale généralement associés au vieillissement, tels que la démence, la maladie d'Alzheimer, la dépression, l'anxiété et la psychose tardive. Les psychiatres gériatriques abordent également les défis et complexités uniques liés à la gestion des médicaments chez les personnes âgées." },
        { job_title: "Psychiatres toxicomanes", description: "Les psychiatres en toxicomanie se spécialisent dans le diagnostic et le traitement des troubles liés à la consommation de substances et des problèmes de santé mentale concomitants. Ils possèdent une expertise dans la compréhension des complexités de la dépendance et de son impact sur le bien-être mental. Les psychiatres en toxicomanie peuvent utiliser une combinaison de thérapies, de médicaments et d'interventions comportementales pour aider les individus à surmonter la toxicomanie et à maintenir leur rétablissement à long terme." },
        { job_title: "Psychiatres légistes", description: "Les psychiatres légistes appliquent leur expertise psychiatrique dans des contextes juridiques et médico-légaux. Ils évaluent et évaluent les individus au sein du système juridique, tels que ceux impliqués dans des affaires pénales ou des litiges civils. Les psychiatres légistes peuvent fournir des témoignages d'experts, procéder à des évaluations de compétences, effectuer des évaluations de risques et donner un aperçu de l'état mental et du comportement des personnes impliquées dans des affaires juridiques." },
        { job_title: "Psychiatres de consultation-liaison", description: "Les psychiatres de consultation-liaison, également appelés psychiatres de médecine psychosomatique, travaillent dans des milieux de médecine générale tels que les hôpitaux. Ils se spécialisent dans l'évaluation et le traitement des problèmes de santé mentale qui surviennent chez les patients souffrant de problèmes médicaux complexes. Ils collaborent avec les équipes médicales pour fournir des soins intégrés, abordant les aspects psychologiques et émotionnels de la santé globale des patients." },
        { job_title: "Neuropsychiatres", description: "Les neuropsychiatres se spécialisent dans l'interface entre la psychiatrie et la neurologie, en se concentrant sur le diagnostic et le traitement des troubles de santé mentale accompagnés d'affections ou de troubles neurologiques sous-jacents. Ils possèdent des connaissances spécialisées dans la compréhension de la relation complexe entre le cerveau et le comportement, combinant une expertise en psychiatrie et en neurologie pour fournir des soins complets." },
      ]
    };
  },
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.animate-fade-in {
  animation: fadeIn 1s ease-out forwards;
}
</style>