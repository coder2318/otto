<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, useForm } from '@inertiajs/svelte'
    import { onMount } from 'svelte'
    import ChapterNameBanner from '@/components/Chapters/ChapterNameBanner.svelte'
    import ChapterTipBanner from '@/components/Chapters/ChapterTipBanner.svelte'
    import goBackLinkIcon from '@/assets/img/go-back-link-icon.svg'
    import EnhanceBtn from '@/components/SVG/buttons/enhance-btn.svg.svelte'
    import { autosize } from '@/service/svelte'

    export let transcriptions: App.TranscriptionsData | null = null
    export let chapter: { data: App.Chapter }

    let modal: HTMLDialogElement

    const form = useForm({
        content: chapter.data.content ?? '',
        title: chapter.data.title,
        status: chapter.data.status,
        images: [],
    })

    onMount(() => {
        if (transcriptions) {
            $form.content ? modal.showModal() : paste('replace')
        }
    })

    function paste(mode: string) {
        switch (mode) {
            case 'start':
                $form.content = Object.values(transcriptions).join('\n\n') + '\n\n' + $form.content
                break
            case 'end':
                $form.content += '\n\n' + Object.values(transcriptions).join('\n\n')
                break
            case 'replace':
                $form.content = Object.values(transcriptions).join('\n\n')
                break
        }

        modal.close()
    }

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                ...data,
                _method: 'PUT',
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .post(`/chapters/${chapter.data.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    $form.images = []
                    $form.defaults({
                        content: chapter.data.content ?? '',
                        title: chapter.data.title,
                        status: chapter.data.status,
                        images: [],
                    })
                },
            })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<ChapterNameBanner title={$form.title} />
<ChapterTipBanner
    title="OttoStory recording tip:"
    tip="This is your transcription. Edit any misspellings in proper nouns, or city names. You can also add more text via typing or rerecord additional information."
/>

<form on:submit|preventDefault={submit} in:fade>
    <main class="otto-container">
        <div class="card bg-neutral text-neutral-content">
            <div class="card-body gap-4">
                <div class="form-control gap-2">
                    <textarea
                        class="textarea textarea-bordered rounded-xl font-sans text-2xl"
                        class:textarea-error={$form.errors.content}
                        bind:value={$form.content}
                        name="content"
                        rows="10"
                        use:autosize={{ offset: 2 }}
                        placeholder="Type Your Story here..."
                    />
                    {#if $form.errors.content}
                        <span class="badge badge-error badge-lg mx-auto text-neutral/80">
                            {$form.errors.content}
                        </span>
                    {/if}
                </div>
                <div class="flex justify-between">
                    <div class="flex items-center gap-4">
                        <a href="/chapters/{chapter.data.id}/edit" class="goBackLink" use:inertia>
                            <img src={goBackLinkIcon} alt="Record" />
                            <span>Record more</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        {#if $form.content != chapter.data.content}
                            <button type="submit" class="otto-btn-secondary medium" data-status="draft">
                                Save & Next
                            </button>
                        {:else}
                            <a
                                use:inertia
                                class="btn btn-primary btn-outline rounded-full text-lg"
                                href="/chapters/{chapter.data.id}/finish"
                            >
                                Complete chapter
                            </a>

                            <a class="otto-btn-svg" href="/chapters/{chapter.data.id}/enhance" use:inertia>
                                <EnhanceBtn />
                            </a>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </main>
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
