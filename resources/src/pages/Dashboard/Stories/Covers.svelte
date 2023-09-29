<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import { fade, blur } from 'svelte/transition'
    import Paginator from '@/components/Paginator.svelte'
    import BookCoverBuilder from '@/components/Stories/BookCoverBuilder.svelte'

    export let story: { data: App.Story }
    export let covers: {
        data: App.BookCoverTemplate[]
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<Breadcrumbs step={1} {story} />

<section
    class="container card m-4 mx-auto grid grid-cols-1 gap-8 rounded-xl px-4 sm:grid-cols-3 lg:grid-cols-6"
    in:fade
>
    {#each covers.data as cover (cover.id)}
        <a
            class="card card-bordered border-neutral bg-neutral transition-transform hover:scale-105"
            href="/stories/{story.data.id}/cover?template={cover.id}"
            use:inertia
            out:blur={{ duration: 250 }}
            in:blur={{ delay: 250, duration: 250 }}
        >
            <figure>
                <BookCoverBuilder template={cover} pages={200} />
            </figure>
            <div class="card-body items-center">
                <h5 class="card-title">{cover.name}</h5>
            </div>
        </a>
    {/each}
</section>

<Paginator class="mx-auto py-4" meta={covers.meta} />
