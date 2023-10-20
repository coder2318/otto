<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, router } from '@inertiajs/svelte'
    import FilePond from '@/components/FilePond.svelte'
    import BookCoverBuilder from '@/components/Stories/BookCoverBuilder.svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import type { FilePond as FilePondType } from 'filepond'
    import { createCropperForFilepond } from '@/service/cropper'
    import { fade } from 'svelte/transition'
    import { onMount } from 'svelte'
    import editIcon from '@fortawesome/fontawesome-free/svgs/solid/pen-to-square.svg?raw'
    import Fa from 'svelte-fa'
    import { faArrowLeft, faArrowRight } from '@fortawesome/free-solid-svg-icons'
    import { fileToBase64 } from '@/service/helpers'

    export let story: { data: App.Story }
    export let template: { data: App.BookCoverTemplate }

    let element: HTMLElement, modal: HTMLDialogElement, filepond: FilePondType, builder: BookCoverBuilder, editor: any

    let parameters = {} as any

    let editing: boolean = !story.data.cover

    let loading: boolean = false

    onMount(() => {
        editor = createCropperForFilepond(element, {
            aspectRatio: builder.getCoverAspectRatio(),
            viewMode: 2,
            background: false,
            autoCrop: true,
            ready: () => {
                modal.showModal()
            },
        })

        return () => {
            editor.clear()
            editor = null
        }
    })

    function canvelEdit() {
        editor.oncancel()
        editor.onclose && editor.onclose()
        editor?.clear()
    }

    function saveImage() {
        modal.close()
        editor.onconfirm(editor.getOptions())
        editor.onclose && editor.onclose()
        editor?.clear()
    }

    async function submit() {
        if (loading) return

        loading = true
        const file = new File([await builder.getFile()], 'cover.png', {
            type: 'image/png',
        })
        router.post(
            `/stories/${story.data.id}`,
            {
                cover: file,
                _method: 'PUT',
                redirect: 'dashboard.stories.order',
            },
            {
                forceFormData: true,
                onFinish: () => {
                    loading = false
                },
            }
        )
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<section class="container mx-auto px-4" in:fade>
    <Breadcrumbs step={2} {story} />
</section>

<form on:submit|preventDefault={submit} in:fade id="book-cover">
    <section class="container card m-4 mx-auto grid grid-cols-1 gap-8 rounded-xl px-4 md:grid-cols-2">
        <div class="card bg-base-200">
            <div class="card-body gap-4">
                {#if editing}
                    {#each template.data.fields as field}
                        <div class="form-control">
                            <label class="label" for={field.key}>
                                <span class="label-text">{field.name}</span>
                            </label>
                            {#if field.type === 'text'}
                                <textarea
                                    class="textarea textarea-bordered"
                                    bind:value={parameters[field.key]}
                                    name={field.key}
                                    placeholder={field.name}
                                    rows="1"
                                />
                            {:else if field.type === 'color'}
                                <input
                                    class="input input-bordered w-full"
                                    bind:value={parameters[field.key]}
                                    type="color"
                                    name={field.key}
                                    placeholder={field.name}
                                />
                            {:else if field.type === 'image' && editor}
                                <div class="rounded-lg border border-base-content/20">
                                    <FilePond
                                        name={field.key}
                                        server={false}
                                        bind:pond={filepond}
                                        onpreparefile={async (file, blob) =>
                                            (parameters[field.key] = await fileToBase64(blob))}
                                        onremovefile={() => (parameters[field.key] = null)}
                                        imageEditEditor={editor}
                                        allowImageEdit={true}
                                        allowMultiple={false}
                                        imageEditInstantEdit={true}
                                        styleImageEditButtonEditItemPosition="top right"
                                        imageEditIconEdit={`<div class="flex p-1.5 fill-neutral">${editIcon}</div>`}
                                    />
                                </div>
                            {/if}
                        </div>
                    {/each}
                {:else}
                    <div class="flex h-full flex-col items-center justify-center gap-4">
                        <button
                            type="button"
                            class="max-w-96 btn btn-primary w-full rounded-full"
                            on:click={() => (editing = true)}
                        >
                            Change Cover
                        </button>
                        <a
                            use:inertia
                            href="/stories/{story.data.id}/order"
                            class="max-w-96 btn btn-primary btn-outline w-full rounded-full"
                        >
                            Order Book
                        </a>
                    </div>
                {/if}
            </div>
        </div>
        <div class="card border-2 border-base-300">
            <div class="card-body max-h-screen items-center justify-center gap-4">
                <BookCoverBuilder
                    bind:this={builder}
                    class="select-none {!editing ? 'hidden' : ''}"
                    pages={story.data.pages ?? 0}
                    {parameters}
                    template={template.data}
                />
                {#if !editing}
                    <div class="flex h-full items-center justify-center">
                        <img src={story.data.cover} alt="" class="h-full w-full object-cover" />
                    </div>
                {/if}
            </div>
        </div>
    </section>

    <section class="container mx-auto mb-8 flex justify-between">
        <a href="/stories/{story.data.id}" class="btn btn-neutral rounded-full pl-0" use:inertia>
            <span class="badge mask badge-accent mask-circle p-4"><Fa icon={faArrowLeft} /></span>
            Back
        </a>
        {#if editing}
            <div class="flex gap-4">
                <a href="/stories/{story.data.id}/covers" use:inertia class="btn btn-neutral rounded-full">
                    More Covers
                </a>
                <button class="btn btn-secondary rounded-full pr-0" disabled={loading} type="submit">
                    {#if loading}<span class="loading loading-spinner"></span>{/if}
                    Save & Next
                    <span class="badge mask badge-neutral mask-circle p-4"><Fa icon={faArrowRight} /></span>
                </button>
            </div>
        {:else}
            <a href="/stories/{story.data.id}/order" class="btn btn-secondary rounded-full" use:inertia> Continue </a>
        {/if}
    </section>
</form>

<dialog bind:this={modal} class="modal">
    <form method="dialog" class="modal-box w-11/12 max-w-5xl" on:submit={canvelEdit}>
        <div bind:this={element} class="min-h-[500px]" />
        <div class="modal-action">
            <button type="button" class="btn btn-primary" on:click|preventDefault={saveImage}>Confirm</button>
            <button type="submit" class="btn" form="book-cover">Close</button>
        </div>
    </form>
</dialog>
