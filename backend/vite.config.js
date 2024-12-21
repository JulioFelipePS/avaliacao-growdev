import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        proxy: {
            '/api': {
                target: 'http://localhost:8081',  // Alvo do backend
                changeOrigin: true,  // Modifica o cabeçalho "Origin"
                rewrite: (path) => path.replace(/^\/api/, ''),  // Remove o "/api" da URL
            },
        },
    },
});
