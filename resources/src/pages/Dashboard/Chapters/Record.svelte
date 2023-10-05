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
    import AudioRecorder from '@/components/AudioRecorder.svelte'
    import { start, done } from '@/components/Loading.svelte'

    export let chapter: { data: App.Chapter }

    const form = useForm({
        title: chapter.data.title,
        attachments: null,
        status: chapter.data.status,
    })

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                _method: 'PUT',
                ...data,
                status: event.submitter.dataset?.status ?? data.status,
                redirect: 'dashboard.chapters.write',
            }))
            .post(`/chapters/${chapter.data.id}`, {
                forceFormData: true,
                onStart: start,
                onFinish: done,
            })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

<section class="container card m-4 mx-auto rounded-xl bg-base-300 px-4" in:fade>
    <div class="card-body gap-4">
        <input
            class="input card-title input-ghost font-serif"
            bind:value={$form.title}
        />
    </div>
</section>

<form on:submit|preventDefault={submit} in:fade>
    <main class="container card m-4 mx-auto rounded-xl bg-neutral px-4">
        <div class="card-body gap-4">
            <AudioRecorder bind:recordings={$form.attachments} />
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/chapters/{chapter.data.id}/edit"
            class="btn btn-neutral rounded-full pl-0 font-normal"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            >
            Go Back
        </a>
        {#if $form.isDirty}
            <button
                type="submit"
                class="btn btn-primary btn-outline rounded-full"
                data-status="draft"
            >
                Transcribe
            </button>
        {/if}
    </section>
</form>
