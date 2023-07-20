import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/centros/centros.js',
                'resources/js/miembrosGobierno/miembrosGobierno.js',
                'resources/js/juntas/juntas.js',
                'resources/js/home.js',
                'resources/js/filtros.js',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
