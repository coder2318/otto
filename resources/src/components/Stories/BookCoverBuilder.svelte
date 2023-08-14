<script lang="ts">
    import { onMount } from 'svelte'
    import bookCover from '@/assets/img/book-cover.svg?raw'

    export let template: App.BookCoverTemplate
    export let parameters: any

    let element: HTMLElement
    let svg: HTMLElement
    let defaultParameters: any = {}

    $: {
        console.log(parameters)
        Object.entries(parameters).forEach(([key, value]) => {
            if (!svg) return
            svg.querySelectorAll(
                `[data-${key.replaceAll(/([A-Z])/g, '-$1').toLowerCase()}]`
            ).forEach((node: HTMLElement) => {
                switch (node.dataset[key]) {
                    case 'innerText':
                        node.innerHTML = value ?? defaultParameters[key] ?? ''
                        break
                    case 'innerHTML':
                        node.innerHTML = value ?? defaultParameters[key] ?? ''
                        break
                    default:
                        node.setAttribute(
                            node.dataset[key],
                            value ?? defaultParameters[key] ?? ''
                        )
                        break
                }
            })
        })
    }

    onMount(() => {
        svg = new DOMParser().parseFromString(
            template.template,
            'image/svg+xml'
        ).documentElement

        element.appendChild(svg)

        svg.querySelectorAll('[data-default]').forEach((node: HTMLElement) => {
            Object.entries(node.dataset).forEach(([key, value]) => {
                if (key === 'editable') {
                    return
                }

                defaultParameters[key] = node.getAttribute(value)
            })
        })
    })
</script>

<div bind:this={element} {...$$restProps} />
