import {defineConfig, splitVendorChunkPlugin} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    server: {
        watch: {
            usePolling: true,
        },
    },
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js', 'resources/js/helper.js'],
            refresh: ['resources/**'],
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
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        },
    },
    build: {
        target: 'esnext',
        commonjsOptions: {
            transformMixedEsModules: true
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    bootstrap: ['bootstrap'],
                    lodash: ['lodash'],
                    ol: ['ol', 'proj4'],
                    plotly: ['plotly.js-basic-dist-min'],
                    vue: ['vue', 'vuex', 'vue-router']
                }
            },
        },
    },
});

