import createServer from '@inertiajs/svelte/server'
import { createInertiaApp } from '@inertiajs/svelte'
import { resolve, setup } from './entry'

createServer(
    page => createInertiaApp({ page, resolve, setup }),
    import.meta.env.VITE_SSR_PORT ?? 13714
)
