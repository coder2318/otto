<script lang="ts">
    import { toBlob } from 'html-to-image'
    import { draggable } from '@/service/svelte'
    import { svgTextWrap } from '@/service/helpers'
    import { onMount, afterUpdate } from 'svelte'

    export let template: App.BookCoverTemplate
    export let parameters: any = {}
    export let pages: number = 32

    let svg: HTMLElement | SVGElement
    let oldParams = {}

    $: sizes = getSize(pages)

    let dragHooks = []

    function updateSvg(parameters) {
        if (!svg) return

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
    }

    onMount(() => {
        svg.querySelectorAll(`[data-draggable]`).forEach((node: SVGTextElement) => {
            dragHooks.push(draggable(node, svg as SVGElement))
        })

        return () => {
            dragHooks.forEach((element) => element.destroy())
        }
    })

    afterUpdate(() => {
        const diff = Object.keys(parameters).reduce((diff, key) => {
            if (parameters[key] !== oldParams[key]) {
                diff[key] = parameters[key]
            }
            return diff
        }, {})

        if (Object.keys(diff).length) {
            updateSvg(diff)
        }

        oldParams = { ...parameters }
    })

    export async function getFile() {
        return await toBlob(svg as HTMLElement, {
            canvasHeight: sizes.totalHeight,
            canvasWidth: sizes.totalWidth,
        })
    }

    function getSpineWidth(pages) {
        switch (true) {
            case pages < 24:
                throw new Error('Book must have at least 24 pages.')
            case pages < 85:
                return 0.25
            case pages < 141:
                return 0.5
            case pages < 169:
                return 0.625
            case pages < 195:
                return 0.688
            case pages < 223:
                return 0.75
            case pages < 251:
                return 0.813
            case pages < 279:
                return 0.875
            case pages < 307:
                return 0.938
            case pages < 335:
                return 1
            case pages < 361:
                return 1.063
            case pages < 389:
                return 1.125
            case pages < 417:
                return 1.188
            case pages < 445:
                return 1.25
            case pages < 473:
                return 1.313
            case pages < 501:
                return 1.375
            case pages < 529:
                return 1.438
            case pages < 557:
                return 1.5
            case pages < 583:
                return 1.563
            case pages < 611:
                return 1.625
            case pages < 639:
                return 1.688
            case pages < 667:
                return 1.75
            case pages < 695:
                return 1.813
            case pages < 723:
                return 1.875
            case pages < 751:
                return 1.938
            case pages < 779:
                return 2
            default:
                return 2.12
        }
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
