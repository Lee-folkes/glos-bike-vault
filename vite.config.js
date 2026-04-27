import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                 'resources/js/app.js',
                 //Page specific css
                 'resources/css/pages/login_register.css',
                 'resources/css/pages/dashboard.css',
                 'resources/css/pages/admin-dashboard.css',
                 //Page specific js
                 'resources/js/pages/dashboard.js',
                 'resources/js/pages/admin-dashboard.js',
                ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
