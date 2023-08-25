<script lang="ts">
    import { onMount } from 'svelte'

    export let template: App.BookCoverTemplate
    export let pages: number = 200

    let svg: SVGElement
    $: params = getSize(pages)

    function getSize(pages) {
        let width = 5.5,
            height = 8.5,
            safetyMargin = 0.25,
            bleedArea = 0.125,
            spineWidth = pages / 444 + 0.06 // https://blog.lulu.com/book-spine/

        const sizes = {
            totalHeight: height + 2 * safetyMargin + 2 * bleedArea,
            totalWidth:
                2 * width + 4 * safetyMargin + spineWidth + 2 * bleedArea,
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
        return (
            (2 * params.width + 4 * params.safetyMargin + params.spineWidth) /
            (params.height + 2 * params.safetyMargin)
        )
    }
</script>

<svg
    bind:this={svg}
    viewBox="0 0 {params.totalWidth} {params.totalHeight}"
    xmlns="http://www.w3.org/2000/svg"
    style="--spine-width:{params.spineWidth}"
    {...$$restProps}
>
    <image
        href="https://picsum.photos/1920/1080"
        preserveAspectRatio="xMinYMin slice"
        x={params.bleedArea}
        y={params.bleedArea}
        width={2 * params.width + 4 * params.safetyMargin + params.spineWidth}
        height={params.height + 2 * params.safetyMargin}
    />

    <svg
        x={params.bleedArea}
        y={params.bleedArea}
        width={params.width + 2 * params.safetyMargin}
        height={params.height + 2 * params.safetyMargin}
    >
        <rect x="0" y="0" width="100%" height="100%" fill="transparent" />
    </svg>
    <svg
        x={params.bleedArea + 2 * params.safetyMargin + params.width}
        y={params.bleedArea}
        width={params.spineWidth}
        height={params.height + 2 * params.safetyMargin}
    >
        <rect x="0" y="0" width="100%" height="100%" fill="white" />
        <text
            x="50%"
            y="50%"
            width="100%"
            text-anchor="middle"
            dominant-baseline="middle"
            height="100%"
            style="transform-origin:center;transform:rotate(-90deg);"
            font-size="calc(var(--spine-width) * 0.666)"
        >
            Spine
        </text>
    </svg>

    <svg
        x={params.bleedArea +
            2 * params.safetyMargin +
            params.spineWidth +
            params.width}
        y={params.bleedArea}
        width={params.width + 2 * params.safetyMargin}
        height={params.height + 2 * params.safetyMargin}
    >
        <rect x="0" y="0" width="100%" height="100%" fill="transparent" />
    </svg>
</svg>
