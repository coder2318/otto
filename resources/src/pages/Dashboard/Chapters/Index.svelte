<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import qs from 'qs'
    import { writable } from 'svelte/store'
    import { blur, fade } from 'svelte/transition'
    import { inertia, page, router } from '@inertiajs/svelte'
    import InviteGuestModal from '@/components/Chapters/InviteGuestModal.svelte'
    import background from '@/assets/img/chapters-bgi.jpg'
    import { faClose, faArrowDownLong, faTrash } from '@fortawesome/free-solid-svg-icons'
    import CreateStory from '@/components/Stories/CreateStory.svelte'
    import Fa from 'svelte-fa'
    import { onMount } from 'svelte'
    import smallBannerIllustration from '@/assets/img/profile-illustration.svg'
    import axios from 'axios'

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

    let questionsChapters = [...questions_chapters.data]
    let next = questions_chapters.links?.next
    let loading: boolean = false

    $: query = qs.parse($page.url.split('?').slice(1).join('?'))

    const filter = writable(qs.parse($page.url.split('?').slice(1).join('?'))?.filter || {})

    filter.subscribe((value) => {
        if (import.meta.env.SSR) return

        router.visit(window.location.pathname + '?' + qs.stringify({ filter: value }), {
            only: ['questions_chapters'],
            onSuccess() {
                questionsChapters = [...questions_chapters.data]
                next = questions_chapters.links?.next
            },
        })
    })

    function removeFilter(key: string) {
        filter.update((value) => {
            delete value[key]
            return value
        })
    }

    function loadMoreChapters() {
        if (!next || loading || window.innerHeight + Math.round(window.scrollY) < document.body.offsetHeight) {
            return
        }

        loading = true

        axios
            .get(next)
            .finally(() => (loading = false))
            .then(({ data }) => {
                questionsChapters = [...questionsChapters, ...data.data]
                next = data.links?.next
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
        if ($filter.timeline_id == e.target.value) {
            removeFilter('timeline_id')
        } else {
            $filter.timeline_id = e.target.value
        }
        dropdownDialog.close()
    }

    onMount(() => {
        if (Object.keys($filter).length === 0) {
            const storageFilters = JSON.parse(localStorage.getItem('chapters-filter') ?? '{}')

            if (storageFilters && Object.keys(storageFilters).length) {
                $filter = JSON.parse(localStorage.getItem('chapters-filter'))
            }
        }

        window.addEventListener('scroll', loadMoreChapters)
        window.addEventListener('beforeunload', updateFilter)

        function updateFilter() {
            $filter && Object.keys($filter).length === 0
                ? localStorage.removeItem('chapters-filter')
                : localStorage.setItem('chapters-filter', JSON.stringify($filter))
        }

        return () => {
            updateFilter()
            window.removeEventListener('scroll', loadMoreChapters)
            window.removeEventListener('beforeunload', updateFilter)
        }
    })
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - My Stories</title>
</svelte:head>

<section class="chaptersHero relative" in:fade>
    <div class="otto-container">
        <div class="wrap">
            <img class="chaptersHero-illustration" src={smallBannerIllustration} alt="Figure" />
            <div class="block" style="background-image: url({background})">
                <div class="chaptersHero-overlay" />
                <h1 class="fz_h3 title">
                    <span class="font-normal italic text-neutral">Write About Your</span>
                    <button on:click={() => dropdownDialog.showModal()}>
                        <p>
                            <i class="text-neutral"
                                >{timelines.data.find((el) => el.id == query?.filter?.timeline_id)?.title ??
                                    'Timeline'}</i
                            >
                        </p>
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
                                            class:!bg-secondary={query?.filter?.timeline_id == timeline.id}
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
                    <span class="font-normal text-neutral">With These Questions</span>
                </h1>
            </div>
        </div>
    </div>
</section>

<CreateStory {story} />

<section class="chaptersTabs" in:fade>
    <div class="otto-container">
        <div class="tabs">
            <button
                class="tab -mb-0.5"
                class:tab-active={query?.filter?.status == undefined}
                class:tab-bordered={query?.filter?.status == undefined}
                class:text-primary={query?.filter?.status == undefined}
                class:!border-primary={query?.filter?.status == undefined}
                on:click|preventDefault={() => removeFilter('status')}
            >
                All
            </button>
            <button
                class="tab -mb-0.5"
                class:tab-active={query?.filter?.status == 'undone'}
                class:tab-bordered={query?.filter?.status == 'undone'}
                class:text-primary={query?.filter?.status == 'undone'}
                class:!border-primary={query?.filter?.status == 'undone'}
                on:click|preventDefault={() => ($filter.status = 'undone')}
            >
                Undone
            </button>
            <button
                class="tab -mb-0.5"
                class:tab-active={query?.filter?.status == 'draft'}
                class:tab-bordered={query?.filter?.status == 'draft'}
                class:text-primary={query?.filter?.status == 'draft'}
                class:!border-primary={query?.filter?.status == 'draft'}
                on:click|preventDefault={() => ($filter.status = 'draft')}
            >
                In Progress
            </button>
            <button
                class="tab -mb-0.5"
                class:tab-active={query?.filter?.status == 'published'}
                class:tab-bordered={query?.filter?.status == 'published'}
                class:text-primary={query?.filter?.status == 'published'}
                class:!border-primary={query?.filter?.status == 'published'}
                on:click|preventDefault={() => ($filter.status = 'published')}
            >
                Completed
            </button>
        </div>
    </div>
</section>

<section class="chapters" in:fade>
    <div class="otto-container">
        <div class="wrap">
            {#each questionsChapters as chapter (chapter.type + chapter.id)}
                <a
                    class="chapterCard"
                    href={chapter.type === 'question'
                        ? `/stories/${story.data.id}/questions/${chapter.id}/chapters/create`
                        : `/chapters/${chapter.id}/edit`}
                    use:inertia
                    in:fade
                >
                    <div class="chapterCard__img">
                        <img
                            src={chapter.cover ??
                                `https://random.imagecdn.app/v1/image?width=800&height=600&category=story&format=image&key=${
                                    chapter.type + chapter.id
                                }`}
                            alt={chapter.title}
                        />
                    </div>

                    <div class="chapterCard__content">
                        <h2 class="chapterCard-title">
                            {chapter.context ?? ''} <i>{chapter.title}</i>
                        </h2>

                        <div class="card-actions justify-between">
                            <div
                                class="chapterCard-badge"
                                class:badge-published={chapter.status === 'published'}
                                class:badge-progress={chapter.status === 'draft'}
                            >
                                {chapter.status}
                            </div>
                            <div>
                                {#if chapter.status === 'undone' && !chapter.guest_id}
                                    <div class="card-actions">
                                        <button
                                            class="btn btn-secondary btn-sm"
                                            on:click|preventDefault={() => modal.invite(chapter)}
                                        >
                                            Invite Guest
                                        </button>
                                    </div>
                                {/if}
                                <!-- {#if chapter.type === 'chapter'}
                                    Started: {dayjs(chapter.created_at).format('MMM DD, YYYY')}
                                {/if} -->
                            </div>
                        </div>
                    </div>
                    {#if chapter.type === 'chapter'}
                        <div class="absolute right-4 top-4">
                            <button
                                class="btn-trash btn btn-circle btn-error btn-outline btn-sm"
                                on:click|preventDefault={() => deleteChapter(chapter.id)}
                            >
                                <Fa icon={faTrash} />
                            </button>
                        </div>
                    {/if}
                </a>
            {/each}
        </div>

        {#if loading}
            <div class="flex items-center justify-center pb-16" transition:blur>
                <span class="loading loading-spinner loading-lg"></span>
            </div>
        {/if}

        <dialog bind:this={dialog} class="modal">
            <form method="dialog" class="modal-backdrop">
                <button />
            </form>
            <form method="dialog" class="modal-box">
                <div class="flex justify-end">
                    <button class="btn btn-circle btn-sm bg-white" on:click={() => dialog.close()}>
                        <Fa icon={faClose} />
                    </button>
                </div>
                <h3 class="text-center text-[30px] text-xl font-normal leading-[33px]">
                    Are you sure <i>want to delete this chapter?</i>
                </h3>
                <div class="modal-action mt-12 flex justify-around">
                    <button
                        class="btn btn-primary btn-sm w-[150px] rounded-full"
                        on:click|preventDefault={confirmDelete}>Yes</button
                    >
                    <button class="btn btn-sm w-[150px] rounded-full py-1" on:click={() => dialog.close()}> No </button>
                </div>
            </form>
        </dialog>
    </div>
</section>

<InviteGuestModal story_id={story.data.id} bind:this={modal} />

<style lang="scss">
    .chaptersTabs {
        position: relative;
        padding-bottom: 32px;
        .tabs {
            .tab {
                font-size: 20px;
                color: #808080;
                padding: 16px 24px;
                height: auto;
                line-height: 1;
                transition: 0.3s;
                margin-right: 8px;

                &:focus {
                    outline: none;
                    box-shadow: none;
                }

                &:hover {
                    color: #0c345c;
                }

                &.tab-active {
                    color: #0c345c;
                }
            }
        }
    }

    .chapters {
        .wrap {
            display: flex;
            flex-wrap: wrap;
            margin-right: -24px;
            margin-bottom: 50px;
        }
    }

    .chapterCard {
        background-color: rgba(255, 255, 255, 0.6);
        flex-basis: calc(100% / 3 - 24px);
        padding: 24px;
        border-radius: 24px;
        margin-right: 24px;
        margin-bottom: 24px;
        transition: 0.3s;
        display: flex;
        flex-direction: column;

        &-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 40px;
            padding: 5px 10px;
            background-color: #f16041;
            color: #fff;
            font-size: 20px;
            line-height: 1;
            font-weight: 300;

            &.badge-published {
                background-color: #8ebff0;
            }
            &.badge-progress {
                background-color: #1d80e2;
            }
        }

        &-title {
            font-size: 24px;
            line-height: 1.3;
            margin-bottom: 32px;
            color: #4d4d4d;

            i {
                color: #1a1a1a;
            }
        }

        .btn-trash {
            transition: 0.3s;
        }

        &__img {
            position: relative;
            min-height: 180px;
            overflow: hidden;
            border-radius: 14px;
            margin-bottom: 16px;

            img {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }
        }

        &__content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        &:hover {
            transform: scale(1.05);

            .btn-trash {
                opacity: 1;
            }
        }
    }

    @media (max-width: 991px) {
        .chapterCard {
            flex-basis: calc(100% / 2 - 24px);
        }
    }
    @media (max-width: 767px) {
        .chaptersTabs {
            .tabs {
                .tab {
                    font-size: 16px;
                    margin-right: 0;
                    padding: 12px 12px;
                }
            }
        }

        .chapterCard {
            flex-basis: calc(100%);
        }
    }
</style>
