import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { registerSW } from 'virtual:pwa-register'

export const resolve = (name: string) =>
    resolvePageComponent(
        `/resources/src/pages/${name}.svelte`,
        import.meta.glob(`@/pages/**/*.svelte`)
    )

export const createServiceWorker = () => {
    if (!('serviceWorker' in navigator)) {
        return
    }

    const updateSW = registerSW({
        immediate: true,
        onRegistered(reg) {
            reg.update()
        },
        onNeedRefresh() {
            updateSW()
        },
    })
}

export const setup = ({ App, el, props }) => {
    const app = new App({ target: el, props, hydrate: true })
    delete el.dataset.page
    createServiceWorker()

    return app
}

// See: https://inertiajs.com/progress-indicators
export const progress = {
    color: 'hsl(var(--a))', // accent
}
