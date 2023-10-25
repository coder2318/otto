<script lang="ts">
    import { toBlob } from 'html-to-image'
    import { draggable } from '@/service/svelte'
    import { svgTextWrap } from '@/service/helpers'
    import { onMount } from 'svelte'

    export let template: App.BookCoverTemplate
    export let parameters: any = {}
    export let pages: number = 32

    let svg: HTMLElement | SVGElement

    $: cover = template.cover ?? import.meta.env.VITE_APP_URL + '/build/assets/cover-background.png'
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

    export function getSize(pages) {
        let width = 5.5,
            height = 8.5,
            safetyMargin = 0.25,
            bleedArea = 0.125,
            spineWidth = (pages > 32 ? pages : 32) / 444 + 0.06 // https://blog.lulu.com/book-spine/

        const sizes = {
            totalHeight: height + 2 * safetyMargin,
            totalWidth: width + 2 * safetyMargin,
            width,
            height,
            spineWidth,
            bleedArea,
            safetyMargin,
        }

        Object.keys(sizes).forEach((key) => (sizes[key] = sizes[key] * 96))

        return sizes
    }

    export function getCoverAspectRatio() {
        return (sizes.width + 2 * sizes.safetyMargin) / (sizes.height + 2 * sizes.safetyMargin)
    }
</script>

<svg
    bind:this={svg}
    viewBox="0 0 {sizes.totalWidth} {sizes.totalHeight}"
    xmlns="http://www.w3.org/2000/svg"
    style="--spine-width:{sizes.spineWidth}"
    {...$$restProps}
>
    <image
        href={parameters.background ?? cover}
        preserveAspectRatio="xMinYMin slice"
        x={0}
        y={0}
        width={sizes.width + 2 * sizes.safetyMargin}
        height={sizes.height + 2 * sizes.safetyMargin}
    />

    <!-- <svg
        x={sizes.bleedArea}
        y={sizes.bleedArea}
        width={sizes.width + 2 * sizes.safetyMargin}
        height={sizes.height + 2 * sizes.safetyMargin}
    >
        {@html template.back ?? ''}
    </svg>
    <svg
        x={sizes.bleedArea}
        y={sizes.bleedArea}
        width={sizes.spineWidth}
        height={sizes.height + 2 * sizes.safetyMargin}
    >
        {@html template.spine ?? ''}
    </svg> -->

    <svg x={0} y={0} width={sizes.width + 2 * sizes.safetyMargin} height={sizes.height + 2 * sizes.safetyMargin}>
        {@html template.front ?? ''}
    </svg>
</svg>

<style lang="scss">
    :global([data-draggable]) {
        @apply cursor-move;
    }
</style>
