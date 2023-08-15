<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import FilePond from '@/components/FilePond.svelte'
    import BookCoverBuilder from '@/components/Stories/BookCoverBuilder.svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import type { FilePond as FilePondType } from 'filepond'
    import { createCropperForFilepond } from '@/service/cropper'
    import { fade } from 'svelte/transition'
    import { onMount } from 'svelte'
    import editIcon from '@fortawesome/fontawesome-free/svgs/solid/pen-to-square.svg?raw'

    export let story: { data: App.Story }
    export let template: { data: App.BookCoverTemplate }

    let element: HTMLElement,
        modal: HTMLDialogElement,
        filepond: FilePondType,
        editor: any

    let parameters = {} as any

    onMount(() => {
        editor = createCropperForFilepond(element, {
            aspectRatio: 6 / 9,
            viewMode: 2,
            background: false,
            autoCrop: true,
            ready: () => {
                modal.showModal()
            },
        })

        return () => editor?.clear()
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
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<Breadcrumbs step={1} />

<section
    class="container card m-4 mx-auto grid grid-cols-1 gap-8 rounded-xl px-4 md:grid-cols-2"
    in:fade
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
                            onpreparefile={(file, blob) =>
                                (parameters[field.key] =
                                    URL.createObjectURL(blob))}
                            onremovefile={() => (parameters[field.key] = null)}
                            imageEditEditor={editor}
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
        <div class="card-body items-center justify-center gap-4">
            <BookCoverBuilder
                class="h-full w-full select-none"
                {parameters}
                template={template.data}
            />
        </div>
    </div>
</section>

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
