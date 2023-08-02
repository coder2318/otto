<script context="module" lang="ts">
    import { writable } from 'svelte/store'

    export const loading = writable(false)
    export const start = () => loading.set(true)
    export const done = () => loading.set(false)
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
</script>

{#if $loading}
    <div
        class="fixed inset-0 z-50 flex h-screen w-screen flex-col items-center justify-center bg-primary"
        transition:fade
    >
        <span class="loading loading-spinner w-36 text-primary-content" />
    </div>
{/if}
