<script lang="ts">
    import { fade } from 'svelte/transition';
    import { createEventDispatcher } from 'svelte'
    const dispatch = createEventDispatcher()
    export let form
</script>

<div class="flex flex-col h-full w-full items-center justify-center" in:fade>
    4
    <div class="form-control">
        <button class="btn btn-primary w-40" on:click={() => dispatch('next')}>Continue</button>
    </div>
</div>
