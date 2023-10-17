<script lang="ts">
    import { toBlob } from 'html-to-image'

    export let template: App.BookCoverTemplate
    export let parameters: any = {
        author: 'Author',
        title: 'Title',
        textColor: 'black',
    }
    export let pages: number = 32

    let svg: HTMLElement | SVGElement

    $: cover = template.cover ?? import.meta.env.VITE_APP_URL + '/build/assets/cover-background.png'
    $: sizes = getSize(pages)

    $: authorElements = updateTextElements(parameters.author, 'Author')
    $: titleElements = updateTextElements(parameters.title, 'Title')

    // todo - should be removed if the PR is accepted
    //$: updateSvg(parameters)

    // todo - should be removed if the PR is accepted
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

    const fontSize = (stringVar: HTMLElement, startSize: Number) => {
        if (stringVar?.length > 12) {
            startSize = 2
        }
        if (stringVar?.length > 25) {
            startSize = 1.5
        }
        if (stringVar?.length > 35) {
            startSize = 1.25
        }

        return `${startSize}rem`
    }

    function updateTextElements(text: Array<String>, defaultParam: String) {
        const words = text?.split(' ') || [defaultParam]
        let currentLine = ''
        const newLines = []

        for (let word of words) {
            if (currentLine.length + word.length + 1 <= 40) {
                if (currentLine.length > 0) {
                    currentLine += ' '
                }
                currentLine += word
            } else {
                newLines.push(currentLine)
                currentLine = word
            }
        }

        if (currentLine.length > 0) {
            newLines.push(currentLine)
        } else {
            newLines.push(defaultParam)
        }

        return newLines
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

    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins&display=swap);
    </style>
    {#each authorElements as text, i}
        <text
            x="50%"
            y={75 + i * 25}
            font-size={fontSize(parameters?.author, 3)}
            text-anchor="middle"
            font-family="Poppins"
            text-shadow="#FC0 1px 0 10px"
            data-author="innerText"
            data-text-color="fill"
            letter-spacing="0.1em"
            fill={parameters?.textColor || 'black'}
            style="text-transform:uppercase"
        >
            {text}
        </text>
    {/each}

    <g transform={`translate(0 -${50 + titleElements?.length * 20})`}>
        {#each titleElements as text, i}
            <text
                x="50%"
                y={`${100 + i * 3}%`}
                font-size={fontSize(parameters?.title, 5)}
                text-anchor="middle"
                data-title="innerText"
                data-text-color="fill"
                text-shadow="#FC0 1px 0 10px"
                font-family="GhoticRegular"
                font-weight="bold"
                letter-spacing="0.1em"
                fill={parameters?.textColor || 'black'}
                style="text-transform:uppercase"
                >{text}
            </text>
        {/each}
    </g>

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
</svg>
