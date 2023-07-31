import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte'
import markdown from 'vite-plugin-svelte-md'
import sveltePreprocess from 'svelte-preprocess';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/src/app.ts',
            ssr: 'resources/src/ssr.ts',
            refresh: true,
        }),
        markdown(),
        svelte({
            extensions: [".svelte", ".md"],
            preprocess: [sveltePreprocess({
                typescript: true,
            })],
            compilerOptions: {
                hydratable: true,
            },
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        }
    },
    ssr: {
        noExternal: true,
        external: ['@inertiajs/core'],
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/src'),
        }
    }
});
