import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/centros/centros.js',
                'resources/js/miembrosGobierno/miembrosGobierno.js',
                'resources/js/miembrosJunta/miembrosJunta.js',
                'resources/js/miembrosComision/miembrosComision.js',
                'resources/js/juntas/juntas.js',
                'resources/js/comisiones/comisiones.js',
                'resources/js/panel.js',
                'resources/js/app.js',
                'resources/js/publico/info.js',
                'resources/js/convocatoriasJunta/convocatoriasJunta.js',
                'resources/js/convocatoriasComision/convocatoriasComision.js',
                'resources/js/convocatoriasJunta/confirmarAsistencia.js',
            ],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        exclude: ['js-big-decimal']
      }
});
