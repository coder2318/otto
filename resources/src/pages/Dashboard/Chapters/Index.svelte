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
    import background from '@/assets/img/stories-bg.jpg'
    import {
        faClose,
        faArrowDownLong,
        faTrash,
    } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'

    export let questions_chapters: {
        data: App.Chapter[]
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }
    export let timelines: { data: App.Timeline[] }
    export let story: { data: App.Story }

    let modal: InviteGuestModal
    let dialog: HTMLDialogElement
    let dropdownDialog: HTMLDialogElement
    let chapterId: number = null

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

    function deleteChapter(id: number) {
        chapterId = id
        dialog.showModal()
    }

    function confirmDelete() {
        dialog.close()
        router.delete(`/chapters/${chapterId}`)
    }

    function selectOption(e) {
        if ($filter.timeline_id == e.currentTarget.value) {
            removeFilter('timeline_id')
        } else {
            $filter.timeline_id = e.currentTarget.value
        }

        dropdownDialog.close()
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - My Stories</title>
</svelte:head>

<header
    class="hero relative bg-top"
    style="background-image: url({background})"
    in:fade
>
    <div
        class="hero-overlay absolute bg-gradient-to-br from-primary/80 to-primary/40"
    />
    <div
        class="container hero-content my-8 flex-col items-stretch justify-between text-primary-content md:my-12 lg:my-16"
    >
        <!-- <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="/stories" use:inertia>Stories</a></li>
                <li>
                    <a href="/stories/{story.data.id}" use:inertia
                        >{truncate(story.data.title, 20)}</a
                    >
                </li>
                <li>
                    <a href="/stories/{story.data.id}" use:inertia
                        >Writing Room</a
                    >
                </li>
            </ul>
        </div> -->
        <div class="flex justify-between">
            <h1 class="text-3xl font-bold italic md:text-4xl lg:text-5xl">
                <span class="font-normal text-neutral">Explore Your</span>
                <button
                    on:click={() => dropdownDialog.showModal()}
                    class="inline-flex items-center rounded-full border-none bg-neutral/30 px-8 py-4 backdrop-blur hover:clear-none"
                >
                    <i class="mr-20 font-normal text-neutral"
                        >{timelines.data[query?.filter?.timeline_id - 1]
                            ?.title ?? 'Timeline'}</i
                    >
                    <Fa class="text-2xl text-neutral" icon={faArrowDownLong} />
                </button>
                <dialog bind:this={dropdownDialog} class="modal">
                    <form method="dialog" class="modal-backdrop bg-primary/80">
                        <button />
                    </form>
                    <div class="modal-box bg-transparent shadow-none">
                        <ul class="flex flex-col gap-4">
                            {#each timelines.data as timeline}
                                <li class="text-center">
                                    <button
                                        class="btn btn-lg w-96 justify-start rounded-full border-none bg-neutral/30 px-8 py-4 font-serif text-4xl font-normal italic leading-none text-neutral backdrop-blur hover:clear-none"
                                        class:!bg-secondary={query?.filter
                                            ?.timeline_id == timeline.id}
                                        on:click={selectOption}
                                        value={timeline.id}
                                    >
                                        {timeline.title}
                                    </button>
                                </li>
                            {/each}
                        </ul>
                    </div>
                </dialog>
                <span class="font-normal text-neutral"
                    >With These Questions</span
                >
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
</section>

<main
    class="container mx-auto flex flex-col items-center justify-center gap-8 p-4 lg:py-8"
    in:fade
>
    <div
        class="grid w-full grid-cols-1 justify-center gap-8 md:grid-cols-2 lg:grid-cols-3"
    >
        {#if query?.filter?.timeline_id}
            <div
                class="card min-h-[36rem] bg-cover bg-center bg-no-repeat"
                style="background-image: url({customChapter})"
            >
                <div class="card-body items-center justify-end">
                    <div class="card-actions justify-between">
                        <a
                            href="/stories/{story.data
                                .id}/chapters/create?timeline_id={query?.filter
                                    ?.timeline_id}"
                            use:inertia
                            class="btn btn-neutral btn-lg rounded-full"
                        >
                            Create Custom Question
                        </a>
                    </div>
                </div>
            </div>
        {/if}
        {#each questions_chapters.data as chapter (chapter.type + chapter.id)}
            <a
                class="group card min-h-[36rem] bg-neutral transition-transform hover:scale-105"
                href={chapter.type === 'question'
                    ? `/stories/${story.data.id}/questions/${chapter.id}/chapters/create`
                    : `/chapters/${chapter.id}/edit`}
                use:inertia
                in:fade
            >
                <div class="card-body">
                    <figure class="mb-2 max-h-[300px]">
                        <img
                            src={chapter.cover ??
                                `https://placehold.co/600x400?${new URLSearchParams(
                                    { text: chapter.title }
                                ).toString()}`}
                            alt={chapter.title}
                            class="h-full rounded-xl object-contain"
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
                    {#if chapter.status !== 'undone'}
                        <div class="absolute right-4 top-4">
                            <button
                                class="btn btn-circle btn-error btn-outline btn-sm opacity-0 transition-opacity group-hover:opacity-100"
                                on:click|preventDefault={() =>
                                    deleteChapter(chapter.id)}
                            >
                                <Fa icon={faTrash} />
                            </button>
                        </div>
                    {/if}
                </div>
            </a>
        {/each}
    </div>

    <dialog bind:this={dialog} class="modal">
        <form method="dialog" class="modal-backdrop">
            <button />
        </form>
        <form method="dialog" class="modal-box">
            <div class="flex justify-end">
                <button
                    class="btn btn-circle btn-sm bg-white"
                    on:click={() => dialog.close()}
                >
                    <Fa icon={faClose} />
                </button>
            </div>
            <h3
                class="text-center text-[30px] text-xl font-normal leading-[33px]"
            >
                Are you sure <i>want to delete this chapter?</i>
            </h3>
            <div class="modal-action mt-12 flex justify-around">
                <button
                    class="btn btn-primary btn-sm w-[150px] rounded-full"
                    on:click|preventDefault={confirmDelete}>Yes</button
                >
                <button
                    class="btn btn-sm w-[150px] rounded-full py-1"
                    on:click={() => dialog.close()}
                >
                    No
                </button>
            </div>
        </form>
    </dialog>

    <Paginator
        class="flex-wrap items-center justify-center gap-y-2"
        meta={questions_chapters.meta}
    />
</main>

<InviteGuestModal story_id={story.data.id} bind:this={modal} />
