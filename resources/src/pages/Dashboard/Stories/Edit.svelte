<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import BtnArrow from '@/components/SVG/btn-arrow.svg.svelte'
    import Grab from '@/components/SVG/grab.svg.svelte'
    import TableArrow from '@/components/SVG/table-contents-arrow.svg.svelte'
    import splash4 from '@/assets/img/splash-4.svg'
    import pencil from '@/assets/img/pencil.svg'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import { onMount } from 'svelte'
    import Sortable from 'sortablejs'

    export let story: { data: App.Story }
    export let chapters: { [timeline_id: number]: { data: App.Chapter[] } }
    export let timelines: { data: App.Timeline[] }

    const form = useForm({
        timelines: timelines.data.map((timeline) => ({
            id: timeline.id,
            chapters: chapters[timeline.id]?.data.map((chapter) => chapter.id) ?? [],
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
                        const from = $form.timelines.find((timeline) => timeline.id == event.from.dataset.timeline)
                        const to = $form.timelines.find((timeline) => timeline.id == event.to.dataset.timeline)
                        to.chapters.splice(newIndex, 0, ...from.chapters.splice(oldIndex, 1))

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
        $form.post(`/stories/${story.data.id}/contents`, {
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

<section in:fade>
    <Breadcrumbs step={1} {story} />
</section>

<form on:submit|preventDefault={submit}>
    <section class="editBookHeader">
        <div class="otto-container">
            <div class="wrap">
                <img src={splash4} class="editBookHeader-illustration" alt="" />
                <h1 class="fz_h2 title text-primary">
                    Table of <i>Contents </i>
                </h1>

                {#if $form.isDirty}
                    <button type="submit" class="otto-btn-primary" disabled={$form.processing}>
                        {#if $form.processing}<span class="loading loading-spinner"></span>{/if}
                        Save Chapter Order
                    </button>
                {:else}
                    <a href="/stories/{story.data.id}/preview" use:inertia class="otto-btn-with-arrow">
                        <p>Preview Your Book</p>
                        <span class="icon">
                            <BtnArrow />
                        </span>
                    </a>
                {/if}
            </div>
        </div>
    </section>

    <section class="tableContents">
        <div class="otto-container">
            <div class="wrap">
                {#each timelines.data as timeline (timeline.id)}
                    <div class="otto-colapse collapse collapse-arrow bg-base-200">
                        <input type="checkbox" />

                        <div class="collapse-title">
                            <span>{timeline.title}</span>
                            <div class="collapse-arrow">
                                <TableArrow />
                            </div>
                        </div>

                        <div class="collapse-content">
                            <ul bind:this={lists[timeline.id]} data-timeline={timeline.id}>
                                {#each chapters[timeline.id]?.data ?? [] as chapter (chapter.id)}
                                    <li data-chapter={chapter.id}>
                                        <div class="collapse-content-left">
                                            <div class="collapse-content-icon cursor-grab">
                                                <Grab />
                                            </div>
                                            <span class="collapse-content-title font-serif">{chapter.title}</span>
                                        </div>

                                        <a href="/chapters/{chapter.id}/edit" class="collapse-content-pencil">
                                            <img src={pencil} alt="Pencil" />
                                        </a>
                                    </li>
                                {/each}
                            </ul>
                        </div>
                    </div>
                {/each}
            </div>
        </div>
    </section>
</form>

<style lang="scss">
    .otto-colapse {
        @apply border border-primary text-primary transition-colors duration-300;

        .collapse-title {
            @apply font-serif text-2xl;
        }

        .collapse-content {
            @apply bg-base-200 text-base-content;
        }

        &:has(input:checked) {
            @apply bg-primary text-primary-content;

            .collapse-content {
                @apply pt-4;
            }
        }
    }

    .editBookHeader {
        padding-bottom: 50px;

        &-illustration {
            position: absolute;
            width: auto;
            height: 123px;
            left: -83px;
            top: -2px;
        }

        .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .title,
        button,
        a {
            position: relative;
            z-index: 3;
        }

        .otto-btn-primary {
            height: 54px;
            font-size: 18px;
            padding: 0 24px;
        }
    }

    .tableContents {
        padding-bottom: 100px;

        .collapse[open].collapse-arrow > .collapse-title .collapse-arrow,
        .collapse-open.collapse-arrow > .collapse-title .collapse-arrow,
        .collapse-arrow:focus:not(.collapse-close) > .collapse-title .collapse-arrow,
        .collapse-arrow:not(.collapse-close) > input[type='checkbox']:checked ~ .collapse-title .collapse-arrow,
        .collapse-arrow:not(.collapse-close) > input[type='radio']:checked ~ .collapse-title .collapse-arrow {
            transform: rotate(180deg);

            :global(svg rect) {
                fill: #fff;
            }
            :global(svg g path) {
                stroke: #0c345c;
            }
        }

        .otto-colapse {
            margin-bottom: 20px;
            background: transparent;
            border: 1px solid #ccc;

            &:last-child {
                margin-bottom: 0;
            }
        }
        .collapse-title {
            font-size: 44px;
            padding: 24px;
            font-weight: 400;
            line-height: 1.1;
            display: flex;
            align-items: center;
            justify-content: space-between;

            &:after {
                display: none;
            }
        }
        .collapse-content {
            background-color: #eae4dc;
            padding: 0 !important;

            &-left {
                display: flex;
                align-items: center;
            }

            &-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 32px;
                height: 32px;
                border-radius: 100%;
                background-color: #fff;
                margin-right: 16px;
                cursor: pointer;
            }

            &-pencil {
                min-width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 100%;
                border: 1px solid #80694d;
                transition: 0.3s;

                &:hover {
                    background: #fff;
                    border: 1px solid #fff;
                }
            }

            &-title {
                font-size: 26px;
                line-height: 1.2;
                color: #333333;
                margin-right: 10px;
            }

            ul {
                padding: 24px;
                li {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 24px 16px;
                    border: 1px solid #c6b59f;
                    border-radius: 16px;
                    margin-bottom: 20px;
                    background-color: #eae4dc;

                    &:last-child {
                        margin-bottom: 0;
                    }
                }
            }
        }
    }

    @media (max-width: 767px) {
        .editBookHeader {
            .wrap {
                flex-direction: column;
                align-items: flex-start;
            }
            .title {
                margin-bottom: 20px;
            }
        }

        .tableContents {
            .collapse-title {
                font-size: 30px;
            }

            .collapse-content {
                &-title {
                    font-size: 20px;
                }

                &-pencil {
                    min-width: 32px;
                    height: 32px;

                    img {
                        width: 18px;
                    }
                }
            }
        }
    }
</style>
