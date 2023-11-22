<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import { onMount } from 'svelte'
    import { start, done as finish } from '@/components/Loading.svelte'
    import ChapterNameBanner from '@/components/Chapters/ChapterNameBanner.svelte'
    import ChapterTipBanner from '@/components/Chapters/ChapterTipBanner.svelte'
    import enhanceIcon from '@/assets/img/enhance-icon.svg'
    import goBackLinkIcon from '@/assets/img/go-back-link-icon.svg'
    import CompleteBtn from '@/components/SVG/buttons/complete-btn.svg.svelte'
    import TipTap from '@/components/TipTap.svelte'

    export let chapter: { data: App.Chapter }

    let loading: boolean = true
    let compare: boolean = false

    const form = useForm({
        original: chapter.data.content,
        enhanced: null,
        use: 'enhanced',
        status: chapter.data.status,
    })

    $form.enhanced = ''

    start()

    const controller = new AbortController()

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                content: data[data.use],
                edit: null,
                status: event.submitter.dataset?.status ?? data.status,
                redirect: event.submitter.dataset?.redirect ?? null,
            }))
            .put(`/guests/chapters/${chapter.data.id}`, {
                preserveScroll: true,
            })
    }

    onMount(() => {
        fetch(`/guests/chapters/${chapter.data.id}/enhance/stream`, { signal: controller.signal })
            .then((res) => {
                if (res.ok) return res

                throw new Error('Network response was not ok.')
            })
            .then((res) => res.body.pipeThrough(new TextDecoderStream()).getReader())
            .then((reader) =>
                reader.read().then(function pump({ done, value }) {
                    if (controller.signal.aborted) return

                    if (done) {
                        return
                    }

                    $form.enhanced += value

                    if ($form.enhanced) {
                        finish()
                    }

                    return reader.read().then(pump)
                })
            )
            .finally(() => {
                loading = false
                finish()
            })

        return () => {
            controller.abort()
        }
    })
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<ChapterNameBanner title={chapter.data.title} />
<ChapterTipBanner
    title="OttoStory Transcription Tool Tips:"
    tip="This is your transcription. Edit any misspellings in proper nouns, or city names. You can also add more text via typing or rerecord additional information."
/>

<form on:submit|preventDefault={submit} class="flex flex-col" in:fade>
    <section class:lg:grid-cols-2={compare} class="enhance">
        <div class="otto-container">
            <div class="enhance__top">
                <div class="enhance__top_col">
                    <img src={enhanceIcon} alt="icon" />
                    <span>Enhanced with OttoStory</span>
                </div>
                <div class="enhance__top_col">
                    {#if compare}
                        {#if !loading}
                            <button
                                class="otto-btn-outline small font-sans"
                                class:otto-btn-primary={$form.use == 'enhanced'}
                                disabled={$form.use == 'enhanced'}
                                on:click|preventDefault={() => ($form.use = 'enhanced')}>Use OTTO Writing</button
                            >
                        {/if}
                        {#if !loading}
                            <button
                                class="otto-btn-outline small btn-original font-sans"
                                class:otto-btn-primary={$form.use == 'original'}
                                disabled={$form.use == 'original'}
                                on:click|preventDefault={() => ($form.use = 'original')}>Use Original Writing</button
                            >
                        {/if}
                    {/if}
                </div>
            </div>
            <div class="block">
                <div class="flex flex-col items-stretch gap-4 md:flex-row">
                    <TipTap
                        class="textarea textarea-bordered textarea-ghost h-full transition-transform
                            {$form.use == 'enhanced' ? 'scale-[1.01]' : ''}
                            {$form.errors.original ? 'textarea-error' : ''}
                            rounded-xl text-2xl first-letter:font-serif first-letter:text-4xl first-letter:italic first-letter:text-primary"
                        bind:content={$form.enhanced}
                        placeholder="Type Your Story here..."
                    />

                    {#if compare}
                        <TipTap
                            class="textarea textarea-bordered h-full transition-transform
                                {$form.use == 'original' ? 'scale-[1.01]' : ''}
                                {$form.errors.original ? 'textarea-error' : ''}
                                rounded-xl text-2xl first-letter:font-serif first-letter:text-4xl"
                            bind:content={$form.original}
                            placeholder="Type Your Story here..."
                        />
                    {/if}
                </div>

                <div class="enhance__buttons">
                    <div class="enhance__buttons_col">
                        <button
                            disabled={loading || $form.isDirty || $form.processing}
                            use:inertia={{ href: `/guests/chapters/${chapter.data.id}/edit` }}
                            class="goBackLink"
                        >
                            <img src={goBackLinkIcon} alt="Record" />
                            <span>Record more</span>
                        </button>
                    </div>
                    <div class="enhance__buttons_col gap-4">
                        {#if !compare && !loading}
                            <button
                                type="button"
                                class="otto-btn-outline small"
                                on:click={() => {
                                    compare = true
                                    $form.use = null
                                }}
                            >
                                Compare with My Writing
                            </button>
                        {/if}
                        {#if $form.isDirty && $form.use && !loading}
                            <button
                                type="submit"
                                class="otto-btn-secondary small"
                                data-status="draft"
                                data-redirect="guests.chapters.congratulation"
                            >
                                Save & Next
                            </button>
                        {/if}
                        {#if $form.isDirty && $form.use && !loading}
                            <button
                                type="submit"
                                class="otto-btn-svg"
                                data-status="published"
                                data-redirect="guests.chapters.finish"
                            >
                                <CompleteBtn />
                            </button>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>

<style lang="scss">
    .enhance {
        position: relative;
        padding-bottom: 100px;

        &__top {
            background-color: #ffd885;
            padding: 12px 32px 12px 32px;
            border-radius: 24px 24px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;

            .btn-original {
                margin-left: 12px;
            }

            &_col {
                display: flex;
                align-items: center;
            }

            span {
                font-size: 20px;
                font-weight: 700;
                color: #0c345c;
                margin-left: 10px;
            }
        }

        .block {
            background-color: #fff;
            border: 1px solid #cfe3f3;
            border-top: none;
            border-radius: 0 0 24px 24px;
            padding: 24px 32px 32px 32px;

            @media (max-width: 991px) {
                padding: 16px;
            }
        }

        &__buttons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 32px;

            &_col {
                display: flex;
                align-items: center;
            }
        }
    }
</style>
