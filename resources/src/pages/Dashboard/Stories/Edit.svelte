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

    export let story: { data: App.Story }

    let element: HTMLElement,
        modal: HTMLDialogElement,
        filepond: FilePondType,
        editor: any

    let parameters = {
        titleBig: 'Title Big',
        titleSmall: 'Title Small',
        author: 'Author',
        cover: null,
    }

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

        return () => editor.clear()
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
            <div class="form-control">
                <label class="label" for="cover">
                    <span class="label-text font-serif text-lg text-primary"
                        >Upload a <i>Cover Image</i></span
                    >
                </label>
                {#if editor}
                    <FilePond
                        bind:pond={filepond}
                        onpreparefile={(file, blob) =>
                            (parameters.cover = URL.createObjectURL(blob))}
                        onremovefile={() => (parameters.cover = null)}
                        imageEditEditor={editor}
                        allowMultiple={false}
                        imageEditInstantEdit={true}
                        styleImageEditButtonEditItemPosition="top right"
                        imageEditIconEdit={`<svg xmlns="http://www.w3.org/2000/svg" height="1em" class="p-1.5 fill-neutral" viewBox="0 0 512 512"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>`}
                    />
                {/if}
            </div>
            <div class="form-control">
                <label class="label" for="author">
                    <span class="label-text">By Author</span>
                </label>
                <input
                    class="input input-bordered"
                    bind:value={parameters.author}
                    type="text"
                    name="author"
                    placeholder="Author"
                />
            </div>
            <div class="form-control">
                <label class="label" for="titleBig">
                    <span class="label-text">Title Large</span>
                </label>
                <input
                    class="input input-bordered"
                    bind:value={parameters.titleBig}
                    type="text"
                    name="titleBig"
                    placeholder="Title Large"
                />
            </div>
            <div class="form-control">
                <label class="label" for="titleSmall">
                    <span class="label-text">Title Medium</span>
                </label>
                <input
                    class="input input-bordered"
                    bind:value={parameters.titleSmall}
                    type="text"
                    name="titleSmall"
                    placeholder="Title Medium"
                />
            </div>
        </div>
    </div>
    <div class="card border-2 border-base-300">
        <div class="card-body items-center justify-center gap-4">
            <BookCoverBuilder class="h-full w-full select-none" {parameters} />
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
