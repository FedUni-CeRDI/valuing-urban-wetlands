import {defineConfig, splitVendorChunkPlugin} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
export default defineConfig({
    server: {
        watch: {
            usePolling: true
        }
    },
    plugins: [
        splitVendorChunkPlugin(),
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        })
    ],
    resolve: {
        alias: {
            '~bootstrap':path.resolve(__dirname,'node_modules/bootstrap')
        }
    },
    build: {
            target: 'esnext'
    }
});

