<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { router } from '@inertiajs/core'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
    import { inertia } from '@inertiajs/svelte'
    import { onMount } from 'svelte'
    import Fa from 'svelte-fa'
    export let story: { data: App.Story }
    export let chapters: { data: App.Chapter[] }

    let select: HTMLSelectElement
    let frame: HTMLIFrameElement

    let outlines = []
    let selected = 0

    $: outline = outlines.find((o) => o.page === selected)

    let checkRegenerateCounter
    let needToUpdate = false

    async function checkRegenerateCounterCallback() {
        const response = await fetch(`/stories/${story.data.id}/regenerate_counter`)
        const json = await response.json()

        story.data.regenerate_counter = json?.regenerate_counter ?? 0

        if (needToUpdate && story.data.regenerate_counter == 0) {
            router.visit(`/stories/${story.data.id}/preview`, {
                onFinish: (visit) => {
                    needToUpdate = false
                    clearInterval(checkRegenerateCounter)
                },
            })
        }
    }

    onMount(() => {
        let load, change
        console.log(story.data)
        if (story.data.regenerate_counter) {
            needToUpdate = true
            checkRegenerateCounter = setInterval(() => {
                checkRegenerateCounterCallback()
            }, 5000)
        }

        frame.addEventListener(
            'load',
            (load = () => {
                // @ts-ignore
                const viewer = frame.contentWindow.PDFViewerApplication

                viewer.initializedPromise.then(() => {
                    viewer.eventBus.on('pagesloaded', async () => {
                        const _outlines = await viewer.pdfDocument.getOutline()

                        outlines = await Promise.all(
                            _outlines.map(async (o) => ({
                                title: o.title,
                                page: await viewer.pdfDocument.getPageIndex(o.dest[0]),
                                chapter: chapters.data.find((c) => c.title === o.title),
                            }))
                        )

                        outlines.unshift({
                            title: 'Table of Contents',
                            page: 0,
                            chapter: null,
                        })

                        viewer.eventBus.on('pagechanging', async (e) => {
                            selected = outlines.find((o) => o.page === e.pageNumber - 1)?.page ?? selected
                        })

                        select.addEventListener(
                            'change',
                            (change = () => {
                                viewer.page = selected + 1
                            })
                        )

                        selected = viewer.page - 1
                    })
                })
            })
        )

        return () => {
            if (checkRegenerateCounter) clearInterval(checkRegenerateCounter)
            if (change) select.removeEventListener('change', change)
            if (load) frame.removeEventListener('load', load)
        }
    })
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<section class="container mx-auto flex min-h-[calc(100vh-67px)] flex-col items-stretch gap-4 px-4 pb-4">
    <Breadcrumbs step={2} {story} />
    <h1 class="text-3xl text-primary">
        Book Preview - <i>{story.data.title}</i>
        {#if story.data.regenerate_counter}
            (updating preview <span class="loading loading-spinner" />)
        {/if}
    </h1>

    <select bind:this={select} bind:value={selected} name="bookmarks" class="select select-primary">
        {#each outlines as outline}
            <option value={outline.page}>{outline.title}</option>
        {/each}
    </select>

    <section
        class="grid grid-flow-row gap-4 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-4 xl:grid-cols-4 2xl:grid-cols-4"
    >
        <iframe
            title={story.data.title}
            src="/pdf/web/viewer.html?file={encodeURIComponent(story.data.book)}"
            width="100%"
            class="col-span-3 min-h-[60vh] flex-1 bg-base-300"
            bind:this={frame}
        />
        <div class="flex flex-col gap-4">
            <h2 class="text-2xl text-primary">Issues</h2>

            <div class="card bg-base-200 text-base-content">
                <div class="card-body">
                    <p>This part of your book looks ready to print!</p>

                    <div class="card-actions">
                        {#if outline?.chapter}
                            <a
                                href="/chapters/{outline.chapter.id}/write"
                                use:inertia
                                class="btn btn-primary btn-outline w-full rounded-full">Edit Chapter</a
                            >
                        {/if}
                        <a href="/stories/{story.data.id}/order" use:inertia class="btn btn-primary w-full rounded-full"
                            >Finalize Your Order</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="flex items-center justify-between">
        <a href="/stories/{story.data.id}/edit" use:inertia class="btn btn-neutral rounded-full pl-0">
            <span class="badge mask badge-accent mask-circle p-4">
                <Fa icon={faArrowLeft} />
            </span>
            Go Back
        </a>
    </div>
</section>
