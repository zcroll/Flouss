// Simple case studies loader
export async function loadCaseStudies() {
  // This is a mock implementation - replace with your actual data loading logic
  return [
    {
      client: 'Phobia',
      title: 'Overcoming phobias through virtual reality',
      description: 'We helped Phobia create an immersive VR platform that helps people overcome their fears in a safe, controlled environment.',
      logo: '/images/clients/phobia/logo-light.svg',
      date: '2023-01',
      href: '/case-studies/phobia'
    },
    {
      client: 'Family Fund',
      title: 'Modern financial planning for families',
      description: 'Family Fund revolutionizes how families plan and save for their future with our innovative platform.',
      logo: '/images/clients/family-fund/logo-light.svg',
      date: '2023-03',
      href: '/case-studies/family-fund'
    },
    {
      client: 'Unseal',
      title: 'Secure document sharing made simple',
      description: 'We helped Unseal develop a secure, user-friendly platform for sharing sensitive documents.',
      logo: '/images/clients/unseal/logo-light.svg',
      date: '2023-06',
      href: '/case-studies/unseal'
    }
  ]
} 