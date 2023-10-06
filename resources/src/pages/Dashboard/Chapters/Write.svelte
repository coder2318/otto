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
    import { onMount } from 'svelte'
    import { strToHtml } from '@/service/helpers'

    export let transcriptions: App.TranscriptionsData | null = null
    export let chapter: { data: App.Chapter }

    let editor: Editor
    let modal: HTMLDialogElement

    const form = useForm({
        content: chapter.data.content ?? '',
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
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .put(`/chapters/${chapter.data.id}`, {
                preserveScroll: true,
            })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

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
            <div class="alert alert-success flex flex-wrap items-center rounded-b-none">
                <span>You have shared {words} words in this chapter</span>
                <span>|</span>
                <span class="flex-1">{pages} pages</span>

                {#if transcriptions}
                    <span class="italic"> Please check transcription for any possible errors before continue. </span>
                {/if}
            </div>
            <TipTap
                class="rounded-t-none border border-neutral-content/20 bg-neutral p-4 lg:text-lg"
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
        <a href="/chapters/{chapter.data.id}/edit" class="btn btn-neutral rounded-full pl-0 font-normal" use:inertia>
            <span class="badge mask badge-accent mask-circle p-4"><Fa icon={faArrowLeft} /></span>
            Go Back
        </a>
        {#if $form.content != chapter.data.content}
            <button type="submit" class="btn btn-secondary rounded-full" data-status="draft"> Save & Next </button>
        {:else}
            <div class="flex gap-4">
                <a
                    use:inertia
                    class="btn btn-primary btn-outline rounded-full"
                    href="/chapters/{chapter.data.id}/finish"
                >
                    Complete &<br /> Finish this Chapter
                </a>

                <a href="/chapters/{chapter.data.id}/enhance" class="btn btn-primary rounded-full" use:inertia>
                    Ask Otto AI to<br />enhance the Writing
                </a>
            </div>
        {/if}
    </section>
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
