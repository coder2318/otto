<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import BookCoverBuilder from '@/components/Stories/BookCoverBuilder.svelte'

    export let story: { data: App.Story }

    let parameters = {
        titleBig: 'Title Big',
        titleSmall: 'Title Small',
        author: 'Author',
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<input type="text" bind:value={parameters.titleBig} />

<BookCoverBuilder {parameters} />
