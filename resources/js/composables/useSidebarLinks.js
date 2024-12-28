import { computed } from 'vue';

export function useSidebarLinks(props) {
  const links = computed(() => {
    if (!props.model?.slug) return [];

    const baseUrl = `/${props.type}/${props.model.slug}`;

    const linkConfigs = {
      career: [
        { text: "overview", url: baseUrl },
        { text: "work_environments", url: `${baseUrl}/workEnvironments` },
        { text: "personality", url: `${baseUrl}/personality` },
        { text: "how_to_become", url: `${baseUrl}/how-to-become`, disabled: props.disableStepsLink },
      ],
      degree: [
        { text: "overview", url: baseUrl },
        { text: "how_to_obtain", url: `${baseUrl}/how-to-obtain` },
      ],
      job: [
        { text: "overview", url: baseUrl },
        { text: "requirements", url: `${baseUrl}/requirements` },
        { text: "similar_jobs", url: `${baseUrl}/similar-jobs` },
      ]
    };

    return (linkConfigs[props.type] || []).filter(link => !link.disabled);
  });

  return {
    links
  };
} 