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
    import axios from 'axios'

    export let transcriptions: App.TranscriptionsData | null = null
    export let chapter: { data: App.Chapter }

    let modal: HTMLDialogElement

    const form = useForm({
        content: chapter.data.content ?? '',
        title: chapter.data.title,
        status: chapter.data.status,
    })

    let language: string | null = null

    onMount(() => {
        if (transcriptions) {
            $form.content && $form.content != '<p></p>' ? modal.showModal() : paste('replace')
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
                    $form
                        .defaults({
                            content: chapter.data.content ?? '',
                            title: chapter.data.title,
                            status: chapter.data.status,
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
                text: chapter.data.content == '' ? $form.content : chapter.data.content,
                options: {
                    target: language,
                    format: 'text',
                },
            })
            .then((res) => {
                $form.content = res.data.text
            })
    }

    function uploadImage(event) {
        if (event.detail.image === undefined) {
            axios
                .post(
                    `/chapters/${chapter.data.id}/image`,
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
                .post(`/chapters/${chapter.data.id}/image`, {
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
                        contentType="html"
                        bind:images={chapter.data.images}
                        on:uploadImage={uploadImage}
                    />
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
                                Save to drafts
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
