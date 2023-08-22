<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Demo/Breadcrumbs.svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft, faFile } from '@fortawesome/free-solid-svg-icons'
    import TipTap from '@/components/TipTap.svelte'
    import { start, done } from '@/components/Loading.svelte'
    import type { Editor } from '@tiptap/core'

    export let transcriptions: App.TranscriptionsData | null = null
    export let chapter: { data: App.Chapter }

    let editor: Editor

    const form = useForm({
        content: chapter.data.content,
        title: chapter.data.title,
        status: chapter.data.status,
    })

    $: words =
        $form.content
            ?.replace(/(<([^>]+)>)/gi, '')
            .replace(/&nbsp;/gi, ' ')
            .trim()
            .split(/\s+/).length ?? 0
    $: pages = Math.ceil(words / 500)

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                ...data,
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .put(`/demo`, {
                onSuccess: () =>
                    $form.defaults({
                        content: chapter.data.content,
                        title: chapter.data.title,
                        status: chapter.data.status,
                    }),
            })
    }

    function pasteTranscription(transcription: string) {
        editor.commands.insertContent({
            type: 'paragraph',
            content: [
                {
                    type: 'text',
                    text: transcription,
                },
            ],
        })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

<section class="container card m-4 mx-auto rounded-xl bg-base-300 px-4" in:fade>
    <div class="card-body gap-4">
        <textarea
            class="textarea card-title textarea-ghost font-serif"
            bind:value={$form.title}
        />
    </div>
</section>
<form on:submit|preventDefault={submit} in:fade>
    {#if transcriptions}
        <div class="container mx-auto">
            <div
                class="tooltip tooltip-info"
                data-tip="Click on filename to paste it's transcription to the editor"
            >
                <ul
                    class="container menu rounded-box menu-horizontal mx-auto w-full bg-info"
                >
                    {#each Object.entries(transcriptions ?? {}) as [file, transcription] (file)}
                        <li>
                            <button
                                type="button"
                                on:click|preventDefault={() =>
                                    pasteTranscription(transcription)}
                            >
                                <Fa icon={faFile} />
                                {file}
                            </button>
                        </li>
                    {/each}
                </ul>
            </div>
        </div>
    {/if}
    <main class="container card m-4 mx-auto">
        <div class="form-control join join-vertical">
            <div
                class:alert-success={pages <= 1}
                class:alert-error={pages > 1}
                class="alert sticky top-12 z-20 flex items-center rounded-b-none"
            >
                <span>You have shared {words} words in this chapter</span>
                <span>|</span>
                <span>{pages} pages</span>
                {#if pages > 1}
                    (You can share only 1 page for demo.)
                {/if}
            </div>
            <TipTap
                class="rounded-t-none border border-neutral-content/20 bg-neutral p-4 font-serif"
                bind:editor
                bind:content={$form.content}
                placeholder="Write your story here..."
            />
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/demo/files"
            class="btn btn-neutral rounded-full pl-0"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            >
            Back
        </a>
        {#if $form.isDirty}
            <button
                type="submit"
                class="btn btn-secondary rounded-full"
                data-status="draft"
            >
                Save it as Draft
            </button>
        {:else}
            <div class="flex gap-4">
                <a
                    use:inertia
                    class="btn btn-primary btn-outline rounded-full"
                    href="/demo/finish"
                >
                    Complete &<br /> Finish this Chapter
                </a>

                <a
                    use:inertia={{
                        onStart: start,
                        onFinish: done,
                        hideProgress: true,
                    }}
                    class="btn btn-primary rounded-full"
                    href="/demo/enchance"
                >
                    Ask Otto AI to<br />Enchance the Writing
                </a>
            </div>
        {/if}
    </section>
</form>
