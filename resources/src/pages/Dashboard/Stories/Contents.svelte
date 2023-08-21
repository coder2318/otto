<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import {
        faArrowRight,
        faGripLines,
        faPencil,
    } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'
    import { onMount } from 'svelte'
    import Sortable from 'sortablejs'

    export let story: { data: App.Story }
    export let chapters: { [timeline_id: number]: { data: App.Chapter[] } }
    export let timelines: { data: App.Timeline[] }

    let lists: HTMLOListElement[] = []
    let inputs: HTMLInputElement[] = []

    onMount(() => {
        const sortables = []
        lists.forEach((list) => {
            sortables.push(
                new Sortable(list, {
                    group: 'shared',
                    animation: 150,
                    handle: '.cursor-grab',
                })
            )
        })

        return () => {
            sortables.forEach((sortable) => sortable.destroy())
        }
    })
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

<section class="container mx-auto mb-4 px-4">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h1 class="my-4 text-3xl text-primary">
            Table of <i>Contents</i>
        </h1>
        <a
            href="/stories/{story.data.id}/preview"
            use:inertia
            class="btn btn-secondary rounded-full pr-0"
        >
            Preview Your Book
            <span class="badge mask badge-neutral mask-circle p-4"
                ><Fa icon={faArrowRight} /></span
            >
        </a>
    </div>
</section>

<main class="container mx-auto mb-16 flex flex-col gap-6 px-4">
    {#each timelines.data.filter((t) => chapters?.[t.id]?.data?.length) as timeline, index (timeline.id)}
        <div class="otto-colapse collapse-arrow collapse bg-base-200">
            <input type="checkbox" name="timeline-collapse" checked={!index} />
            <div class="collapse-title text-xl font-medium">
                {timeline.title}
            </div>
            <div class="collapse-content">
                <ol class="flex flex-col gap-4" bind:this={lists[timeline.id]}>
                    {#each chapters[timeline.id]?.data as chapter, index (chapter.id)}
                        <li
                            class="flex items-center justify-between gap-4 rounded-xl border border-neutral-content/20 p-2"
                        >
                            <span
                                class="badge badge-neutral h-8 w-8 cursor-grab rounded-full"
                            >
                                <Fa icon={faGripLines} />
                            </span>
                            <span class="flex-1"
                                >{index + 1}. {chapter.title}</span
                            >
                            <a
                                href="/chapters/{chapter.id}/edit"
                                class="btn btn-circle btn-ghost btn-sm border border-base-content/20"
                            >
                                <Fa icon={faPencil} />
                            </a>
                        </li>
                    {/each}
                </ol>
            </div>
        </div>
    {/each}
</main>

<style lang="scss">
    .otto-colapse {
        @apply border border-primary text-primary transition-colors duration-300;

        .collapse-title {
            @apply font-serif text-2xl;
        }

        .collapse-content {
            @apply bg-base-300 text-base-content;
        }

        &:has(input:checked) {
            @apply bg-primary text-primary-content;

            .collapse-content {
                @apply pt-4;
            }
        }
    }
</style>
