<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import qs from 'qs'
    import { fade } from 'svelte/transition'
    import { useForm, inertia, page } from '@inertiajs/svelte'
    import recordBannerIllustration from '@/assets/img/create-otto-story-illustration.svg'
    import bannerIllustration from '@/assets/img/profile-illustration.svg'
    import goBackLinkIcon from '@/assets/img/go-back-link-icon.svg'
    import background from '@/assets/img/chapters-bgi.jpg'
    import timelineArrow from '@/assets/img/timeline-arrow.svg'

    export let story: { data: App.Story }
    export let timelines: { data: App.Timeline[] }
    let dropdownDialog: HTMLDialogElement

    let query = qs.parse($page.url.replace(window.location.pathname, '').slice(1))

    let el: HTMLFormElement
    const form = useForm({
        title: '',
        timeline_id: query.timeline_id ?? null,
    })

    const getRedirect = () =>
        ({
            record: 'dashboard.chapters.record',
            upload: 'dashboard.chapters.upload',
            write: 'dashboard.chapters.write',
        })[query.redirect] ?? null

    function submit() {
        $form.clearErrors()
        $form.transform((data) => ({ ...data, redirect: getRedirect() })).post(`/stories/${story.data.id}/chapters`)
    }

    function selectOption(e) {
        $form.timeline_id = e.target.value
        dropdownDialog.close()
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Create New Chapter</title>
</svelte:head>

<section class="createChapterBanner">
    <div class="otto-container">
        <div class="wrapper">
            <img class="createChapterBanner-figure" src={bannerIllustration} alt="Figure" />
            <div class="block">
                <img class="createChapterBanner-illustration" src={recordBannerIllustration} alt="Illustartion" />
                <h2 class="fz_h3 title">Starting Your OttoStory</h2>
                <p>
                    Chat with Otto and relive a cherished memory. Turn this treasured moment into a brand new chapter.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="chaptersHero relative" in:fade>
    <div class="otto-container">
        <div class="wrap">
            <div
                class="block border-error"
                class:border={$form.errors.timeline_id}
                style="background-image: url({background})"
            >
                <div class="chaptersHero-overlay" />
                <h1 class="fz_h3 title">
                    <span class="font-normal italic text-neutral">Write About Your</span>
                    <button on:click={() => dropdownDialog.showModal()}>
                        <p>
                            <i class="text-neutral"
                                >{timelines.data.find((el) => el.id == $form.timeline_id)?.title ?? 'Timeline'}</i
                            >
                        </p>
                        <img src={timelineArrow} alt="arrow" />
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
                                            class:!bg-secondary={$form.timeline_id == timeline.id}
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
            {#if $form.errors.timeline_id}
                <span class="label-text-alt mt-1 text-left text-error">
                    {$form.errors.timeline_id}
                </span>
            {/if}
        </div>
    </div>
</section>

<form on:submit|preventDefault={submit} bind:this={el} in:fade>
    <section class="createChapter">
        <div class="otto-container">
            <div class="block">
                <div class="form-control">
                    <textarea
                        class="font-serif"
                        class:input-error={$form.errors.title}
                        bind:value={$form.title}
                        name="title"
                        rows="10"
                        placeholder="Type Your Story here..."
                    />
                    {#if $form.errors.title}
                        <span class="label-text-alt mt-1 text-left text-error">
                            {$form.errors.title}
                        </span>
                    {/if}
                </div>
                <div class="createChapter__buttons">
                    <a href="/stories/{story.data.id}/chapters" class="goBackLink" use:inertia>
                        <img src={goBackLinkIcon} alt="Record" />
                        <span>Go Back</span>
                    </a>

                    {#if $form.isDirty}
                        <button type="submit" class="otto-btn-secondary small" disabled={$form.processing}>
                            {#if $form.processing}
                                <span class="loading loading-spinner" />
                            {/if}
                            Save
                        </button>
                    {/if}
                </div>
            </div>
        </div>
    </section>
</form>

<style lang="scss">
    .createChapterBanner {
        margin: 20px 0;
        .wrapper {
            position: relative;
        }
        .block {
            padding: 32px 50px;
            background: #ffbe33;
            border-radius: 24px;
            overflow: hidden;
            position: relative;

            @media (max-width: 767px) {
                padding: 24px 24px;
            }
        }
        &-figure {
            position: absolute;
            width: 400px;
            height: auto;
            top: -120px;
            left: -100px;
        }

        &-illustration {
            position: absolute;
            left: 0;
            top: 0;
        }

        .title {
            margin-bottom: 24px;
            color: #0c345c;

            @media (max-width: 767px) {
                margin-bottom: 16px;
            }
        }

        p {
            font-size: 24px;
            position: relative;
            z-index: 2;
            color: #06192d;

            @media (max-width: 767px) {
                font-size: 18px;
            }
        }
    }

    .createChapter {
        padding-top: 20px;
        padding-bottom: 100px;
        width: 100%;

        .block {
            background: #fff;
            padding: 24px 32px 32px 32px;
            border-radius: 24px;
            width: 100%;

            @media (max-width: 767px) {
                padding: 24px;
            }
        }

        textarea {
            border-radius: 24px !important;
            border: 1px solid #bfbfbf;
            background: #fff9ed;
            color: #808080;
            font-size: 28px;
            padding: 20px 24px 24px 24px;
            line-height: 1.3;
            min-height: 514px;
            font-style: normal;

            @media (max-width: 767px) {
                min-height: 340px;
                font-size: 22px;
            }
        }

        &__buttons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
        }
    }

    .chaptersHero {
        padding-top: 0;

        .block {
            min-height: auto;
            padding-top: 20px;
            padding-bottom: 20px;
        }
    }
</style>
