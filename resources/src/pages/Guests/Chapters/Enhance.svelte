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
    import EnhanceBtn from '@/components/SVG/buttons/enhance-btn.svg.svelte'

    export let chapter: { data: App.Chapter }
    export let prompts: { data: App.Prompt[] }

    $: tonePrompts = prompts.data.filter((prompt) => !prompt.perspective)
    $: perspectivePrompts = prompts.data.filter((prompt) => prompt.perspective)
    $: currentTone = tonePrompts.find((p) => p.id === enhanceData.tone_id)
    $: currentPerspective = perspectivePrompts.find((p) => p.id === enhanceData.perspective_id)

    let enhance: HTMLDialogElement
    let loading: boolean = true
    let compare: boolean = false

    const enhanceData = {
        tone_id: null,
        perspective_id: null,
    }

    const form = useForm({
        original: chapter.data.content,
        enhanced: null,
        use: 'enhanced',
        status: chapter.data.status,
    })

    $form.enhanced = ''

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

    function proccess() {
        enhance?.close()

        start()

        const data = { ...enhanceData }

        for (const [key, value] of Object.entries(data)) {
            if (value === null) {
                delete data[key]
            }
        }

        fetch(`/guests/chapters/${chapter.data.id}/enhance/stream?${new URLSearchParams(data)}`, {
            signal: controller.signal,
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json, text/plain',
            },
        })
            .then((res) => {
                if (res.ok) return res.body.pipeThrough(new TextDecoderStream()).getReader()

                throw new Error('Network response was not ok.')
            })
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
    }

    onMount(() => {
        enhance.showModal()

        return () => {
            controller?.abort()
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

<dialog bind:this={enhance} class="modal">
    <form method="dialog" class="modal-box flex w-11/12 max-w-5xl flex-col gap-4 bg-neutral text-neutral-content">
        <h3 class="text-2xl font-bold text-primary">How to use OttoStory AI</h3>
        <div class="alert border-0 bg-secondary/60 text-primary">
            We are excited you want to use our Ottostory AI. Please note this is a new feature that will take your
            writing to the next level. In order to have the best experience – choose a tone, language, and writing
            perspective below.
        </div>
        {#if tonePrompts.length > 0}
            <div class="form-control gap-2">
                <label class="label" for="tone">
                    <span class="label-text">Change Tone</span>
                </label>
                <div class="grid grid-cols-3 gap-8">
                    <div class="join col-span-1 items-center md:col-span-1">
                        {#if enhanceData.tone_id}
                            <div
                                class="join-item flex h-full items-center justify-center border border-r-0 px-2 text-primary"
                            >
                                {@html currentTone?.icon}
                            </div>
                        {/if}
                        <select
                            bind:value={enhanceData.tone_id}
                            class="select join-item select-bordered select-ghost w-full"
                        >
                            <option disabled value={null}>Select Tone</option>
                            {#each tonePrompts as tone}
                                <option value={tone.id}>{tone.title}</option>
                            {/each}
                        </select>
                    </div>
                    {#if enhanceData.tone_id}
                        <div class="col-span-2 font-serif italic text-primary">
                            {currentTone?.description}
                        </div>
                    {/if}
                </div>
            </div>
        {/if}

        {#if perspectivePrompts.length > 0}
            <div class="form-control gap-2">
                <label class="label" for="tone">
                    <span class="label-text">From the Perspective of</span>
                </label>
                <div class="grid grid-cols-3 gap-8">
                    <div class="join col-span-1 flex items-center md:col-span-1">
                        {#if enhanceData.perspective_id}
                            <div
                                class="join-item flex h-full items-center justify-center border border-r-0 px-2 text-primary"
                            >
                                {@html currentPerspective?.icon}
                            </div>
                        {/if}
                        <select
                            bind:value={enhanceData.perspective_id}
                            class="select join-item select-bordered select-ghost w-full"
                        >
                            <option disabled value={null}>Select Perspective</option>
                            {#each perspectivePrompts as perspective}
                                <option value={perspective.id}>{perspective.title}</option>
                            {/each}
                        </select>
                    </div>
                    {#if enhanceData.perspective_id}
                        <div class="col-span-2 font-serif italic text-primary">
                            {currentPerspective?.description}
                        </div>
                    {/if}
                </div>
            </div>
        {/if}

        <div class="alert border-0 bg-secondary/60 text-primary">
            Once you are satisfied with your selections - click the Ottostory button. On the next page – you will have a
            chance to further edit your text and compare it to the original.
        </div>

        <div class="modal-action justify-between">
            <a href="/guests/chapters/{chapter.data.id}/write" class="goBackLink" use:inertia>
                <img src={goBackLinkIcon} alt="Record" />
                <span>Back</span>
            </a>
            <span role="button" tabindex="0" on:click={proccess} on:keypress={(e) => e.key === 'Enter' && proccess()}>
                <EnhanceBtn />
            </span>
        </div>
    </form>
</dialog>

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
