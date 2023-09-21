import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import { mdsvex } from 'mdsvex';
import { viteStaticCopy as copy } from 'vite-plugin-static-copy';
import { VitePWA as pwa } from 'vite-plugin-pwa'
import eslint from 'vite-plugin-eslint';
import sveltePreprocess from 'svelte-preprocess';
import path from 'path';

export default ({ mode }) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd(), ['APP'])};

    return defineConfig({
        plugins: [
            laravel({
                input: ['resources/src/app.ts', 'resources/src/app.scss'],
                ssr: 'resources/src/ssr.ts',
                refresh: true,
            }),
            svelte({
                extensions: [".svelte", ".svx", ".md"],
                hot: mode !== 'production',
                preprocess: [
                    sveltePreprocess({
                        typescript: true,
                        scss: true,
                        postcss: true,
                    }),
                    mdsvex({
                        extensions: [".md", ".svx"],
                    })
                ],
                compilerOptions: {
                    hydratable: true,
                },
            }),
            pwa({
                mode: process.env.APP_ENV == 'production' ? 'production' : 'development',
                strategies: 'injectManifest',
                registerType: 'autoUpdate',
                srcDir: 'resources/src',
                filename: 'sw.ts',
                injectRegister: 'auto',
                manifest: {
                    short_name: process.env.APP_NAME,
                    name: process.env.APP_NAME,
                    start_url: process.env.APP_URL,
                    scope: process.env.APP_URL,
                    description: "Your Autobiography AI",
                    categories: ["books", "lifestyle", "entertainment", "education", "shopping"],
                    theme_color: "#0C345C",
                    background_color: "#F1EDE7",
                    display: "standalone",
                    orientation: "any",
                    id: 'dashboard',
                    icons: [
                        {
                            src: "assets/logo.svg",
                            type: "image/svg+xml",
                            purpose: "any",
                            sizes: "any"
                        },
                        {
                            src: "assets/logo.png",
                            type: "image/png",
                            purpose: "any",
                            sizes: "any"
                        }
                    ]
                },
                workbox: {
                    sourcemap: true
                }
            }),
            copy({
                targets: [
                    {
                        src: path.resolve(__dirname, 'resources/assets'),
                        dest: path.resolve(__dirname, 'public/build'),
                    },
                ],
            }),
            eslint({
                include: path.resolve(__dirname, 'resources/src/**/*.{ts,js,svelte}'),
                fix: true,
            })
        ],
        server: {
            hmr: {
                host: 'localhost',
            }
        },
        build: {
            chunkSizeWarningLimit: 1024,
            rollupOptions: {
                output: {
                    manualChunks(id) {
                        if (id.includes('node_modules')) {
                            return id.toString().split('node_modules/')[1].split('/')[0].toString()
                        }
                    }
                }
            }
        },
        ssr: {
            noExternal: true,
            external: ['@inertiajs/core', 'util'],
        },
        resolve: {
            alias: [
                { find: "@", replacement: path.resolve(__dirname, 'resources/src') },
                { find: /~(.+)/, replacement: path.join(process.cwd(), 'node_modules/$1') },
            ]
        },
    });
}
