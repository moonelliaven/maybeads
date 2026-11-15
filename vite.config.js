import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/landing/home.css',
                'resources/js/landing/home.js',
                'resources/css/landing/contact.css',
                'resources/css/auth/login.css',
                'resources/js/auth/login.js',
                'resources/css/auth/register.css',
                'resources/js/auth/register.js',
                'resources/css/admin/sidebar.css',
                'resources/css/admin/dashboard.css',
                'resources/js/admin/dashboard.js',
                'resources/css/admin/product-index.css',
                'resources/js/admin/product-index.js',
                'resources/css/admin/category-index.css',
                'resources/js/admin/category-index.js',
                'resources/css/admin/user-index.css',
                'resources/js/admin/user-index.js',
                'resources/css/admin/user-detail.css',
                'resources/js/admin/user-detail.js',
                'resources/css/admin/order-index.css',
                'resources/js/admin/order-index.js',
                'resources/css/admin/order-detail.css',
                'resources/js/admin/order-detail.js',
                'resources/css/admin/payment-index.css',
                'resources/js/admin/payment-index.js',
                'resources/css/admin/payment-method.css',
                'resources/js/admin/payment-method.js',
                'resources/css/admin/product-form.css',
                'resources/js/admin/product-form.js',
                'resources/css/admin/management.css',
                'resources/css/main/dashboard.css',
                'resources/js/main/dashboard.js',
                'resources/css/main/product-detail.css',
                'resources/js/main/product-detail.js',
                'resources/css/lenis.css',
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
