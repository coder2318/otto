<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm, router } from '@inertiajs/svelte'
    import { onMount } from 'svelte'
    import ChapterNameBanner from '@/components/Chapters/ChapterNameBanner.svelte'
    import ChapterTipBanner from '@/components/Chapters/ChapterTipBanner.svelte'
    import goBackLinkIcon from '@/assets/img/go-back-link-icon.svg'
    import EnhanceBtn from '@/components/SVG/buttons/enhance-btn.svg.svelte'
    import TipTap from '@/components/TipTap.svelte'
    import languages from '@/data/translate_languages.json'
    import Fa from 'svelte-fa'
    import { faPlus, faTrash } from '@fortawesome/free-solid-svg-icons'
    import axios from 'axios'

    export let transcriptions: App.TranscriptionsData | null = null
    export let chapter: { data: App.Chapter }

    let modal: HTMLDialogElement
    let input: HTMLInputElement

    const form = useForm({
        content: chapter.data.content ?? '',
        title: chapter.data.title,
        status: chapter.data.status,
        images: [],
    })

    let language: string | null = null

    onMount(() => {
        if (transcriptions) {
            $form.content ? modal.showModal() : paste('replace')
        }
    })

    function paste(mode: string) {
        switch (mode) {
            case 'start':
                $form.content = Object.values(transcriptions).join('\n\n') + '\n\n' + $form.content
                break
            case 'end':
                $form.content += '\n\n' + Object.values(transcriptions).join('\n\n')
                break
            case 'replace':
                $form.content = Object.values(transcriptions).join('\n\n')
                break
        }

        modal.close()
    }

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                ...data,
                _method: 'PUT',
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .post(`/chapters/${chapter.data.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    $form.images = []
                    $form
                        .defaults({
                            content: chapter.data.content ?? '',
                            title: chapter.data.title,
                            status: chapter.data.status,
                            images: [],
                        })
                        .reset()
                },
            })
    }

    async function translateText(language) {
        if (!language) {
            $form.content = chapter.data.content
        }

        axios
            .post('/translate', {
                text: chapter.data.content,
                options: {
                    target: language,
                    format: 'text',
                },
            })
            .then((res) => {
                $form.content = res.data.text
            })
    }

    function addImages(event) {
        $form.images.push({
            file: event.target.files[0],
            caption: prompt('Please enter image caption'),
            url: URL.createObjectURL(event.target.files[0]),
        })

        $form.images = $form.images
    }

    function removeImage() {
        if (chapter.data.images?.length) {
            router.delete(`/chapters/${chapter.data.id}/image/${chapter.data.images[0].id}`, {
                only: ['chapter'],
                preserveScroll: true,
            })
        }

        $form.images.forEach((image) => URL.revokeObjectURL(image.url))
        $form.images = []
    }

    $: images = chapter.data.images?.length ? chapter.data.images : $form.images?.length ? $form.images : []
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<ChapterNameBanner title={$form.title} />
<ChapterTipBanner
    title="OttoStory recording tip:"
    tip="This is your transcription. Edit any misspellings in proper nouns, or city names. You can also add more text via typing or rerecord additional information."
/>

<form on:submit|preventDefault={submit} in:fade>
    <main class="otto-container">
        <div class="card bg-neutral text-neutral-content">
            <div class="card-body gap-4">
                <div
                    class="form-control gap-2 {$form.errors.content
                        ? 'textarea-error'
                        : ''} textarea textarea-bordered textarea-ghost rounded-xl"
                >
                    <TipTap
                        class="min-h-[150px] w-full text-lg first-letter:font-serif first-letter:text-4xl first-letter:italic first-letter:text-primary md:text-2xl"
                        bind:content={$form.content}
                        placeholder="Type Your Story here..."
                    />
                    <input bind:this={input} type="file" accept="image/*" class="hidden" on:change={addImages} />
                    {#if images.length}
                        <div class="flex flex-wrap items-center justify-center gap-4">
                            {#each images as image, i (image.url)}
                                <div class="relative flex flex-col items-center justify-center gap-2">
                                    <figure
                                        class="flex w-full max-w-sm flex-col rounded-xl border-error bg-base-100"
                                        class:border={$form.errors[`images.${i}.file`] ||
                                            $form.errors[`images.${i}.caption`]}
                                        in:fade
                                    >
                                        <button
                                            class="btn-trash btn btn-circle btn-error btn-outline btn-sm absolute right-2 top-2"
                                            on:click={removeImage}><Fa icon={faTrash} /></button
                                        >
                                        <img src={image.url} alt={image.caption} class="w-full bg-base-200" />
                                        <figcaption class="p-2 italic">{image.caption}</figcaption>
                                    </figure>
                                    {#if $form.errors?.[`images.${i}.file`]}
                                        <span class="badge badge-error badge-lg mx-auto text-neutral/80">
                                            {$form.errors[`images.${i}.file`]}
                                        </span>
                                    {/if}
                                    {#if $form.errors[`images.${i}.caption`]}
                                        <span class="badge badge-error badge-lg mx-auto text-neutral/80">
                                            {$form.errors?.[`images.${i}.caption`]}
                                        </span>
                                    {/if}
                                </div>
                            {/each}
                        </div>
                    {:else}
                        <div class="flex">
                            <button
                                type="button"
                                class="btn btn-ghost btn-sm"
                                on:click|preventDefault={() => input.click()}
                            >
                                <Fa icon={faPlus} /> Add Image
                            </button>
                        </div>
                    {/if}
                </div>
                {#if $form.errors.content}
                    <span class="badge badge-error badge-lg mx-auto text-neutral/80">
                        {$form.errors.content}
                    </span>
                {/if}
                <div class="flex flex-col justify-between gap-4 md:flex-row">
                    <div class="flex items-center gap-4">
                        <a href="/chapters/{chapter.data.id}/edit" class="goBackLink" use:inertia>
                            <img src={goBackLinkIcon} alt="Record" />
                            <span>Record more</span>
                        </a>
                    </div>
                    <div class="flex flex-col items-stretch gap-4 md:flex-row md:items-center">
                        <select
                            name="language"
                            id="language"
                            class="select select-bordered select-ghost rounded-full"
                            bind:value={language}
                            on:change={() => translateText(language)}
                        >
                            <option value={null}>Default</option>
                            {#each languages as language}
                                <option value={language.code}>{language.language}</option>
                            {/each}
                        </select>
                        {#if $form.isDirty}
                            <button type="submit" class="btn btn-secondary rounded-full" data-status="draft">
                                Save & Next
                            </button>
                        {:else}
                            <a
                                use:inertia
                                class="btn btn-primary btn-outline rounded-full text-lg"
                                href="/chapters/{chapter.data.id}/finish"
                            >
                                Complete chapter
                            </a>

                            <a href="/chapters/{chapter.data.id}/enhance" use:inertia>
                                <EnhanceBtn class="w-full" />
                            </a>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </main>
</form>

<dialog bind:this={modal} class="modal">
    <form method="dialog" class="modal-box w-11/12 max-w-5xl">
        <h3 class="text-lg font-bold">Do you want to replace old content?</h3>
        <p class="py-4">
            It seems like you have been working on this chapter already. How do you want to proceed with new content?
        </p>
        <div class="flex flex-wrap gap-x-4 gap-y-2">
            <button
                type="button"
                class="btn btn-error btn-outline btn-sm"
                on:click|preventDefault={() => paste('replace')}>Replace old content</button
            >
            <button
                type="button"
                class="btn btn-primary btn-outline btn-sm"
                on:click|preventDefault={() => paste('start')}>Paste at the beginning</button
            >
            <button
                type="button"
                class="btn btn-primary btn-outline btn-sm"
                on:click|preventDefault={() => paste('end')}>Paste in the end</button
            >
        </div>
    </form>
</dialog>
