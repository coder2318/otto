<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Demo/Breadcrumbs.svelte'
    import TipTap from '@/components/TipTap.svelte'
    import type { Editor } from '@tiptap/core'
    import Otto from '@/components/SVG/otto.svg.svelte'

    export let otto_edit: string = ''
    export let chapter: { data: App.Chapter }

    let editor: Editor

    const form = useForm({
        original: chapter.data.content,
        enhanced: otto_edit,
        use: null,
        status: chapter.data.status,
    })

    $: wordsOriginal =
        $form.original
            ?.replace(/(<([^>]+)>)/gi, '')
            .replace(/&nbsp;/gi, ' ')
            .trim()
            .split(/\s+/).length ?? 0
    $: wordsEnhanced =
        $form.enhanced
            ?.replace(/(<([^>]+)>)/gi, '')
            .replace(/&nbsp;/gi, ' ')
            .trim()
            .split(/\s+/).length ?? 0

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                content: data[data.use],
                status: event.submitter.dataset?.status ?? data.status,
                redirect: event.submitter.dataset?.redirect ?? null,
            }))
            .put(`/demo`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={3} />

<section
    class="container card relative m-4 mx-auto rounded-xl px-4"
    style="background-image:{chapter.data.cover};"
    in:fade
>
    <div
        class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary/80 to-primary/40"
    />
    <div class="card-body z-10 gap-4 text-primary-content">
        <h1 class="card-title font-serif text-4xl italic">
            {chapter.data.title}
        </h1>
    </div>
</section>

<form on:submit|preventDefault={submit} class="p-4" in:fade>
    <main class="container m-4 mx-auto grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div
            class="form-control join join-vertical transition-all {$form.use ==
            'enhanced'
                ? 'scale-[1.01] drop-shadow-lg'
                : ''}"
        >
            <div class="alert alert-success flex items-center rounded-b-none">
                <span
                    >You have shared {wordsEnhanced} words in this chapter</span
                >
                <span>|</span>
                <span>{Math.round(wordsEnhanced / 500)} pages</span>
            </div>
            <TipTap
                class="rounded-t-none border border-neutral-content/20 bg-neutral p-4 font-serif"
                bind:editor
                bind:content={$form.enhanced}
                placeholder="Write your story here..."
            >
                <h2
                    slot="top"
                    class="m-0 flex items-center justify-between gap-4 rounded-none border border-b-0 border-neutral-content/20 bg-neutral p-4 text-2xl text-primary md:text-3xl lg:text-4xl"
                >
                    <span class="flex items-center gap-4"
                        ><Otto class="h-10" />
                        <span>Otto's <i>Enhance</i></span></span
                    >
                    <button
                        class="btn btn-primary btn-xs font-sans"
                        class:btn-outline={$form.use == 'enhanced'}
                        disabled={$form.use == 'enhanced'}
                        on:click|preventDefault={() => ($form.use = 'enhanced')}
                        >Use OTTO Writing</button
                    >
                </h2>
            </TipTap>
        </div>
        <div
            class="form-control join join-vertical transition-all {$form.use ==
            'original'
                ? 'scale-[1.01] drop-shadow-lg'
                : ''}"
        >
            <div class="alert alert-info flex items-center rounded-b-none">
                <span
                    >You have shared {wordsOriginal} words in this chapter</span
                >
                <span>|</span>
                <span>{Math.round(wordsOriginal / 500)} pages</span>
            </div>
            <TipTap
                class="rounded-t-none border border-neutral-content/20 bg-neutral p-4 font-serif"
                bind:editor
                bind:content={$form.original}
                placeholder="Write your story here..."
            >
                <h2
                    slot="top"
                    class="m-0 flex items-center justify-between gap-4 rounded-none border border-b-0 border-neutral-content/20 bg-neutral p-4 text-2xl md:text-3xl lg:text-4xl"
                >
                    <span>Original Writing</span>
                    <button
                        class="btn btn-primary btn-xs font-sans"
                        class:btn-outline={$form.use == 'original'}
                        disabled={$form.use == 'original'}
                        on:click|preventDefault={() => ($form.use = 'original')}
                        >Use Original Writing</button
                    >
                </h2>
            </TipTap>
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-end">
        {#if $form.isDirty && $form.use}
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="btn btn-primary rounded-full"
                    data-status="published"
                    data-redirect="demo.finish"
                >
                    Finish this Chapter
                </button>
            </div>
        {/if}
    </section>
</form>
