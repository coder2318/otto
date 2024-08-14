<script lang="ts">
    import { onMount } from 'svelte'

    import Select from 'svelte-select'

    export let fonts: App.Font[]
    export let value: string
    export let labelText: string = 'Select font:'

    let items: { value: string; label: string }[] = []

    onMount(() => {
        items = (fonts || []).map(({ value, name: label }) => ({
            value,
            label,
        }))
        value = value || fonts?.[0]?.value || ''

        loadFonts()
    })

    async function loadFonts() {
        for (const font of fonts) {
            const fontFace = new FontFace(font.name, `url('${font.path}')`)
            try {
                fontFace.load()

                const style = document.createElement('style')

                style.textContent = `
                    @font-face {
                        font-family: '${font.value}';
                        src: url('${font.path}') format('truetype');
                    }
                `
                document.head.appendChild(style)
                console.log(font.name, 'loaded')
            } catch (error) {
                console.error(font, error)
            }
        }
    }
</script>

<div class="flex w-full flex-col md:w-fit md:min-w-96">
    <span>{labelText}</span>
    <Select
        containerStyles="font-family: '{value}';"
        bind:items
        {value}
        bind:justValue={value}
        on:change={(event) => (value = event.detail.value)}
        clearable={false}
        searchable={false}
        showChevron
        required
        --item-height="auto"
        --item-line-height="auto"
        {...$$restProps}
    >
        <span slot="item" class="font-item" let:item style="font-family: '{item.value}';">
            {item.label}
        </span>
    </Select>
</div>

<style lang="scss">
    .font-item {
        min-height: 16px;
        padding: 10px 0;
        line-height: 16px;
        display: flex;
        line-break: auto;
        white-space: pre-wrap;
        align-items: center;
    }
</style>
