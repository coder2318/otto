import '@/app.scss'
import '@/polyfill'
import '@/types/app.d'
import { createInertiaApp } from '@inertiajs/svelte'
import { progress, resolve, setup } from './service/entry'

createInertiaApp({ resolve, setup, progress })
