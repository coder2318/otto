<script lang="ts">
    import { toBlob } from 'html-to-image'
    import { draggable } from '@/service/svelte'
    import { loadFonts, svgTextInside, svgTextWrap } from '@/service/helpers'
    import { onMount, afterUpdate } from 'svelte'
    import isEqual from 'lodash/isEqual'
    import { BOOK_COVER_EXCLUDED_FIELDS } from '@/app.constants'

    export let template: App.CoverTemplate
    export let fonts: App.Font[] = []
    export let parameters: any = {}
    export let shared: any = {}
    export let pages: number = 32
    export let preview: boolean = false
    export let reload: boolean = false
    export let change = () => {}

    let svg: HTMLElement | SVGElement
    let oldParams = { ...parameters }

    $: sizes = getSize(pages)

    let isPositionSetted = false
    const dragHooks = []

    const keys = {
        titleColor: 'title',
        titleSize: 'title',
        titleFont: 'title',
        descriptionColor: 'description',
        descriptionSize: 'description',
        descriptionFont: 'description',
        authorColor: 'author',
        authorSize: 'author',
        authorFont: 'author',
        subtitleColor: 'subtitle',
        subtitleSize: 'subtitle',
        subtitleFont: 'subtitle',
    }

    function setTextValue(node: SVGTextElement, value: string | null = null) {
        if (node.dataset.max && value) {
            value = value.toString().slice(0, parseInt(node.dataset.max))
        }

        svgTextWrap(node, value, parseFloat(node.dataset['maxWidth'] || sizes.width + ''))
    }

    function setHref(node: SVGTextElement, value: string | null = null) {
        if (value !== null) node.setAttribute('href', value)
    }

    function updateSvg(params) {
        Object.entries(params).forEach(([key, value], i) => {
            key = key.includes('Position') ? key.replace('Position', '') : key

            svg.querySelectorAll(`[data-${key.replaceAll(/([A-Z])/g, '-$1').toLowerCase()}]`).forEach(
                (node: HTMLElement | SVGElement) => {
                    if (keys[key]) {
                        node.classList.add(keys[key])
                    }

                    if (!isPositionSetted || reload) {
                        const className = node.className.baseVal
                        const values = params[`${className}Position`]

                        if (className && values) {
                            node.setAttribute('x', values.x)
                            node.setAttribute('y', values.y)
                        }
                    }

                    if (i === Object.entries(params).length - 1) {
                        isPositionSetted = true
                        reload = false
                    }

                    switch (node.dataset[key]) {
                        case 'font-size':
                            node.style.fontSize = value + 'px'

                            if (node.tagName === 'foreignObject') {
                                svgTextInside(node as SVGTextElement)
                            } else {
                                setTextValue(node as SVGTextElement)
                            }

                            if (+node.getAttribute('y') < 0) {
                                node.setAttribute('y', '0')
                            }

                            break
                        case 'innerText':
                            setTextValue(node as SVGTextElement, value as string)
                            break
                        case 'innerHTML':
                            svgTextInside(node as SVGTextElement, value as string)
                            break
                        case 'href':
                            setHref(node as SVGTextElement, value as string)
                            break
                        case 'text-background':
                            if (typeof value === 'string') {
                                node.style.background = value
                            }
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
        const file = await toBlob(svg as HTMLElement, {
            canvasHeight: sizes.totalHeight,
            canvasWidth: sizes.totalWidth,
            type: 'image/jpeg',
            cacheBust: true,
        })

        return { file, svg }
    }

    onMount(() => {
        loadFonts(fonts)

        if (!preview) {
            svg.querySelectorAll(`[data-draggable]`).forEach((node: SVGTextElement) => {
                dragHooks.push(draggable(node, svg as SVGElement, change))
            })
        }

        const textFields = template.fields.filter((field) => field.type === 'text').map((field) => field.key)

        parameters = template.fields
            .filter(({ key }) => !BOOK_COVER_EXCLUDED_FIELDS.includes(key))
            .reduce((acc, field) => {
                const key = field.key

                if (textFields.includes(key)) {
                    acc[key] = shared[key]

                    return acc
                }

                acc[key] = field?.defaultValue

                return acc
            }, {})

        Object.entries(shared).forEach(([key, value]) => {
            const keyPos = key.includes('Position') ? key.replace('Position', '') : key

            const element = svg.querySelector(`[data-${keyPos.replaceAll(/([A-Z])/g, '-$1').toLowerCase()}]`)

            const tagName = element?.tagName.toLowerCase()

            if (tagName === 'text' || tagName === 'p' || element?.hasAttribute('data-shared')) {
                parameters[key] = value
            }
        })

        updateSvg(parameters)

        return () => {
            dragHooks.forEach((element) => element.destroy())
        }
    })

    afterUpdate(() => {
        const diff = Object.keys(parameters).reduce((diff, key) => {
            if (!isEqual(oldParams[key], parameters[key])) {
                diff[key] = parameters[key]
            }

            return diff
        }, {})

        if (Object.keys(diff).length) {
            change()
        }

        updateSvg(parameters)

        oldParams = { ...parameters }
    })

    export async function getSVG() {
        return svg
    }

    function getSpineWidth(pages) {
        switch (true) {
            case pages < 24:
                return 0.25
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
    class="rounded-md"
    bind:this={svg}
    viewBox="0 0 {preview ? sizes.width : sizes.totalWidth} {preview ? sizes.height : sizes.totalHeight}"
    xmlns="http://www.w3.org/2000/svg"
    style="--spine-width:{sizes.spineWidth}"
    width="100%"
    height="100%"
    {...$$restProps}
>
    {#if !preview}
        <svg x={0} y={0} width={sizes.totalWidth} height={sizes.totalHeight + 1} class="base">
            {@html template.base ?? ''}
        </svg>
        <svg x={0} y={0} width={sizes.width} height={sizes.height + 1} class="back">
            {@html template.back ?? ''}
        </svg>
        <svg x={sizes.width} y={0} width={sizes.spineWidth + 1} height={sizes.height + 1}>
            {@html template.spine ?? ''}
        </svg>
    {/if}
    <svg
        x={preview ? 0 : sizes.width + sizes.spineWidth}
        y={0}
        width={sizes.width}
        height={sizes.height + 1}
        class="front"
    >
        {@html template.front ?? ''}
    </svg>
</svg>
{#if !preview}
    <style lang="scss">
        :global([data-draggable]) {
            @apply cursor-move;
        }
    </style>
{/if}
