<script lang="ts">
    import { createEventDispatcher } from 'svelte'
    const dispatch = createEventDispatcher()
    export let form
</script>

3

<button on:click={() => dispatch('next')}>Next</button>
