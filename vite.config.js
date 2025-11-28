import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/student-layout.css',
                'resources/css/student-auth.css',
                'resources/css/certificate-download.css',
                'resources/js/student-layout.js',
                'resources/js/exam-timer.js',
                'resources/js/profile.js',
            ],
            refresh: true,
        }),
    ],
});
