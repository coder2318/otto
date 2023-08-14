<script lang="ts">
    import { onMount } from 'svelte'
    import bookCover from '@/assets/img/book-cover.svg?raw'

    export let template: string = bookCover
    export let parameters: App.BookCoverTemplateParameters

    let element: HTMLElement
    let svg: HTMLElement

    $: {
        Object.entries(parameters).forEach(([key, value]) => {
            if (!value || !svg) {
                return
            }
            svg.querySelectorAll(`[data-${key}]`).forEach(
                (node: HTMLElement) => {
                    switch (node.dataset[key]) {
                        case 'innerText':
                            node.innerHTML = value
                            break
                        case 'innerHTML':
                            node.innerHTML = value
                            break
                        default:
                            node.setAttribute(node.dataset[key], value)
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
    })
</script>

<div bind:this={element} {...$$restProps} />
