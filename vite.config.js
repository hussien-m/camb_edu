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
                'resources/css/frontend-home.css',
                'resources/css/frontend-layout.css',
                'resources/css/frontend-courses.css',
                'resources/css/frontend-course-detail.css',
                'resources/js/student-layout.js',
                'resources/js/exam-timer.js',
                'resources/js/profile.js',
                'resources/js/frontend-home.js',
                'resources/js/frontend-courses.js',
                'resources/js/frontend-course-detail.js',
                'resources/js/csrf-setup.js',
                'resources/js/frontend-newsletter.js',
            ],
            refresh: true,
        }),
    ],
});
