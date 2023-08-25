<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, router } from '@inertiajs/svelte'
    import FilePond from '@/components/FilePond.svelte'
    import BookCoverBuilder from '@/components/Stories/BookCoverBuilderV2.svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import type { FilePond as FilePondType } from 'filepond'
    import { createCropperForFilepond } from '@/service/cropper'
    import { fade } from 'svelte/transition'
    import { onMount } from 'svelte'
    import editIcon from '@fortawesome/fontawesome-free/svgs/solid/pen-to-square.svg?raw'
    import Fa from 'svelte-fa'
    import {
        faArrowLeft,
        faArrowRight,
    } from '@fortawesome/free-solid-svg-icons'
    import { fileToBase64 } from '@/service/helpers'

    export let story: { data: App.Story }
    export let template: { data: App.BookCoverTemplate }

    let element: HTMLElement,
        modal: HTMLDialogElement,
        filepond: FilePondType,
        builder: BookCoverBuilder,
        editor: any

    let parameters = {} as any

    onMount(() => {
        editor = createCropperForFilepond(element, {
            aspectRatio: 6 / 9,
            viewMode: 2,
            background: false,
            autoCrop: true,
            ready: () => modal.showModal(),
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
        const file = new File([await builder.getFile(1800, 9 / 6)], 'cover.jpg')
        router.post(
            `/stories/${story.data.id}`,
            { cover: file, _method: 'PUT', redirect: 'stories.contents' },
            { forceFormData: true }
        )
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<section class="container mx-auto px-4" in:fade>
    <Breadcrumbs step={1} {story} />
</section>

<form on:submit|preventDefault={submit} in:fade>
    <section
        class="container card m-4 mx-auto grid grid-cols-1 gap-8 rounded-xl px-4 md:grid-cols-2"
    >
        <div class="card bg-base-300">
            <div class="card-body gap-4">
                {#each template.data.fields as field}
                    <div class="form-control">
                        <label class="label" for={field.key}>
                            <span class="label-text">{field.name}</span>
                        </label>
                        {#if field.type === 'text'}
                            <input
                                class="input input-bordered"
                                bind:value={parameters[field.key]}
                                type="text"
                                name={field.key}
                                placeholder={field.name}
                            />
                        {:else if field.type === 'image'}
                            <FilePond
                                bind:pond={filepond}
                                onpreparefile={async (file, blob) =>
                                    (parameters[field.key] =
                                        await fileToBase64(blob))}
                                onremovefile={() =>
                                    (parameters[field.key] = null)}
                                imageEditEditor={editor}
                                allowImageEdit={true}
                                allowMultiple={false}
                                imageEditInstantEdit={true}
                                styleImageEditButtonEditItemPosition="top right"
                                imageEditIconEdit={`<div class="flex p-1.5 fill-neutral">${editIcon}</div>`}
                            />
                        {/if}
                    </div>
                {/each}
            </div>
        </div>
        <div class="card border-2 border-base-300">
            <div
                class="card-body max-h-screen items-center justify-center gap-4"
            >
                <BookCoverBuilder
                    bind:this={builder}
                    class="h-full w-full"
                    {parameters}
                    template={template.data}
                />
            </div>
        </div>
    </section>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/stories/{story.data.id}"
            class="btn btn-neutral rounded-full pl-0"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            >
            Back
        </a>
        <div class="flex gap-4">
            <a
                href="/stories/{story.data.id}/covers"
                use:inertia
                class="btn btn-neutral rounded-full"
            >
                More Covers
            </a>
            <button class="btn btn-secondary rounded-full pr-0" type="submit">
                Save & Next
                <span class="badge mask badge-neutral mask-circle p-4"
                    ><Fa icon={faArrowRight} /></span
                >
            </button>
        </div>
    </section>
</form>

<dialog bind:this={modal} class="modal">
    <form
        method="dialog"
        class="modal-box w-11/12 max-w-5xl"
        on:submit={canvelEdit}
    >
        <div bind:this={element} class="min-h-[500px]" />
        <div class="modal-action">
            <button
                type="button"
                class="btn btn-primary"
                on:click|preventDefault={saveImage}>Confirm</button
            >
            <button type="submit" class="btn">Close</button>
        </div>
    </form>
</dialog>
