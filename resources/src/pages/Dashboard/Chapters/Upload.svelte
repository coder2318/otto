<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
    import FilePond from '@/components/FilePond.svelte'
    import type {
        FilePondErrorDescription,
        FilePond as FilePondType,
    } from 'filepond'

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

        const files = filepond.getFiles().map((file) => file.file)

        $form.attachments = files.length ? files : null
    }

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                _method: 'PUT',
                ...data,
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .post(`/chapters/${chapter.data.id}`, {
                forceFormData: true,
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

<section class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
    <div class="card-body gap-4">
        <input
            class="input card-title input-ghost font-serif"
            bind:value={$form.title}
        />
    </div>
</section>

<form on:submit|preventDefault={submit}>
    <main class="container card m-4 mx-auto rounded-xl bg-neutral px-4">
        <div class="card-body gap-4">
            <FilePond
                bind:pond={filepond}
                allowMultiple={true}
                allowProcess={false}
                instantUpload={false}
                onaddfile={syncFiles}
                onremovefile={syncFiles}
                acceptedFileTypes={[
                    'audio/*',
                    'text/plain',
                    'application/pdf',
                    'application/msword',
                ]}
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
        {#if $form.isDirty}
            <button
                type="submit"
                class="btn btn-secondary rounded-full"
                data-status="draft"
            >
                Save it as Draft
            </button>
        {:else}
            <div>
                <a
                    class="btn btn-primary btn-outline rounded-full"
                    href="/chapters/{chapter.data.id}/files"
                    use:inertia
                >
                    Transcribe Attachments
                </a>
            </div>
        {/if}
    </section>
</form>
