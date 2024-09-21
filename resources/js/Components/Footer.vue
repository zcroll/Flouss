<template>
        <footer class="bg-black">
            <div class="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
                <nav class="-mb-6 columns-2 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">
                    <div v-for="item in navigation" :key="item.name" class="pb-6">
                        <a :href="item.href" class="text-sm leading-6 text-gray-200 hover:text-gray-200">{{ item.name }}</a>
                    </div>
                </nav>
                <div class="mt-10 flex justify-center space-x-10">
                    <a v-for="item in socialLinks" :key="item.name" :href="item.href" class="text-gray-200 hover:text-gray-200">
                        <span class="sr-only">{{ item.name }}</span>
                        <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />
                    </a>
                </div>
                <p class="mt-10 text-center text-xs leading-5 text-gray-200">&copy; 2024 Your Company, Inc. All rights reserved.</p>
            </div>
        </footer>
</template>

<script>
const navigation = [
    { name: 'Dashboard', href: route('dashboard'), current: route().current('dashboard') },
    { name: 'Results', href: route('results'), current: route().current('results') },
    // Add more navigation items as needed
];

const socialLinks = [
    // ... (keep your existing social links)
];
</script>