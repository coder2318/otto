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
    import { faClose, faPlus, faTrash } from '@fortawesome/free-solid-svg-icons'
    import background from '@/assets/img/stories-bg.jpg'
    import { onMount } from 'svelte'

    export let stories: {
        data: Array<App.Story>
        links: App.PaginationLinks
        meta: App.PaginationMeta
    }

    let query: { filter?: { [key: string]: string } }
    let filter: Writable<{ [key: string]: string }>

    let modal: HTMLDialogElement
    let storyId: number = null

    onMount(() => {
        filter = writable(qs.parse($page.url.replace(window.location.pathname, '').slice(1))?.filter ?? {})

        filter.subscribe((value) => {
            router.visit(window.location.pathname + '?' + qs.stringify({ filter: value }), { only: ['stories'] })
        })

        page.subscribe((value) => {
            query = qs.parse(value.url.replace(window.location.pathname, '').slice(1))
        })
    })

    function removeFilter(key: string) {
        filter.update((value) => {
            delete value[key]
            return value
        })
    }

    function deleteStory(id: number) {
        storyId = id
        modal.showModal()
    }

    function confirmDelete() {
        modal.close()
        router.delete(`stories/${storyId}`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - My Stories</title>
</svelte:head>

<header class="hero relative bg-top" style="background-image: url({background})" in:fade>
    <div class="hero-overlay absolute bg-gradient-to-br from-primary/80 to-primary/60" />
    <div
        class="container hero-content my-8 flex-col items-stretch justify-between text-primary-content md:my-12 lg:my-16"
    >
        <div class="flex justify-between">
            <h1 class="text-3xl md:text-4xl lg:text-5xl">My Stories</h1>
            <a href="/stories/create" use:inertia class="btn btn-secondary">
                <Fa icon={faPlus} /> Create Story
            </a>
        </div>
    </div>
</header>

<section class="container m-4 mx-auto flex border-b-2 border-base-content/20 lg:my-8" in:fade>
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

<main class="container mx-auto flex flex-col items-center justify-center gap-8 p-4 lg:py-8" in:fade>
    <div class="grid grid-cols-1 justify-center gap-8 md:grid-cols-2 lg:grid-cols-3">
        {#each stories.data as story (story.id)}
            <a
                class="group card bg-neutral transition-transform hover:scale-105"
                href="/stories/{story.id}"
                use:inertia
                in:fade
            >
                <figure class="max-h-[300px] bg-base-200">
                    <img
                        src={story?.cover?.url ??
                            `https://placehold.co/300x450?${new URLSearchParams({
                                text: story.title,
                            })}`}
                        class="aspect-[2/3] h-full object-cover object-right"
                        alt={story.title}
                    />
                </figure>
                <div class="card-body">
                    <h2 class="card-title text-2xl font-normal">
                        {story.title}
                    </h2>
                    <div class="card-actions justify-between">
                        <div>
                            Started: {dayjs(story.created_at).format('MMM DD, YYYY')}
                        </div>
                        <div class="badge badge-outline">{story.status}</div>
                    </div>
                    <div class="absolute right-4 top-4">
                        <button
                            class="btn btn-circle btn-error btn-outline btn-sm opacity-0 transition-opacity group-hover:opacity-100"
                            on:click|preventDefault={() => deleteStory(story.id)}
                        >
                            <Fa icon={faTrash} />
                        </button>
                    </div>
                </div>
            </a>
        {/each}
    </div>

    <dialog bind:this={modal} class="modal">
        <form method="dialog" class="modal-backdrop">
            <button />
        </form>
        <form method="dialog" class="modal-box">
            <div class="flex justify-end">
                <button class="btn btn-circle btn-sm bg-white" on:click={() => modal.close()}>
                    <Fa icon={faClose} />
                </button>
            </div>
            <h3 class="text-center text-[30px] text-xl font-normal leading-[33px]">
                Are you sure <i>want to delete this story?</i>
            </h3>
            <div class="modal-action mt-12 flex justify-around">
                <button class="btn btn-primary btn-sm w-[150px] rounded-full" on:click|preventDefault={confirmDelete}
                    >Yes</button
                >
                <button class="btn btn-sm w-[150px] rounded-full py-1" on:click={() => modal.close()}> No </button>
            </div>
        </form>
    </dialog>

    <Paginator meta={stories.meta} />
</main>
