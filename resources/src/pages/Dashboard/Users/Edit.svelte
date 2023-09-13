<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte'
    import FilePond from '@/components/FilePond.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import { fade } from 'svelte/transition'
    import type { FilePond as FilePondType } from 'svelte-filepond'
    import type { FilePondErrorDescription, FilePondFile } from 'filepond'
    import editIcon from '@fortawesome/fontawesome-free/svgs/solid/pen-to-square.svg?raw'
    import { onMount } from 'svelte'
    import { createCropperForFilepond } from '@/service/cropper'

    export let user: { data: App.User }

    const form = useForm({
        avatar: undefined,
    })

    let element: HTMLElement
    let modal: HTMLDialogElement
    let pond: FilePondType
    let initialFile: FilePondFile | null = null
    let editor: any

    onMount(() => {
        editor = createCropperForFilepond(element, {
            aspectRatio: 1,
            viewMode: 2,
            background: false,
            autoCrop: true,
            ready: () => {
                modal.showModal()
            },
        })
    })

    $: {
        if (pond && user.data.avatar) {
            pond.addFile(user.data.avatar, { type: 'input' })
        }
    }

    function reset() {
        $form.reset()
        pond.removeFiles()

        if (initialFile) {
            pond.addFile(initialFile, { type: 'input' })
        }
    }

    function addFile(file: FilePondFile, blob: Blob) {
        $form.avatar = new File([blob], file.filename)
    }

    function submit() {
        $form
            .transform((data) => ({ _method: 'put', ...data }))
            .post(window.location.pathname, {
                forceFormData: true,
            })
    }

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
    <title>{import.meta.env.VITE_APP_NAME} - Edit Profile</title>
</svelte:head>

<form
    class="container mx-auto mt-8 flex flex-col gap-4 px-4"
    in:fade
    on:submit|preventDefault={submit}
>
    <PageHeader class="text-primary">
        Personal <i>Information</i>
    </PageHeader>

    <main class="card border border-base-300 bg-neutral text-neutral-content">
        <div class="card-body">
            <div class="form-control">
                <label class="label" for="avatar">
                    <i class="label-text font-serif text-3xl text-primary">
                        Avatar
                    </i>
                </label>
                {#if editor}
                    <FilePond
                        id="avatar"
                        name="avatar"
                        bind:pond
                        maxFiles={1}
                        server={false}
                        allowImagePreview={true}
                        acceptedFileTypes={['image/*']}
                        imageEditEditor={editor}
                        allowImageEdit={true}
                        imageEditInstantEdit={true}
                        styleImageEditButtonEditItemPosition="top right"
                        imageEditIconEdit={`<div class="flex p-1.5 fill-neutral">${editIcon}</div>`}
                        onpreparefile={addFile}
                        onremovefile={() => ($form.avatar = undefined)}
                    />
                {/if}
                {#if $form.errors.avatar}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.avatar}
                    </span>
                {/if}
            </div>
        </div>
    </main>

    <div class="flex w-full items-center justify-end gap-4">
        <button
            type="button"
            class="btn btn-primary btn-outline rounded-full"
            on:click|preventDefault={reset}
        >
            Reset
        </button>
        <button type="submit" class="btn btn-primary rounded-full">Save</button>
    </div>
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
