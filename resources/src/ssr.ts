import createServer from '@inertiajs/svelte/server'
import { createInertiaApp } from '@inertiajs/svelte'
import { resolve } from './service/entry'

createServer(
    page => createInertiaApp({ page, resolve }),
    import.meta.env.VITE_SSR_PORT ?? 13714
)
