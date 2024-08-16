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
    import Select from 'svelte-select'

    export let story: { data: App.Story }
    export let chapters: { data: App.Chapter[] }

    let frame: HTMLIFrameElement

    let outlines = []
    let selected: any
    let selectItems = []

    $: outline = outlines.find((o) => o.page === selected)

    let checkRegenerateCounter
    let checkRegenerateInterval = 1000

    let needToUpdate = false
    let regenerateStatus = false
    let regeneratePreviewStatus = false

    async function checkRegenerateCounterCallback() {
        if (checkRegenerateInterval == 1000) {
            clearInterval(checkRegenerateCounter)
            checkRegenerateInterval = 5000
            checkRegenerateCounter = setInterval(() => {
                checkRegenerateCounterCallback()
            }, checkRegenerateInterval)
        }

        const response = await fetch(`/stories/${story.data.id}/regenerate_status`)
        const json = await response.json()

        regenerateStatus = json?.regenerate_status ?? false
        regeneratePreviewStatus = json?.regenerate_preview_status ?? false

        if (regeneratePreviewStatus) {
            needToUpdate = true
        }

        if (needToUpdate && !regeneratePreviewStatus) {
            router.visit(`/stories/${story.data.id}/preview`, {
                onFinish: () => {
                    needToUpdate = false
                },
            })
        }
    }

    function selectChangeHandle(frame: any) {
        const viewer = frame.contentWindow.PDFViewerApplication

        viewer.page = selected.value + 1
    }

    onMount(() => {
        let load

        checkRegenerateCounter = setInterval(() => {
            checkRegenerateCounterCallback()
        }, checkRegenerateInterval)

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

                        outlines.map((o) => {
                            selectItems.push({
                                id: o.page,
                                value: o.page,
                                label: o.title,
                            })
                        })

                        viewer.eventBus.on('pagechanging', async () => {
                            selected = selectItems.findLast((o) => o.value <= viewer.page) ?? selected
                        })

                        selected = selectItems.findLast((o) => o.value <= viewer.page) ?? selected
                    })
                })
            })
        )

        return () => {
            if (checkRegenerateCounter) clearInterval(checkRegenerateCounter)
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
        {#if regeneratePreviewStatus}
            (updating preview <span class="loading loading-spinner" />)
        {/if}
        {#if regenerateStatus}
            (updating printable version <span class="loading loading-spinner" />)
        {/if}
    </h1>

    <Select
        bind:value={selected}
        bind:items={selectItems}
        on:change={() => selectChangeHandle(frame)}
        placeholder=""
        clearable={false}
        searchable={true}
        showChevron
    ></Select>

    <section
        class="grid grid-flow-row gap-4 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-4 xl:grid-cols-4 2xl:grid-cols-4"
    >
        <iframe
            title={story.data.title}
            src="/pdf/web/viewer.html?file={encodeURIComponent(story.data.book_preview)}"
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
