<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import qs from 'qs'
    import { writable } from 'svelte/store'
    import { fade } from 'svelte/transition'
    import Paginator from '@/components/Paginator.svelte'
    import { inertia, page, router } from '@inertiajs/svelte'
    import { dayjs } from '@/service/dayjs'

    export let timelines: { data: App.Timeline[] }
    export let story: { data: App.Story }
    export let chapters: {
        data: Array<App.Story>
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }

    $: query = qs.parse(
        $page.url.replace(window.location.pathname, '').slice(1)
    )

    const filter = writable(
        qs.parse($page.url.replace(window.location.pathname, '').slice(1))
            ?.filter ?? {}
    )

    filter.subscribe((value) => {
        router.visit(
            window.location.pathname + '?' + qs.stringify({ filter: value }),
            { only: ['chapters'] }
        )
    })

    function removeFilter(key: string) {
        filter.update((value) => {
            delete value[key]
            return value
        })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - My Stories</title>
</svelte:head>

<header class="hero" style="background-image: url({story.data.cover})" in:fade>
    <div class="hero-overlay bg-gradient-to-br from-primary/40 to-primary/10" />
    <div
        class="container hero-content my-8 flex-col items-start justify-between text-primary-content md:my-12 lg:my-16"
    >
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="/stories" use:inertia>Stories</a></li>
                <li>
                    <a href="/stories/{story.data.id}" use:inertia
                        >{story.data.title}</a
                    >
                </li>
                <li>Writing Room</li>
            </ul>
        </div>
        <h1 class="text-3xl font-bold italic md:text-4xl lg:text-5xl">
            {story.data.title}
        </h1>
    </div>
</header>

<section
    class="container m-4 mx-auto flex border-b-2 border-base-content/20 lg:my-8"
    in:fade
>
    <div class="tabs flex-1">
        <button
            class="tab -mb-0.5"
            class:tab-active={query?.filter?.status == undefined}
            class:tab-bordered={query?.filter?.status == undefined}
            on:click|preventDefault={() => removeFilter('status')}
        >
            All
        </button>
        <button
            class="tab -mb-0.5"
            class:tab-active={query?.filter?.status == 'pending'}
            class:tab-bordered={query?.filter?.status == 'pending'}
            on:click|preventDefault={() => ($filter.status = 'pending')}
        >
            Pending
        </button>
        <button
            class="tab -mb-0.5"
            class:tab-active={query?.filter?.status == 'published'}
            class:tab-bordered={query?.filter?.status == 'published'}
            on:click|preventDefault={() => ($filter.status = 'published')}
        >
            Published
        </button>
    </div>

    <span class="flex gap-2">
        Timeline:
        <select
            class="select select-bordered select-ghost select-xs"
            on:change={(e) =>
                e.currentTarget.value !== ''
                    ? ($filter.timeline_id = e.currentTarget.value)
                    : removeFilter('timeline_id')}
        >
            <option value="" selected={query?.filter?.timeline_id == undefined}
                >All</option
            >
            {#each timelines.data as timeline}
                <option
                    value={timeline.id}
                    selected={query?.filter?.timeline_id == timeline.id}
                    >{timeline.title}</option
                >
            {/each}
        </select>
    </span>
</section>

<main
    class="container mx-auto flex flex-col items-center justify-center gap-8 p-4 lg:py-8"
    in:fade
>
    <div
        class="grid grid-cols-1 justify-center gap-8 md:grid-cols-2 lg:grid-cols-3"
    >
        {#each chapters.data as chapter (chapter.id)}
            <a
                class="card bg-neutral transition-transform hover:scale-105"
                href="/stories/{story.data.id}/chapters/{chapter.id}"
                use:inertia
                in:fade
            >
                <figure>
                    <img src={chapter.cover} alt={chapter.title} />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">{chapter.title}</h2>
                    <p />
                    <div class="card-actions justify-between">
                        <div>
                            Started: {dayjs(chapter.created_at).format(
                                'MMM DD, YYYY'
                            )}
                        </div>
                        <div class="badge badge-outline">{chapter.status}</div>
                    </div>
                </div>
            </a>
        {/each}
    </div>

    <Paginator meta={chapters.meta} />
</main>
