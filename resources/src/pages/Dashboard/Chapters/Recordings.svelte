<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft, faTrash } from '@fortawesome/free-solid-svg-icons'
    import { start, done } from '@/components/Loading.svelte'
    import { bytes } from '@/service/helpers'
    import { dayjs } from '@/service/dayjs'

    export let chapter: { data: App.Chapter }

    const form = useForm({
        recordings: [],
    })

    let selected = {}

    $: $form.recordings = Object.entries(selected)
        .filter(([, v]) => v)
        .map(([id]) => id)

    function submit(event: SubmitEvent) {
        $form.post(`/chapters/${chapter.data.id}/recordings`, {
            onStart: start,
            onFinish: done,
            hideProgress: true,
        })
    }

    let dialog: HTMLDialogElement
    let deleting: number = null

    function deleteRecording(index: number) {
        deleting = index
        dialog.showModal()
    }

    function confirmDelete() {
        $form.delete(`/chapters/${chapter.data.id}/recordings/${deleting}`)
        dialog.close()
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

<form on:submit|preventDefault={submit}>
    <main class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
        <div class="card-body gap-4">
            <h1 class="card-title mb-4">{chapter.data.title}</h1>

            {#each chapter.data?.recordings as recording}
                <div class="card bg-neutral">
                    <div
                        class="card-body grid auto-rows-min grid-cols-4 flex-row items-center justify-between gap-4 lg:flex"
                    >
                        <input
                            type="checkbox"
                            class="checkbox-primary checkbox col-span-1"
                            class:checkbox-success={recording.transcribed}
                            bind:checked={selected[recording.id]}
                        />
                        <div
                            class="col-span-3 flex flex-1 flex-col gap-2 lg:col-span-1"
                        >
                            <span>{recording.name}</span>
                            <span class="flex gap-2 text-xs opacity-50">
                                <span>Size: {bytes(recording.size)}</span>
                                <span
                                    >Transcribed: {recording.transcribed
                                        ? 'Yes'
                                        : 'No'}</span
                                >
                                <span
                                    >Uploaded: {dayjs(
                                        recording.created_at
                                    ).format('YYYY-MM-DD HH:mm')}</span
                                >
                            </span>
                        </div>
                        <div class="col-span-4 mx-auto">
                            <audio src={recording.url} controls />
                        </div>
                        <div class="col-span-4 mx-auto">
                            <button
                                type="button"
                                class="btn btn-error btn-sm"
                                on:click|preventDefault={() =>
                                    deleteRecording(recording.id)}
                            >
                                <Fa icon={faTrash} />
                            </button>
                        </div>
                    </div>
                </div>
            {:else}
                <div class="card text-base-content/60">
                    <div class="card-body items-center">
                        <span>No recordings found</span>
                    </div>
                </div>
            {/each}
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/chapters/{chapter.data.id}/record"
            class="btn btn-neutral rounded-full pl-0"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            >
            Back
        </a>
        {#if $form.recordings.length}
            <button type="submit" class="btn btn-secondary rounded-full">
                Transcribe
            </button>
        {/if}
    </section>
</form>

<dialog bind:this={dialog} class="modal">
    <form method="dialog" class="modal-box">
        <h3 class="text-lg font-bold">
            Are you sure you want to delete this recording?
        </h3>
        <div class="modal-action">
            <button
                class="btn btn-error btn-sm"
                on:click|preventDefault={confirmDelete}>Delete</button
            >
            <button class="btn btn-sm" on:click={() => dialog.close()}
                >Close</button
            >
        </div>
    </form>
</dialog>
