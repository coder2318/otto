<script lang="ts">
    import { onMount } from 'svelte'

    export let template: string = `<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><image style="object-fit:contain" href="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==" height="100" width="100" data-cover="href" /><text style="fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 15.1px; font-weight: 700; white-space: pre;" transform="matrix(1, 0, 0, 1, -68.574997, -50.336933)"><tspan x="68.575" y="82.073" data-titleBig="innerText">Title Big</tspan><tspan x="68.575" dy="1em"></tspan><tspan x="68.575" dy="1em"></tspan></text><text style="fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 15.1px; font-style: italic; white-space: pre;" x="0.54" y="13.499" data-author="innerText">Author</text><text style="fill: rgb(51, 51, 51); font-family: Arial, sans-serif; font-size: 11px; white-space: pre;" x="0" y="47.175" data-titleSmall="innerText">Title Small</text></svg>`
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
                            node.setAttribute(key, value)
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
