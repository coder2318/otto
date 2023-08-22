<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Demo/Breadcrumbs.svelte'
    import AudioRecorder from '@/components/AudioRecorder.svelte'

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
                redirect: 'demo.attachments',
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .post(`/demo`, {
                forceFormData: true,
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
    <main class="container card m-4 mx-auto rounded-xl bg-neutral px-4">
        <div class="card-body gap-4">
            <AudioRecorder bind:recordings={$form.attachments} maxFiles={1} />
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-end">
        <button
            class="btn btn-primary btn-outline rounded-full"
            data-status="draft"
            type="submit"
        >
            {#if $form.isDirty}Save &{/if} Transcribe Attachments
        </button>
    </section>
</form>
