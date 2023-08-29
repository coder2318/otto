<script lang="ts">
    import cover from '@/assets/img/default-cover.jpg'

    export let template: App.BookCoverTemplate
    export let parameters: any = {}
    export let pages: number = 32

    let svg: SVGElement
    $: sizes = getSize(pages)
    $: updateSvg(parameters)

    function updateSvg(parameters) {
        if (!svg) return

        Object.entries(parameters).forEach(([key, value]) => {
            svg.querySelectorAll(
                `[data-${key.replaceAll(/([A-Z])/g, '-$1').toLowerCase()}]`
            ).forEach((node: HTMLElement) => {
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
            })
        })
    }

    export function getFile() {
        return new Promise<Blob>((resolve) => {
            const svgData =
                `<?xml version="1.0" standalone="no"?>\r\n` +
                new XMLSerializer().serializeToString(svg)
            const blob = new Blob([svgData], { type: 'image/svg+xml' })
            const image = new Image()

            image.width = sizes.totalWidth
            image.height = sizes.totalHeight
            image.src = URL.createObjectURL(blob)

            image.onload = () => {
                const canvas = document.createElement('canvas')
                canvas.width = image.width
                canvas.height = image.height
                const ctx = canvas.getContext('2d')
                ctx.drawImage(image, 0, 0)
                URL.revokeObjectURL(image.src)

                canvas.toBlob(
                    (blob) => {
                        resolve(blob)
                    },
                    'image/jpg',
                    80
                )
            }
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
            (2 * sizes.width + 4 * sizes.safetyMargin + sizes.spineWidth) /
            (sizes.height + 2 * sizes.safetyMargin)
        )
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
        x={sizes.bleedArea +
            2 * sizes.safetyMargin +
            sizes.spineWidth +
            sizes.width}
        y={sizes.bleedArea}
        width={sizes.width + 2 * sizes.safetyMargin}
        height={sizes.height + 2 * sizes.safetyMargin}
    >
        {@html template.front ?? ''}
    </svg>
</svg>
