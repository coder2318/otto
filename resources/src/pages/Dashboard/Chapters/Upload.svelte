<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { router } from '@inertiajs/core'

    import { fade } from 'svelte/transition'
    import { page, inertia, useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
    import FilePond from '@/components/FilePond.svelte'
    import type { FilePondErrorDescription, FilePond as FilePondType, FilePondFile } from 'filepond'
    import translateIcon from '@fortawesome/fontawesome-free/svgs/solid/globe.svg?raw'
    import FileTranslateModal from '@/components/Chapters/FileTranslateModal.svelte'
    import { autosize } from '@/service/svelte'

    export let chapter: { data: App.Chapter }

    let filepond: FilePondType
    let modal: HTMLDialogElement
    let currentFile: FilePondFile | null = null

    const form = useForm({
        title: chapter.data.title,
        attachments: null,
        status: chapter.data.status,
    })

    function syncFiles(err: FilePondErrorDescription | null) {
        if (err) {
            return
        }

        const files = filepond.getFiles()

        $form.attachments = files.length ? files : null
    }

    function openMetaDataModal(file: FilePondFile) {
        currentFile = file
        modal.showModal()
    }

    function addMetadataButton(file: FilePondFile) {
        const button = document.createElement('button')
        button.classList.add('filepond--file-action-button', 'p-2', 'text-neutral')
        button.type = 'button'
        button.dataset.align = 'right'
        button.style.transform = 'translate3d(0px, 0px, 0px) scale3d(1, 1, 1)'
        button.title = 'Translate'

        const icon = document.createElement('div')
        icon.classList.add('flex', 'p-1.5', 'fill-neutral', 'w-full', 'h-full')
        icon.innerHTML = translateIcon

        button.appendChild(icon)

        button.addEventListener('click', (event) => {
            event.preventDefault()
            openMetaDataModal(file)
        })

        document
            .getElementById('filepond--item-' + file.id)
            .getElementsByClassName('filepond--file')[0]
            .appendChild(button)
    }

    function submit() {
        document.getElementsByClassName('filepond--drop-label')[0].style.visibility = 'hidden'

        filepond.setOptions({
            server: {
                url: `/chapters/${chapter.data.id}`,
                process: {
                    url: '/attachments',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $page?.props?.csrf_token,
                    },
                    withCredentials: true,
                    onerror: (response) => {
                        console.error(response)
                    },
                    ondata: (formData) => {
                        return formData
                    },
                },
                revert: '/revert',
                restore: '/restore',
                load: '/load',
            },
        })

        filepond.processFiles()
    }

    function filePondAddFile(error: FilePondErrorDescription | null, file: FilePondFile) {
        syncFiles(error)
        addMetadataButton(file)
    }
    function filePondProcessFiles() {
        router.visit(`/chapters/${chapter.data.id}/write`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

<section class="container card m-4 mx-auto rounded-xl bg-base-200 px-4" in:fade>
    <div class="card-body gap-4">
        <textarea
            class="textarea card-title textarea-ghost resize-none font-serif text-2xl font-normal italic text-primary md:text-3xl lg:text-4xl"
            bind:value={$form.title}
            use:autosize={{ offset: 2 }}
            rows="1"
        />
    </div>
</section>

<form on:submit|preventDefault={submit} in:fade>
    <main class="container card m-4 mx-auto rounded-xl bg-neutral px-4">
        <div class="card-body gap-4">
            <FilePond
                bind:instance={filepond}
                instantUpload={false}
                allowMultiple={true}
                allowProcess={true}
                onremovefile={syncFiles}
                onaddfile={filePondAddFile}
                onprocessfiles={filePondProcessFiles}
                acceptedFileTypes={[
                    'audio/webm',
                    'audio/wav',
                    'audio/mpeg',
                    'audio/mpeg3',
                    'audio/x-mpeg-3',
                    'audio/m4a',
                    'audio/mp4',
                    'video/mp4',
                    'audio/flac',
                    'audio/aac',
                    'audio/x-wav',
                    'audio/x-m4a',
                    'text/plain',
                    'application/pdf',
                    `application/vnd.openxmlformats-officedocument.wordprocessingml.document`,
                ]}
            />
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

<FileTranslateModal bind:modal bind:file={currentFile} />

<style lang="scss">
    :global(.filepond--file-status .filepond--file-status-sub) {
        display: none;
    }
</style>
