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
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
    import TipTap from '@/components/TipTap.svelte'
    import { start, done } from '@/components/Loading.svelte'
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
            .put(`/demo`, {
                onSuccess: () =>
                    $form.defaults({
                        content: chapter.data.content,
                        title: chapter.data.title,
                        status: chapter.data.status,
                    }),
            })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

<section class="container card m-4 mx-auto" in:fade>
    <div class="card-body gap-4 rounded-xl bg-base-300">
        <textarea
            class="textarea card-title textarea-ghost font-serif hover:textarea-bordered"
            bind:value={$form.title}
        />
    </div>
</section>
<form on:submit|preventDefault={submit} in:fade class="px-4">
    <main class="container card m-4 mx-auto">
        <div class="form-control join join-vertical">
            <div
                class:alert-success={pages <= 1}
                class:alert-error={pages > 1}
                class="alert sticky top-12 z-20 flex flex-wrap items-center rounded-b-none"
            >
                <span>You have shared {words} words in this chapter</span>
                <span>|</span>
                <span class="flex-1"
                    >{pages} pages {#if pages > 1}
                        (You can share only 1 page for demo.)
                    {/if}</span
                >
                <span class="italic"
                    >Please check transcription for any possible errors before
                    continue.</span
                >
            </div>
            <TipTap
                class="rounded-t-none border {$form.errors.content
                    ? 'border-error'
                    : 'border-neutral-content/20'} bg-neutral p-4 font-serif"
                bind:editor
                bind:content={$form.content}
                autofocus
                placeholder="Write your story here..."
            />

            {#if $form.errors.content}
                <span class="label-text-alt mt-1 text-left text-error">
                    {$form.errors.content}
                </span>
            {/if}
        </div>
    </main>

    <section class="container mx-auto mb-8 flex flex-wrap justify-between">
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
                class="btn btn-secondary rounded-full lg:btn-lg"
                data-status="draft"
            >
                Save & Next
            </button>
        {:else}
            <div class="flex gap-4">
                <a
                    use:inertia
                    class="btn btn-primary btn-outline rounded-full lg:btn-lg"
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
                    class="btn btn-primary rounded-full lg:btn-lg"
                    href="/demo/enhance"
                >
                    Ask Otto AI to<br />enhance the Writing
                </a>
            </div>
        {/if}
    </section>
</form>
