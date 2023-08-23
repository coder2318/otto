<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    export let story: { data: App.Story }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<section
    class="container mx-auto flex min-h-[calc(100vh-66px)] flex-col items-stretch px-4 pb-4"
>
    <Breadcrumbs step={3} {story} />
    <h1 class="my-4 text-3xl text-primary">Order Details</h1>
</section>
