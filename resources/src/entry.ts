import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

export const resolve = (name: string) => resolvePageComponent(
    `./pages/${name}.svelte`,
    import.meta.glob(`./pages/**/*.svelte`)
)

export const setup = ({ el, App, props }) => {
    const app = new App({ target: el, props, hydrate: true })
    delete el.dataset.page

    return app
}

export const progress = {}
