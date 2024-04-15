<script lang="ts">
    import RangeSlider from 'svelte-range-slider-pips'
    import { afterUpdate } from 'svelte'

    export let values
    export let valuesArr = [values]
    export let min = 0
    export let max = 256
    export let inputName = 'value'
    afterUpdate(() => {
        if (!isNaN(valuesArr[0])) {
            values = valuesArr[0]
        }
    })
</script>

<div class="range-slider-container">
    <div class="range-slider">
        <RangeSlider bind:values={valuesArr} {min} {max} hoverable />
    </div>
    <input class="range-slider-input input input-bordered" type="number" bind:value={valuesArr[0]} name={inputName} />
</div>

<style>
    :root {
        --range-handle: #ffd58f;
        --range-handle-focus: hsl(var(--s));
        --range-handle-inactive: hsl(var(--s));
    }

    .range-slider-container {
        display: flex;
        width: 100%;
    }
    .range-slider {
        width: 100%;
        height: 100%;
        flex: 0.9;
        margin: auto;
    }
    .range-slider-input {
        flex: 0.1;
        max-width: 80px;
    }
</style>
