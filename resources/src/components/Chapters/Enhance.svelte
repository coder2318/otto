<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import { onMount } from 'svelte'
    import { start, done as finish } from '@/components/Loading.svelte'
    import ChapterNameBanner from '@/components/Chapters/ChapterNameBanner.svelte'
    import ChapterTipBanner from '@/components/Chapters/ChapterTipBanner.svelte'
    import enhanceIcon from '@/assets/img/enhance-icon.svg'
    import goBackLinkIcon from '@/assets/img/go-back-link-icon.svg'
    import TipTap from '@/components/TipTap.svelte'
    import EnhanceBtn from '@/components/SVG/buttons/enhance-btn.svg.svelte'
    import axios from 'axios'
    import { flash } from '@/components/Toast.svelte'
    import Select from 'svelte-select'

    export let type: string = 'guests'
    export let routePrefix: string = '/guests/chapters'

    export let chapter: { data: App.Chapter }
    export let prompts: { data: App.Prompt[] }

    $: tonePrompts = prompts.data.filter((prompt) => !prompt.perspective)
    $: perspectivePrompts = prompts.data.filter((prompt) => prompt.perspective)
    $: currentTone = tonePrompts.find((p) => p.id === enhanceData.tone_id)
    $: currentPerspective = perspectivePrompts.find((p) => p.id === enhanceData.perspective_id)

    let enhance: HTMLDialogElement
    let loading: boolean = true
    let compare: boolean = false

    $: tonePromptsItems = tonePrompts.map((tonePrompt) => ({
        value: tonePrompt.id,
        label: tonePrompt.title,
        icon: tonePrompt.icon,
    }))
    $: perspectivePromptsItems = perspectivePrompts.map((perspectivePrompt) => ({
        value: perspectivePrompt.id,
        label: perspectivePrompt.title,
        icon: perspectivePrompt.icon,
    }))

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

    let toneError = ''
    let perspectiveError = ''

    function validateForm() {
        toneError = ''
        perspectiveError = ''

        if (!enhanceData.tone_id) {
            toneError = 'Please select a tone.'
        }

        if (!enhanceData.perspective_id) {
            perspectiveError = 'Please select a perspective.'
        }

        return !toneError && !perspectiveError
    }

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                content: data[data.use],
                edit: null,
                status: event.submitter.dataset?.status ?? data.status,
                redirect: event.submitter.dataset?.redirect ?? null,
            }))
            .put(`${routePrefix}/${chapter.data.id}`, {
                preserveScroll: true,
            })
    }

    function proccess() {
        if (!validateForm()) {
            return
        }

        enhance?.close()

        start()

        const data = { ...enhanceData }

        for (const [key, value] of Object.entries(data)) {
            if (value === null) {
                delete data[key]
            }
        }

        fetch(`${routePrefix}/${chapter.data.id}/enhance/stream?${new URLSearchParams(data)}`, {
            signal: controller.signal,
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json, text/plain',
            },
        })
            .then((res) => {
                if (res.ok) {
                    return res.body.pipeThrough(new TextDecoderStream()).getReader()
                }

                $form.enhanced = $form.original

                flash({
                    message: 'OttoStory AI not available',
                    type: 'alert-error',
                    autohide: true,
                })

                throw new Error('Network response was not ok.')
            })
            .then((reader) => {
                $form.enhanced = ''
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
            })
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

    function uploadImage(event) {
        if (event.detail.image === undefined) {
            axios
                .post(
                    `${routePrefix}/${chapter.data.id}/image`,
                    {
                        images: [
                            {
                                file: event.detail.files[0],
                                caption: prompt('Please enter image caption'),
                                url: URL.createObjectURL(event.detail.files[0]),
                            },
                        ],
                    },
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    }
                )
                .then((response) => {
                    event.detail.callback(response)
                })
                .catch(function (error) {
                    event.detail.callback(error)
                })
        } else {
            axios
                .post(`${routePrefix}/${chapter.data.id}/image`, {
                    image: {
                        id: event.detail.id,
                        url: event.detail.image,
                        caption: event.detail.caption,
                    },
                })
                .then((response) => {
                    event.detail.callback(response)
                })
                .catch(function (error) {
                    event.detail.callback(error)
                })
        }
    }
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
                    <div
                        class="form-control w-full gap-2 {$form.errors.content
                            ? 'textarea-error'
                            : ''} textarea textarea-bordered textarea-ghost rounded-xl"
                    >
                        <TipTap
                            class="textarea  textarea-ghost h-full transition-transform
                                {compare && $form.use == 'enhanced' ? 'scale-[1.004]' : ''}
                                {$form.errors.original ? 'textarea-error' : ''}
                                rounded-xl text-2xl first-letter:font-serif first-letter:text-4xl first-letter:italic first-letter:text-primary"
                            bind:content={$form.enhanced}
                            bind:images={chapter.data.images}
                            placeholder="Type Your Story here..."
                            contentType="html"
                            on:uploadImage={uploadImage}
                        />
                    </div>
                    {#if compare}
                        <div
                            class="form-control w-full gap-2 {$form.errors.content
                                ? 'textarea-error'
                                : ''} textarea textarea-bordered textarea-ghost rounded-xl"
                        >
                            <TipTap
                                class="textarea  h-full transition-transform
                                {compare && $form.use == 'original' ? 'scale-[1.004]' : ''}
                                {$form.errors.original ? 'textarea-error' : ''}
                                rounded-xl text-2xl first-letter:font-serif first-letter:text-4xl"
                                bind:content={$form.original}
                                bind:images={chapter.data.images}
                                placeholder="Type Your Story here..."
                                contentType="html"
                                on:uploadImage={uploadImage}
                            />
                        </div>
                    {/if}
                </div>

                <div class="mt-4 flex flex-col items-stretch justify-end gap-2 md:flex-row md:items-baseline">
                    {#if !compare && !loading}
                        <button
                            type="button"
                            class="btn btn-primary btn-outline rounded-full"
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
                            class="btn btn-secondary rounded-full"
                            data-status="draft"
                            data-redirect="{type}.chapters.congratulation"
                        >
                            {type == 'guests' ? 'Save & Next' : 'Save to drafts'}
                        </button>
                    {/if}
                    {#if $form.isDirty && $form.use && !loading}
                        <button
                            type="submit"
                            class="btn btn-secondary btn-lg rounded-full text-primary"
                            data-status="published"
                            data-redirect="{type}.chapters.finish"
                        >
                            Complete chapter
                        </button>
                    {/if}
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
                <div class="grid grid-cols-1 items-center gap-8 md:grid-cols-3">
                    <div class="join col-span-1 items-center md:col-span-1">
                        {#if enhanceData.tone_id && currentTone?.icon}
                            <div
                                class="icon join-item flex h-full items-center justify-center border border-r-0 px-2 text-primary"
                            >
                                {@html currentTone?.icon}
                            </div>
                        {/if}
                        <Select
                            items={tonePromptsItems}
                            on:change={(event) => {
                                enhanceData.tone_id = event.detail.value
                                validateForm()
                            }}
                            placeholder="Select Tone"
                            clearable={false}
                            searchable={false}
                            showChevron
                            required
                        >
                            <div class="flex w-full items-center gap-2" slot="item" let:item>
                                {@html item.icon}
                                {item.label}
                            </div>
                        </Select>
                    </div>
                    {#if toneError}
                        <div class="text-sm text-error">{toneError}</div>
                    {/if}
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
                <div class="grid grid-cols-1 items-center gap-8 md:grid-cols-3">
                    <div class="join col-span-1 flex items-center md:col-span-1">
                        {#if enhanceData.perspective_id && currentPerspective?.icon}
                            <div
                                class="icon join-item flex h-full items-center justify-center border border-r-0 px-2 text-primary"
                            >
                                {@html currentPerspective?.icon}
                            </div>
                        {/if}
                        <Select
                            items={perspectivePromptsItems}
                            on:change={(event) => {
                                enhanceData.perspective_id = event.detail.value
                                validateForm()
                            }}
                            placeholder="Select Perspective"
                            clearable={false}
                            searchable={false}
                            showChevron
                            required
                        >
                            <div class="flex w-full items-center gap-2" slot="item" let:item>
                                {@html item.icon}
                                {item.label}
                            </div>
                        </Select>
                    </div>
                    {#if perspectiveError}
                        <div class="text-sm text-error">{perspectiveError}</div>
                    {/if}
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
            <a href="{routePrefix}/{chapter.data.id}/write" class="goBackLink" use:inertia>
                <img src={goBackLinkIcon} alt="Record" />
                <span>Back</span>
            </a>
            <span role="button" tabindex="0" on:click={proccess} on:keypress={(e) => e.key === 'Enter' && proccess()}>
                <EnhanceBtn class="w-[208px]" />
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
    }
    .icon {
        min-width: 53px;
        height: 42px;
    }
</style>
