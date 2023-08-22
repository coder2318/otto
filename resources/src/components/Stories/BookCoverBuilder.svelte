<script lang="ts">
    import { onMount } from 'svelte'

    export let template: App.BookCoverTemplate
    export let parameters: any

    let element: HTMLElement
    let svg: HTMLElement
    let defaultParameters: any = {}

    export function getFile(width: number = 1800, ratio: number = 1) {
        return new Promise<Blob>((resolve) => {
            const svgData =
                `<?xml version="1.0" standalone="no"?>\r\n` +
                new XMLSerializer().serializeToString(svg)
            const blob = new Blob([svgData], { type: 'image/svg+xml' })
            const image = new Image()

            image.width = width
            image.height = width * ratio
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

    $: {
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

        svg.classList.add('w-full', 'h-full', 'select-none')

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
