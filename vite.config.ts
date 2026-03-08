import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import vueDevTools from 'vite-plugin-vue-devtools';
import {nativephpMobile,nativephpHotFile} from './vendor/nativephp/mobile/resources/js/vite-plugin.js';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
            hotFile: nativephpHotFile(),
        }),
        vueDevTools({
            appendTo: 'resources/js/app.ts',
        }),
        tailwindcss(),
        nativephpMobile(),
        wayfinder({
            formVariants: true,
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
});
