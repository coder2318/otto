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
    import { autosize } from '@/service/svelte'

    export let chapter: { data: App.Chapter }

    let enhance: HTMLTextAreaElement
    let original: HTMLTextAreaElement
    let loading: boolean = true
    let compare: boolean = false

    const form = useForm({
        original: chapter.data.content,
        enhanced: '',
        use: null,
        status: chapter.data.status,
    })

    $form.use = 'enhanced'

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
            .put(`/chapters/${chapter.data.id}`, {
                preserveScroll: true,
            })
    }

    onMount(() => {
        if (original) original.disabled = true
        if (enhance) enhance.disabled = true

        fetch(`/chapters/${chapter.data.id}/enhance/stream`, { signal: controller.signal })
            .then((res) => {
                if (res.ok) return res

                throw new Error('Network response was not ok.')
            })
            .then((res) => res.body.pipeThrough(new TextDecoderStream()).getReader())
            .then((reader) =>
                reader.read().then(function pump({ done, value }) {
                    if (controller.signal.aborted) return

                    if (done) {
                        if (original) original.disabled = false
                        if (enhance) enhance.disabled = false
                        if (enhance) enhance.disabled = false
                        loading = false
                        return
                    }

                    $form.enhanced += value

                    if ($form.enhanced) {
                        finish()
                    }

                    return reader.read().then(pump)
                })
            )
            .catch(() => {
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
                <div class="wrap">
                    <div class="form-control transition-all">
                        <textarea
                            class="textarea textarea-bordered textarea-ghost w-full rounded-xl bg-primary/10 font-sans text-2xl text-primary"
                            class:input-error={$form.errors.enhanced}
                            bind:value={$form.enhanced}
                            bind:this={enhance}
                            name="enhanced"
                            rows="10"
                            use:autosize={{ offset: 2 }}
                            placeholder="Type Your Story here..."
                        />
                    </div>

                    {#if compare}
                        <div class="form-control transition-all">
                            <textarea
                                class="textarea textarea-bordered w-full rounded-xl font-sans text-2xl"
                                class:input-error={$form.errors.original}
                                bind:value={$form.original}
                                bind:this={original}
                                name="original"
                                rows="10"
                                use:autosize={{ offset: 2 }}
                                placeholder="Type Your Story here..."
                            />
                        </div>
                    {/if}
                </div>

                <div class="enhance__buttons">
                    <div class="enhance__buttons_col">
                        <a href="/chapters/{chapter.data.id}/edit" class="goBackLink" use:inertia>
                            <img src={goBackLinkIcon} alt="Record" />
                            <span>Record more</span>
                        </a>
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
                                data-redirect="dashboard.chapters.congratulation"
                            >
                                Save & Next
                            </button>
                        {/if}
                        {#if $form.isDirty && $form.use && !loading}
                            <button
                                type="submit"
                                class="otto-btn-svg"
                                data-status="published"
                                data-redirect="dashboard.chapters.finish"
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

        .wrap {
            display: flex;

            // @media (max-width: 991px) {
            //     flex-direction: column;
            // }
        }

        .form-control {
            display: block;
            position: relative;
            width: 100%;
            margin-right: 15px;

            &:last-child {
                margin-right: 0;
                border-left: 1px solid #cfe3f3;
                padding-left: 15px;
            }
            &:first-child {
                border-left: none;
                padding-left: 0;
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
