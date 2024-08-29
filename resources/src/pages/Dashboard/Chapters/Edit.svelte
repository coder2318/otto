<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm, inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
    import record from '@/assets/img/chapter-record.jpg'
    import upload from '@/assets/img/chapter-upload.jpg'
    import write from '@/assets/img/chapter-type.jpg'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import { autosize } from '@/service/svelte'
    import { onMount } from 'svelte'

    export let chapter: { data: App.Chapter }
    let dialog: HTMLDialogElement

    onMount(() => {
        chapter?.data?.hasUntranscribedAttachments && dialog.showModal()
    })

    const form = useForm({
        title: chapter.data?.title ?? '',
        status: chapter.data.status,
    })

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                ...data,
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .patch(`/chapters/${chapter.data.id}`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={1} />

<dialog bind:this={dialog} class="modal">
    <form method="dialog" class="modal-box">
        <h3 class="text-lg font-bold">Would you like to transcribe the previously recorded file or start over?</h3>
        <div class="modal-action">
            <a
                class="btn btn-success btn-sm"
                href={`/chapters/${chapter.data.id}/files?onlyUntranscribed=true`}
                use:inertia
            >
                Transcribe
            </a>
            <button class="btn btn-sm" on:click={() => dialog.close()}>Start Over</button>
        </div>
    </form>
</dialog>

<form on:submit|preventDefault={submit} in:fade>
    <main class="container card m-4 mx-auto rounded-xl bg-base-200 px-4">
        {#if chapter.data.processing}
            <figure class="-mx-4 flex-col gap-2 text-base-content/50">
                <progress class="progress progress-secondary w-full" />
                <figcaption>Chapter is being processed</figcaption>
            </figure>
        {:else if chapter.data.edit}
            <figure class="-mx-4 flex-col gap-2 text-base-content/50">
                <progress class="progress progress-success w-full" value="100" max="100" />
                <figcaption>
                    <a href="/chapters/{chapter.data.id}/enhance" use:inertia> Your chapter enhancement is ready! </a>
                </figcaption>
            </figure>
        {/if}
        <div class="card-body gap-4">
            <textarea
                class="textarea card-title textarea-ghost resize-none font-serif text-2xl font-normal italic text-primary md:text-3xl lg:text-4xl"
                rows="1"
                use:autosize={{ offset: 2 }}
                bind:value={$form.title}
            />
            <div class="3455 grid grid-cols-1 gap-8 p-4 md:grid-cols-3">
                <a
                    class="card bg-neutral transition-transform hover:scale-105"
                    href="/chapters/{chapter.data.id}/record"
                    use:inertia
                >
                    <figure>
                        <img src={record} alt="record" />
                    </figure>
                    <div class="card-body items-center">
                        <h2 class="card-title text-2xl font-normal text-primary">Record your Answer</h2>
                    </div>
                </a>
                <a
                    class="card bg-neutral transition-transform hover:scale-105"
                    href="/chapters/{chapter.data.id}/upload"
                    use:inertia
                >
                    <figure>
                        <img src={upload} alt="upload" />
                    </figure>
                    <div class="card-body items-center">
                        <h2 class="card-title text-2xl font-normal text-primary">Upload File</h2>
                    </div>
                </a>
                <a
                    class="card bg-neutral transition-transform hover:scale-105"
                    href="/chapters/{chapter.data.id}/write"
                    use:inertia
                >
                    <figure>
                        <img src={write} alt="write" />
                    </figure>
                    <div class="card-body items-center">
                        <h2 class="card-title text-2xl font-normal text-primary">Type your Story</h2>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <section class="container mx-auto mb-8 flex items-center justify-between">
        <a
            href="/stories/{chapter.data.story_id}/chapters"
            class="btn btn-neutral rounded-full pl-0 font-normal"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"><Fa icon={faArrowLeft} /></span> Go Back
        </a>

        {#if $form.title != chapter.data.title}
            <button type="submit" class="btn btn-secondary rounded-full" data-status="draft"> Save </button>
        {:else}
            <div>
                <a
                    class="btn btn-primary btn-outline rounded-full"
                    href="/chapters/{chapter.data.id}/files"
                    use:inertia
                >
                    Memory Box
                </a>
            </div>
        {/if}
    </section>
</form>
