<script lang="ts">
    import { toBlob } from 'html-to-image'

    export let template: App.BookCoverTemplate
    export let parameters: any = {}
    export let pages: number = 32

    let svg: HTMLElement | SVGElement

    $: cover = template.cover ?? import.meta.env.VITE_APP_URL + '/build/assets/cover-background.png'
    $: sizes = getSize(pages)
    $: updateSvg(parameters)

    function updateSvg(parameters) {
        if (!svg) return

        Object.entries(parameters).forEach(([key, value]) => {
            svg.querySelectorAll(`[data-${key.replaceAll(/([A-Z])/g, '-$1').toLowerCase()}]`).forEach(
                (node: HTMLElement) => {
                    switch (node.dataset[key]) {
                        case 'innerText':
                            node.innerHTML = value as string
                            break
                        case 'innerHTML':
                            node.innerHTML = value as string
                            break
                        default:
                            node.setAttribute(node.dataset[key], value as string)
                            break
                    }
                }
            )
        })
    }

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
            totalHeight: height + 2 * safetyMargin + 2 * bleedArea,
            totalWidth: 2 * width + 4 * safetyMargin + spineWidth + 2 * bleedArea,
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
        return (2 * sizes.width + 4 * sizes.safetyMargin + sizes.spineWidth) / (sizes.height + 2 * sizes.safetyMargin)
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
        x={sizes.bleedArea}
        y={sizes.bleedArea}
        width={2 * sizes.width + 4 * sizes.safetyMargin + sizes.spineWidth}
        height={sizes.height + 2 * sizes.safetyMargin}
    />

    <svg
        x={sizes.bleedArea}
        y={sizes.bleedArea}
        width={sizes.width + 2 * sizes.safetyMargin}
        height={sizes.height + 2 * sizes.safetyMargin}
    >
        {@html template.back ?? ''}
    </svg>
    <svg
        x={sizes.bleedArea + 2 * sizes.safetyMargin + sizes.width}
        y={sizes.bleedArea}
        width={sizes.spineWidth}
        height={sizes.height + 2 * sizes.safetyMargin}
    >
        {@html template.spine ?? ''}
    </svg>

    <svg
        x={sizes.bleedArea + 2 * sizes.safetyMargin + sizes.spineWidth + sizes.width}
        y={sizes.bleedArea}
        width={sizes.width + 2 * sizes.safetyMargin}
        height={sizes.height + 2 * sizes.safetyMargin}
    >
        {@html template.front ?? ''}
    </svg>
</svg>
