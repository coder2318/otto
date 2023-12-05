<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import AudioRecorder from '@/components/AudioRecorder.svelte'
    import { start, done } from '@/components/Loading.svelte'
    import { onMount } from 'svelte'
    import goBackLinkIcon from '@/assets/img/go-back-link-icon.svg'
    import ChapterTipBanner from '@/components/Chapters/ChapterTipBanner.svelte'
    import ChapterNameBanner from '@/components/Chapters/ChapterNameBanner.svelte'

    export let chapter: { data: App.Chapter }

    let carousel: HTMLDivElement

    const form = useForm({
        title: chapter.data.title,
        attachments: null,
        status: chapter.data.status,
    })

    function transcribe() {
        $form
            .transform((data) => ({
                _method: 'PUT',
                ...data,
                status: 'draft',
                redirect: 'dashboard.chapters.write',
            }))
            .post(`/chapters/${chapter.data.id}`, {
                forceFormData: true,
                onStart: start,
                onFinish: done,
            })
    }

    onMount(() => {
        if (!carousel) return

        let timer = 0
        const interval = setInterval(() => {
            timer += 500

            if (timer > 10000) {
                timer = 0
                carousel.scrollLeft =
                    carousel.scrollLeft >= carousel.scrollWidth - carousel.offsetWidth
                        ? 0
                        : carousel.scrollLeft + carousel.offsetWidth
            }
        }, 500)

        return () => clearInterval(interval)
    })
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<ChapterNameBanner title={$form.title} />

<ChapterTipBanner title="OttoStory recording tip:" questions={chapter.data.question.sub_questions} />

<form in:fade>
    <section class="record">
        <div class="otto-container">
            <div class="wrap" class:withoutSlider={!chapter.data?.question?.covers?.length}>
                <div class="col">
                    <div class="recordAudio">
                        <AudioRecorder
                            min={1000 * 60}
                            max={1000 * 60 * 10}
                            maxFiles={1}
                            onStop={transcribe}
                            bind:recordings={$form.attachments}
                        />
                    </div>
                </div>
                {#if chapter.data?.question?.covers?.length}
                    <div class="col">
                        <div class="recordImage blockForImage">
                            <div class="carousel h-full overflow-hidden" bind:this={carousel}>
                                {#each chapter.data?.question?.covers as cover}
                                    <div
                                        class="carousel-item flex h-full w-full flex-wrap content-center justify-center text-center text-xl"
                                    >
                                        <img src={cover} alt="Cover" />
                                    </div>
                                {/each}
                            </div>
                        </div>
                    </div>
                {/if}
            </div>

            <div class="record__buttons">
                <a href="/chapters/{chapter.data.id}/edit" class="goBackLink" use:inertia>
                    <img src={goBackLinkIcon} alt="Record" />
                    <span>Go Back</span>
                </a>
            </div>
        </div>
    </section>
</form>

<style lang="scss">
    .record {
        position: relative;
        padding-bottom: 100px;

        .wrap {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-right: -30px;
            margin-bottom: 20px;
        }

        .withoutSlider {
            .col {
                flex-basis: calc(100%);
            }
        }

        .col {
            flex-basis: calc(100% / 2 - 30px);
            margin-right: 30px;

            @media (max-width: 991px) {
                flex-basis: calc(100%);
                margin-bottom: 30px;

                &:last-child {
                    order: -1;
                }
            }
        }

        .recordAudio {
            background: #fff;
            padding: 48px 24px 24px 24px;
            border-radius: 24px;
            min-height: 520px;
        }

        .recordImage {
            width: 100%;
            height: 100%;
            border-radius: 24px;
            background-color: #fff;

            .carousel {
                width: 100%;
            }

            .carousel-item {
                position: relative;
                width: 100%;
            }

            @media (max-width: 991px) {
                aspect-ratio: 16/12;
            }
        }

        &__buttons {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    }
</style>
