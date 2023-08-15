<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import qs from 'qs'
    import { writable, type Writable } from 'svelte/store'
    import { fade } from 'svelte/transition'
    import Fa from 'svelte-fa'
    import Paginator from '@/components/Paginator.svelte'
    import { inertia, page, router } from '@inertiajs/svelte'
    import { dayjs } from '@/service/dayjs'
    import { faPlus } from '@fortawesome/free-solid-svg-icons'
    import background from '@/assets/img/stories-bg.jpg'
    import { onMount } from 'svelte'

    export let stories: {
        data: Array<App.Story>
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }

    let query: { filter?: { [key: string]: string } }
    let filter: Writable<{ [key: string]: string }>

    onMount(() => {
        filter = writable(
            qs.parse($page.url.replace(window.location.pathname, '').slice(1))
                ?.filter ?? {}
        )

        filter.subscribe((value) => {
            router.visit(
                window.location.pathname +
                    '?' +
                    qs.stringify({ filter: value }),
                { only: ['stories'] }
            )
        })

        page.subscribe((value) => {
            query = qs.parse(
                value.url.replace(window.location.pathname, '').slice(1)
            )
        })
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

<header class="hero bg-top" style="background-image: url({background})" in:fade>
    <div class="hero-overlay bg-gradient-to-br from-primary/80 to-primary/60" />
    <div
        class="container hero-content my-8 flex-col items-stretch justify-between text-primary-content md:my-12 lg:my-16"
    >
        <div class="breadcrumbs text-sm">
            <ul>
                <li>Stories</li>
            </ul>
        </div>
        <div class="flex justify-between">
            <h1 class="text-3xl font-bold italic md:text-4xl lg:text-5xl">
                My Stories
            </h1>
            <a href="/stories/create" use:inertia class="btn btn-secondary">
                <Fa icon={faPlus} /> Create Story
            </a>
        </div>
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
