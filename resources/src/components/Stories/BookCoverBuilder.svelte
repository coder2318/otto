<script lang="ts">
    import { onMount } from 'svelte'
    import bookCover from '@/assets/img/book-cover.svg?raw'

    export let template: string = bookCover
    export let parameters: App.BookCoverTemplateParameters

    let element: HTMLElement
    let svg: HTMLElement
    let defaultParameters: any = {}

    $: {
        Object.entries(parameters).forEach(([key, value]) => {
            if (!svg) return
            svg.querySelectorAll(`[data-${key}]`).forEach(
                (node: HTMLElement) => {
                    switch (node.dataset[key]) {
                        case 'innerText':
                            node.innerHTML =
                                value ?? defaultParameters[key] ?? ''
                            break
                        case 'innerHTML':
                            node.innerHTML =
                                value ?? defaultParameters[key] ?? ''
                            break
                        default:
                            node.setAttribute(
                                node.dataset[key],
                                value ?? defaultParameters[key] ?? ''
                            )
                            break
                    }
                }
            )
        })
    }

    onMount(() => {
        svg = new DOMParser().parseFromString(
            template,
            'image/svg+xml'
        ).documentElement

        element.appendChild(svg)

        svg.querySelectorAll('[data-editable]').forEach((node: HTMLElement) => {
            Object.entries(node.dataset).forEach(([key, value]) => {
                if (key === 'editable') {
                    return
                }

                switch (node.dataset[key]) {
                    case 'innerText':
                        defaultParameters[key] = node.innerText
                        break
                    case 'innerHTML':
                        defaultParameters[key] = node.innerHTML
                        break
                    default:
                        defaultParameters[key] = node.getAttribute(
                            node.dataset[key]
                        )
                }
            })
        })
    })
</script>

<div bind:this={element} {...$$restProps} />
