<script lang="ts">
    import { toBlob } from 'html-to-image'
    import { draggable } from '@/service/svelte'
    import { svgTextWrap } from '@/service/helpers'
    import { onMount } from 'svelte'

    export let template: App.BookCoverTemplate
    export let parameters: any = {}
    export let pages: number = 32

    let svg: HTMLElement | SVGElement

    $: sizes = getSize(pages)
    $: updateSvg(parameters)

    let dragHooks = []

    function updateSvg(parameters) {
        if (!svg) return

        dragHooks.forEach((element) => element.destroy())
        dragHooks = []

        Object.entries(parameters).forEach(([key, value]) => {
            svg.querySelectorAll(`[data-${key.replaceAll(/([A-Z])/g, '-$1').toLowerCase()}]`).forEach(
                (node: HTMLElement | SVGElement) => {
                    switch (node.dataset[key]) {
                        case 'innerText':
                        case 'innerHTML':
                            svgTextWrap(node as SVGTextElement, value as string, sizes.width)
                            break
                        default:
                            node.setAttribute(node.dataset[key], value as string)
                            break
                    }
                }
            )
        })

        svg.querySelectorAll(`[data-draggable]`).forEach((node: SVGTextElement) => {
            dragHooks.push(draggable(node, svg as SVGElement))
        })
    }

    onMount(() => {
        svg.querySelectorAll(`[data-draggable]`).forEach((node: SVGTextElement) => {
            dragHooks.push(draggable(node, svg as SVGElement))
        })

        return () => {
            dragHooks.forEach((element) => element.destroy())
        }
    })

    export async function getFile() {
        return await toBlob(svg as HTMLElement, {
            canvasHeight: sizes.totalHeight,
            canvasWidth: sizes.totalWidth,
        })
    }

    function getSpineWidth(pages) {
        if (pages < 24) {
            return 0.25
        }

        const stops = {
            24: 0.25,
            85: 0.5,
            141: 0.625,
            169: 0.688,
            195: 0.75,
            223: 0.813,
            251: 0.875,
            279: 0.938,
            307: 1,
            335: 1.063,
            361: 1.125,
            389: 1.188,
            417: 1.25,
            445: 1.313,
            473: 1.375,
            501: 1.438,
            529: 1.5,
            557: 1.563,
            583: 1.625,
            611: 1.688,
            639: 1.75,
            667: 1.813,
            695: 1.875,
            723: 1.938,
            751: 2,
            779: 2.063,
            800: 2.12,
        }

        for (const [key, value] of Object.entries(stops)) {
            if (pages > key) {
                return value
            }
        }
        return 2.12
    }

    export function getSize(pages) {
        let width = 6.265,
            height = 9.46,
            spineWidth = getSpineWidth(pages) // https://blog.lulu.com/book-spine/

        const sizes = {
            totalHeight: height,
            totalWidth: 2 * width + spineWidth,
            width,
            height,
            spineWidth,
        }

        Object.keys(sizes).forEach((key) => (sizes[key] = sizes[key] * 96))

        return sizes
    }

    export function getCoverAspectRatio() {
        return sizes.width / sizes.height
    }
</script>

<svg
    bind:this={svg}
    viewBox="0 0 {sizes.totalWidth} {sizes.totalHeight}"
    xmlns="http://www.w3.org/2000/svg"
    style="--spine-width:{sizes.spineWidth}"
    {...$$restProps}
>
    <svg x={0} y={0} width={sizes.width} height={sizes.height}>
        {@html template.back ?? ''}
    </svg>
    <svg x={sizes.width} y={0} width={sizes.spineWidth} height={sizes.height}>
        {@html template.spine ?? ''}
    </svg>
    <svg x={sizes.width + sizes.spineWidth} y={0} width={sizes.width} height={sizes.height}>
        {@html template.front ?? ''}
    </svg>
</svg>

<style lang="scss">
    :global([data-draggable]) {
        @apply cursor-move;
    }
</style>
