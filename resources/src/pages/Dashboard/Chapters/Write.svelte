<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft, faFile } from '@fortawesome/free-solid-svg-icons'
    import TipTap from '@/components/TipTap.svelte'
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

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                ...data,
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .put(`/chapters/${chapter.data.id}`)
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

<section class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
    <div class="card-body gap-4">
        <input
            class="input card-title input-ghost font-serif"
            bind:value={$form.title}
        />
    </div>
</section>
{#if transcriptions}
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
{/if}
<form on:submit|preventDefault={submit}>
    <main class="container card m-4 mx-auto">
        <div class="form-control join join-vertical">
            <div class="alert alert-success flex items-center rounded-b-none">
                <span>You have shared {words} words in this chapter</span>
                <span>|</span>
                <span>{Math.round(words / 500)} pages</span>
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
            href="/chapters/{chapter.data.id}/edit"
            class="btn btn-neutral rounded-full pl-0"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            >
            Back
        </a>
        {#if $form.content != chapter.data.content}
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
                    class="btn btn-primary btn-outline rounded-full"
                    href="/chapters/{chapter.data.id}/finish"
                >
                    Complete &<br /> Finish this Chapter
                </a>

                <a
                    class="btn btn-primary rounded-full"
                    href="/chapters/{chapter.data.id}/enchance"
                >
                    Ask Otto AI to<br />Enchance the Writing
                </a>
            </div>
        {/if}
    </section>
</form>
