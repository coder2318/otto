<script lang="ts">
    import { createEventDispatcher } from 'svelte'
    const dispatch = createEventDispatcher()
    export let form
</script>

4

<button on:click={() => dispatch('next')}>Next</button>
