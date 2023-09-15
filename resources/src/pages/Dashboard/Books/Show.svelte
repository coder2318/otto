<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'

    export let story: { data: App.Story }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<main class="container mx-auto p-4" in:fade>
    <div
        class="prose max-h-screen max-w-none select-none overflow-hidden prose-headings:text-primary"
    >
        <h1>{story.data.title}</h1>

        {#each story.data.chapters as chapter}
            <h2>{chapter.title}</h2>

            {@html chapter.content}
        {/each}
    </div>
</main>

<div
    class="fixed bottom-0 flex h-48 w-full items-center justify-center bg-gradient-to-b from-transparent to-base-300"
>
    <button
        type="button"
        class="btn btn-primary btn-lg rounded-full"
        on:click={() => {}}
    >
        Subscribe to Read Book
    </button>
</div>
