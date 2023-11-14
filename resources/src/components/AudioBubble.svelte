<script lang="ts">
    import { fade } from 'svelte/transition'
    export let size: number = 3
    export let heights: Array<number> = []
    export let color = '#8EBFF0'
</script>

<div class="my-8 flex min-h-[10rem] flex-nowrap items-center justify-center gap-4" in:fade>
    {#each Array(size) as _, i (_)}
        <div
            style="background-color: {color}; height: {((heights[i] ?? 0) / 255) * 5 + 4}rem;"
            class="min-h-[5rem] w-20 rounded-[2.5rem]"
        />
    {/each}
</div>
