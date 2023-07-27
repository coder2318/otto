<script context="module" lang="ts">
    import { writable } from 'svelte/store';

    export const loading = writable(false);
    export const start = () => loading.set(true);
    export const done = () => loading.set(false);
</script>

<script lang="ts">
    import { fade } from 'svelte/transition';
</script>

{#if $loading}
    <div class="fixed inset-0 h-screen w-screen flex flex-col justify-center items-center bg-primary z-50" transition:fade >
        <span class="loading loading-spinner text-primary-content w-36"/>
    </div>
{/if}
