import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import vueDevTools from 'vite-plugin-vue-devtools';

import {nativephpMobile,nativephpHotFile} from './vendor/nativephp/mobile/resources/js/vite-plugin.js';

export default defineConfig({
    server: {
        watch: {
            // The app writes to the SQLite DB (sessions/cache/queue all use the
            // database driver) on nearly every request. Without this, every write
            // is picked up as a project file change and triggers a full-reload
            // loop in the browser.
            ignored: ['**/database.sqlite*', '**/storage/**'],
        },
    },
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
