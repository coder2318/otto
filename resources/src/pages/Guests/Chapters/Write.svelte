<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
    import TipTap from '@/components/TipTap.svelte'
    import type { Editor } from '@tiptap/core'

    export let transcriptions: App.TranscriptionsData | null = null
    export let chapter: { data: App.Chapter }

    let editor: Editor

    const form = useForm({
        content: chapter.data.content ?? '',
        title: chapter.data.title,
        status: chapter.data.status,
    })

    $form.content += transcriptions
        ? ($form.content ? '\n' : '') + Object.values(transcriptions).join('\n')
        : ''

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
            .put(`/guests/chapters/${chapter.data.id}`, {
                preserveScroll: true
            })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs guest={true} step={2} />

<section class="container card m-4 mx-auto rounded-xl bg-base-200 px-4" in:fade>
    <div class="card-body gap-4">
        <textarea
            class="textarea card-title textarea-ghost font-serif text-2xl font-normal italic text-primary md:text-3xl lg:text-4xl"
            bind:value={$form.title}
            rows="1"
        />
    </div>
</section>
<form on:submit|preventDefault={submit} in:fade>
    <main class="container card m-4 mx-auto">
        <div class="form-control join join-vertical">
            <div
                class="alert alert-success flex flex-wrap items-center rounded-b-none"
            >
                <span>You have shared {words} words in this chapter</span>
                <span>|</span>
                <span class="flex-1">{pages} pages</span>

                {#if transcriptions}
                    <span class="italic">
                        Please check transcription for any possible errors
                        before continue.
                    </span>
                {/if}
            </div>
            <TipTap
                class="rounded-t-none border border-neutral-content/20 bg-neutral p-4 font-serif"
                bind:editor
                bind:content={$form.content}
                placeholder="Write your story here..."
            />
            {#if $form.errors.content}
                <span class="label-text-alt mt-1 text-left text-error">
                    {$form.errors.content}
                </span>
            {/if}
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/guests/chapters/{chapter.data.id}/edit"
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
                Save & Next
            </button>
        {:else}
            <div class="flex gap-4">
                <a
                    use:inertia
                    class="btn btn-primary btn-outline rounded-full"
                    href="/guests/chapters/{chapter.data.id}/finish"
                >
                    Complete &<br /> Finish this Chapter
                </a>
            </div>
        {/if}
    </section>
</form>
