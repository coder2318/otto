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

    export let story: { data: App.Story }
    export let chapters: { [timeline_id: number]: { data: App.Chapter[] } }
    export let timelines: { data: App.Timeline[] }

    console.log(story, chapters, timelines)
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
            class="btn btn-secondary rounded-full"
        >
            Preview Your Book
            <span class="badge mask badge-neutral mask-circle p-4"
                ><Fa icon={faArrowRight} /></span
            >
        </a>
    </div>
</section>

<main class="container mx-auto mb-16 flex flex-col gap-6 px-4">
    {#each timelines.data as timeline, index (timeline.id)}
        {#if chapters?.[timeline.id]?.data?.length}
            <div class="otto-colapse collapse-arrow collapse bg-base-200">
                <input
                    type="radio"
                    name="timeline-collapse"
                    checked={index == 0}
                />
                <div class="collapse-title text-xl font-medium">
                    {timeline.title}
                </div>
                <div class="collapse-content">
                    <ol class="flex flex-col gap-4">
                        {#each chapters[timeline.id]?.data as chapter, index (chapter.id)}
                            <li
                                class="flex items-center justify-between gap-4 rounded-xl border border-neutral-content/20 p-2"
                            >
                                <Fa
                                    icon={faGripLines}
                                    class="cursor-grab rounded-full bg-neutral p-0.5 text-neutral-content"
                                />
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
        {/if}
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
