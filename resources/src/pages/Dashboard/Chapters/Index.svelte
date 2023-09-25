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
    import { truncate } from '@/service/helpers'
    import customChapter from '@/assets/img/custom-chapter.jpg'
    import InviteGuestModal from '@/components/Chapters/InviteGuestModal.svelte'

    export let questions_chapters: {
        data: App.Chapter[]
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }
    export let timelines: { data: App.Timeline[] }
    export let story: { data: App.Story }

    let modal: InviteGuestModal

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
            { only: ['questions_chapters'] }
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
    <div class="hero-overlay bg-gradient-to-br from-primary/80 to-primary/40" />
    <div
        class="container hero-content my-8 flex-col items-stretch justify-between text-primary-content md:my-12 lg:my-16"
    >
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="/stories" use:inertia>Stories</a></li>
                <li>
                    <a href="/stories/{story.data.id}" use:inertia
                        >{truncate(story.data.title, 20)}</a
                    >
                </li>
                <li>Writing Room</li>
            </ul>
        </div>
        <div class="flex justify-between">
            <h1 class="text-3xl font-bold italic md:text-4xl lg:text-5xl">
                {story.data.title}
            </h1>
        </div>
    </div>
</header>

<section
    class="gird-cols-1 container m-4 mx-auto grid place-content-between gap-4 border-b-2 border-base-content/20 px-4 pb-4 md:grid-cols-2 md:pb-0 lg:my-8"
    in:fade
>
    <div class="tabs">
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
            class:tab-active={query?.filter?.status == 'undone'}
            class:tab-bordered={query?.filter?.status == 'undone'}
            on:click|preventDefault={() => ($filter.status = 'undone')}
        >
            Undone
        </button>
        <button
            class="tab -mb-0.5"
            class:tab-active={query?.filter?.status == 'draft'}
            class:tab-bordered={query?.filter?.status == 'draft'}
            on:click|preventDefault={() => ($filter.status = 'draft')}
        >
            In Progress
        </button>
        <button
            class="tab -mb-0.5"
            class:tab-active={query?.filter?.status == 'published'}
            class:tab-bordered={query?.filter?.status == 'published'}
            on:click|preventDefault={() => ($filter.status = 'published')}
        >
            Completed
        </button>
    </div>

    <span class="flex gap-2 md:ml-auto">
        Timeline:
        <select
            class="select select-bordered select-ghost select-xs w-48"
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
        class="grid w-full grid-cols-1 justify-center gap-8 md:grid-cols-2 lg:grid-cols-3"
    >
        <div
            class="card min-h-[36rem] bg-cover bg-center bg-no-repeat"
            style="background-image: url({customChapter})"
        >
            <div class="card-body items-center justify-end">
                <div class="card-actions justify-between">
                    <a
                        href="/stories/{story.data.id}/chapters/create"
                        use:inertia
                        class="btn btn-neutral btn-lg rounded-full"
                    >
                        Create Custom Question
                    </a>
                </div>
            </div>
        </div>
        {#each questions_chapters.data as chapter (chapter.type + chapter.id)}
            <a
                class="card min-h-[36rem] bg-neutral transition-transform hover:scale-105"
                href={chapter.type === 'question'
                    ? `/stories/${story.data.id}/questions/${chapter.id}/chapters/create`
                    : `/chapters/${chapter.id}/edit`}
                use:inertia
                in:fade
            >
                <div class="card-body">
                    <figure class="mb-2 rounded-xl">
                        <img
                            src={chapter.cover ??
                                'https://placehold.co/600x400?text=Your+cover+should+be+there'}
                            alt={chapter.title}
                        />
                    </figure>
                    <h2 class="card-title text-2xl font-normal">
                        {chapter.title}
                    </h2>
                    {#if chapter.type === 'question'}
                        <div class="card-actions">
                            <button
                                class="btn btn-secondary btn-sm"
                                on:click|preventDefault={() =>
                                    modal.invite(chapter)}
                            >
                                Invite Guest
                            </button>
                        </div>
                    {/if}
                    <p />
                    <div class="card-actions justify-between">
                        <div>
                            Started: {dayjs(chapter.created_at).format(
                                'MMM DD, YYYY'
                            )}
                        </div>
                        <div class="badge badge-outline">
                            {chapter.status}
                        </div>
                    </div>
                </div>
            </a>
        {/each}
    </div>

    <Paginator
        class="flex-wrap items-center justify-center gap-y-2"
        meta={questions_chapters.meta}
    />
</main>

<InviteGuestModal story_id={story.data.id} bind:this={modal} />
