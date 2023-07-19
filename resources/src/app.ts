import { createInertiaApp } from '@inertiajs/svelte'
import { resolve, setup, progress } from './entry'
import '@/app.scss'

createInertiaApp({ resolve, setup, progress })
