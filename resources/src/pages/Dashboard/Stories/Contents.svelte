<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
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

    const form = useForm({
        timelines: timelines.data.map((timeline) => ({
            id: timeline.id,
            chapters:
                chapters[timeline.id]?.data.map((chapter) => chapter.id) ?? [],
        })),
    })

    let lists: HTMLUListElement[] = []

    onMount(() => {
        const sortables = []
        lists.forEach((list) => {
            sortables.push(
                new Sortable(list, {
                    group: 'shared',
                    animation: 150,
                    handle: '.cursor-grab',
                    onEnd(event) {
                        const { oldIndex, newIndex } = event
                        const from = $form.timelines.find(
                            (timeline) =>
                                timeline.id == event.from.dataset.timeline
                        )
                        const to = $form.timelines.find(
                            (timeline) =>
                                timeline.id == event.to.dataset.timeline
                        )
                        to.chapters.splice(
                            newIndex,
                            0,
                            ...from.chapters.splice(oldIndex, 1)
                        )

                        $form.timelines = $form.timelines
                    },
                })
            )
        })

        return () => {
            sortables.forEach((sortable) => sortable.destroy())
        }
    })

    function submit() {
        $form.post(window.location.pathname, {
            onSuccess: () =>
                $form.defaults({
                    timelines: $form.timelines,
                }),
        })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<section class="container mx-auto px-4" in:fade>
    <Breadcrumbs step={2} {story} />
</section>

<form on:submit|preventDefault={submit}>
    <section class="container mx-auto mb-4 px-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="my-4 text-3xl text-primary">
                Table of <i>Contents</i>
            </h1>
            {#if $form.isDirty}
                <button
                    type="submit"
                    class="btn btn-primary rounded-full"
                    disabled={$form.processing}
                >
                    {#if $form.processing}<span class="loading loading-spinner"
                        ></span>{/if}
                    Save Chapter Order
                </button>
            {:else}
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
            {/if}
        </div>
    </section>

    <main class="container mx-auto mb-16 flex flex-col gap-6 px-4">
        {#each timelines.data as timeline (timeline.id)}
            <div class="otto-colapse collapse-arrow collapse bg-base-200">
                <input type="checkbox" />
                <div class="collapse-title text-xl font-medium">
                    {timeline.title}
                </div>
                <div class="collapse-content">
                    <ul
                        class="flex flex-col gap-4"
                        bind:this={lists[timeline.id]}
                        data-timeline={timeline.id}
                    >
                        {#each chapters[timeline.id]?.data ?? [] as chapter (chapter.id)}
                            <li
                                data-chapter={chapter.id}
                                class="flex items-center justify-between gap-4 rounded-xl border border-neutral-content/20 p-2"
                            >
                                <span
                                    class="badge badge-neutral h-8 w-8 cursor-grab rounded-full"
                                >
                                    <Fa icon={faGripLines} />
                                </span>
                                <span class="flex-1">{chapter.title}</span>
                                <a
                                    href="/chapters/{chapter.id}/edit"
                                    class="btn btn-circle btn-ghost btn-sm border border-base-content/20"
                                >
                                    <Fa icon={faPencil} />
                                </a>
                            </li>
                        {/each}
                    </ul>
                </div>
            </div>
        {/each}
    </main>
</form>

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
