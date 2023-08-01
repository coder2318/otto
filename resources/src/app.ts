import '@/app.scss'
import { createInertiaApp } from '@inertiajs/svelte'
import { progress, resolve, setup } from './service/entry'

createInertiaApp({ resolve, setup, progress })
