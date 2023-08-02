<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import Fa from 'svelte-fa'
    import Paginator from '@/components/Paginator.svelte'
    import { inertia, page } from '@inertiajs/svelte'
    import { dayjs } from '@/service/dayjs'
    import { faPlus } from '@fortawesome/free-solid-svg-icons'
    import background from '@/assets/img/stories-bg.jpg'
    export let query: any
    export let stories: {
        data: Array<App.Story>
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }

    function filters(parameters = {}) {
        return (
            window.location.origin +
            window.location.pathname +
            '?' +
            new URLSearchParams(parameters).toString()
        )
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - My Stories</title>
</svelte:head>

<header class="hero bg-top" style="background-image: url({background})" in:fade>
    <div class="hero-overlay bg-gradient-to-br from-primary/80 to-primary/60" />
    <div
        class="container hero-content my-8 justify-between text-primary-content md:my-12 lg:my-16"
    >
        <h1 class="text-3xl font-bold italic md:text-4xl lg:text-5xl">
            My Stories
        </h1>
        <a href="/stories/create" use:inertia class="btn btn-secondary">
            <Fa icon={faPlus} />
            Create Story
        </a>
    </div>
</header>

<section class="container mx-auto flex p-4 lg:py-8" in:fade>
    <div class="tabs flex-1 border-b-2 border-base-content/20">
        <a
            class="tab -mb-0.5 {!query?.filter?.status
                ? 'tab-active tab-bordered'
                : ''}"
            href={filters()}
            use:inertia>All</a
        >
        <a
            class="tab -mb-0.5 {query?.filter?.status == 'pending'
                ? 'tab-active tab-bordered'
                : ''}"
            href={filters({ 'filter[status]': 'pending' })}
            use:inertia>Pending</a
        >
        <a
            class="tab -mb-0.5 {query?.filter?.status == 'published'
                ? 'tab-active tab-bordered'
                : ''}"
            href={filters({ 'filter[status]': 'published' })}
            use:inertia>Published</a
        >
    </div>
</section>

<main
    class="container mx-auto flex flex-col items-center justify-center gap-8 p-4 lg:py-8"
    in:fade
>
    <div
        class="grid grid-cols-1 justify-center gap-8 md:grid-cols-2 lg:grid-cols-3"
    >
        {#each stories.data as story (story.id)}
            <a
                class="card bg-neutral transition-transform hover:scale-105"
                href="/stories/{story.id}"
                use:inertia
                in:fade
            >
                <figure>
                    <img src={story.cover} alt={story.title} />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">{story.title}</h2>
                    <p />
                    <div class="card-actions justify-between">
                        <div>
                            Started: {dayjs(story.created_at).format(
                                'MMM DD, YYYY'
                            )}
                        </div>
                        <div class="badge badge-outline">{story.status}</div>
                    </div>
                </div>
            </a>
        {/each}
    </div>

    <Paginator meta={stories.meta} />
</main>
