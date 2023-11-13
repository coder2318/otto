<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import TipTap from '@/components/TipTap.svelte'
    import type { Editor } from '@tiptap/core'
    import { onMount } from 'svelte'
    import { strToHtml } from '@/service/helpers'
    import ChapterNameBanner from '@/components/Chapters/ChapterNameBanner.svelte'
    import ChapterTipBanner from '@/components/Chapters/ChapterTipBanner.svelte'
    import goBackLinkIcon from '@/assets/img/go-back-link-icon.svg'
    import EnhanceBtn from '@/components/SVG/buttons/enhance-btn.svg.svelte'

    export let transcriptions: App.TranscriptionsData | null = null
    export let chapter: { data: App.Chapter }

    let editor: Editor
    let modal: HTMLDialogElement
    let input: HTMLInputElement

    const form = useForm({
        content: chapter.data.content ?? '',
        title: chapter.data.title,
        status: chapter.data.status,
        images: [],
    })

    $: words =
        $form.content
            ?.replace(/(<([^>]+)>)/gi, '')
            .replace(/&nbsp;/gi, ' ')
            .trim()
            .split(/\s+/).length ?? 0
    $: pages = Math.ceil(words / 500)

    onMount(() => {
        if (transcriptions) {
            $form.content ? modal.showModal() : paste('replace')
        }
    })

    function paste(mode: string) {
        switch (mode) {
            case 'start':
            case 'end':
                editor
                    .chain()
                    .focus(mode)
                    .insertContent(strToHtml(Object.values(transcriptions).join('\n\n')), {
                        parseOptions: {
                            preserveWhitespace: false,
                        },
                    })
                    .run()
                break
            case 'replace':
                editor.commands.setContent(strToHtml(Object.values(transcriptions).join('\n\n')), true)
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
                    $form.defaults({
                        content: chapter.data.content ?? '',
                        title: chapter.data.title,
                        status: chapter.data.status,
                        images: [],
                    })
                },
            })
    }

    function addImages(event) {
        $form.images.push({
            file: event.target.files[0],
            caption: prompt('Please enter image caption'),
        })

        $form.images = $form.images
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<!-- <Breadcrumbs step={2} /> -->

<!-- <section class="container card m-4 mx-auto rounded-xl bg-base-200 px-4" in:fade>
    <div class="card-body gap-4">
        <textarea
            class="textarea card-title textarea-ghost resize-none font-serif text-2xl font-normal italic text-primary md:text-3xl lg:text-4xl"
            bind:value={$form.title}
            use:autosize={{ offset: 2 }}
            rows="1"
        />
    </div>
</section> -->

<ChapterNameBanner title={$form.title} />
<ChapterTipBanner
    title="OttoStory recording tip:"
    tip="This is your transcription. Edit any misspellings in proper nouns, or city names. You can also add more text via typing or rerecord additional information."
/>

<form on:submit|preventDefault={submit} in:fade>
    <main class="transcribe">
        <div class="otto-container">
            <div class="transcriptionEditor block">
                <TipTap bind:editor bind:content={$form.content} placeholder="Write your story here..." />
                {#if $form.errors.content}
                    <span class="text-error">
                        {$form.errors.content}
                    </span>
                {/if}

                <div class="transcribe__buttons">
                    <div class="transcribe__buttons_col">
                        <a href="/chapters/{chapter.data.id}/edit" class="goBackLink" use:inertia>
                            <img src={goBackLinkIcon} alt="Record" />
                            <span>Record more</span>
                        </a>
                    </div>
                    <div class="transcribe__buttons_col gap-4">
                        {#if $form.content != chapter.data.content}
                            <button type="submit" class="otto-btn-secondary medium" data-status="draft">
                                Save & Next
                            </button>
                        {:else}
                            <a use:inertia class="otto-btn-outline small" href="/chapters/{chapter.data.id}/finish">
                                Complete chapter
                            </a>

                            <a class="otto-btn-svg" href="/chapters/{chapter.data.id}/enhance" use:inertia>
                                <EnhanceBtn />
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

<style lang="scss">
    .transcribe {
        position: relative;
        padding-bottom: 100px;

        .block {
            background: #fff;
            padding: 24px 32px 32px 32px;
            border: 1px solid rgba(191, 191, 191, 0.4);
            border-radius: 24px;
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

            .otto-btn-outline {
                padding: 0 16px;
                font-weight: 700;
                font-size: 18px;
                border: 1px solid #c6b59f;

                &:hover {
                    background: #c6b59f;
                }
            }
        }

        .text-error {
            display: block;
            width: fit-content;
            font-size: 20px;
            color: #0c345c;
            font-weight: 700;
            background: rgba(247, 163, 146, 0.6);
            padding: 10px 16px;
            border-radius: 40px;
            margin: 0 auto;
            line-height: 1.1;
        }
    }
</style>
