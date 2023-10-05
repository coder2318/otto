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
    import FilePond from '@/components/FilePond.svelte'
    import { start, done } from '@/components/Loading.svelte'
    import type { FilePondErrorDescription, FilePond as FilePondType } from 'filepond'

    export let chapter: { data: App.Chapter }

    let filepond: FilePondType

    const form = useForm({
        title: chapter.data.title,
        attachments: null,
        status: chapter.data.status,
    })

    function syncFiles(err: FilePondErrorDescription | null) {
        if (err) {
            return
        }

        const files = filepond.getFiles().map((file) => ({
            file: file.file,
            options: [],
        }))

        $form.attachments = files.length ? files : null
    }

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
                onSuccess: () => {
                    filepond.removeFiles()
                },
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
    <main class="container card m-4 mx-auto rounded-xl bg-neutral px-4">
        <div class="card-body gap-4">
            <FilePond
                bind:instance={filepond}
                server={false}
                allowMultiple={true}
                allowProcess={false}
                instantUpload={false}
                onaddfile={syncFiles}
                onremovefile={syncFiles}
                acceptedFileTypes={[
                    'audio/webm',
                    'audio/wav',
                    'audio/mpeg',
                    'audio/mpeg3',
                    'audio/x-mpeg-3',
                    'audio/m4a',
                    'audio/mp4',
                    'audio/flac',
                    'audio/aac',
                    'audio/x-wav',
                    'audio/x-m4a',
                    'text/plain',
                    'application/pdf',
                    `application/vnd.openxmlformats-officedocument.wordprocessingml.document`,
                ]}
            />
            {#if Object.keys($form.errors).length > 0}
                <span class="label-text-alt mt-1 text-left text-error">
                    <ul class="list-inside list-disc">
                        {#each Object.entries($form.errors) as [field, error]}
                            {#if field.startsWith('attachments')}
                                <li>{error}</li>
                            {/if}
                        {/each}
                    </ul>
                </span>
            {/if}
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a href="/chapters/{chapter.data.id}/edit" class="btn btn-neutral rounded-full pl-0 font-normal" use:inertia>
            <span class="badge mask badge-accent mask-circle p-4"><Fa icon={faArrowLeft} /></span>
            Go Back
        </a>
        {#if $form.isDirty}
            <button type="submit" class="btn btn-secondary rounded-full" data-status="draft"> Transcribe </button>
        {/if}
    </section>
</form>
