<script lang="ts">
    import { createEventDispatcher } from 'svelte'
    const dispatch = createEventDispatcher()
    export let form

    console.log(form)
</script>

1

<button on:click={() => dispatch('next')}>Next</button>
