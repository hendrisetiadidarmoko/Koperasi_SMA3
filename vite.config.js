import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1', // Ganti "::1" dengan IPv4
        port: 5173,         // Pastikan port ini tidak digunakan oleh aplikasi lain
        hmr: {
            host: '127.0.0.1', // Atur agar HMR juga menggunakan IPv4
        },
    },
});
