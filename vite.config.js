import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/css/assessment.css',
                'resources/css/career_personnality.css',
                'resources/css/listing_page.css',
                'resources/css/Home.css',
                'resources/css/ForOrganizations.css',
                'resources/css/vueMultiselect.css',
                'resources/css/index.css',
                'resources/css/header.css',
                'resources/css/global.css',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
