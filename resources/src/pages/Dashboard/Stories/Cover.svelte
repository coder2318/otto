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
    import { fade } from 'svelte/transition'
    import editIcon from '@fortawesome/fontawesome-free/svgs/solid/pen-to-square.svg?raw'
    import { fileToBase64, groupBy } from '@/service/helpers'
    import bookCoverIllustration1 from '@/assets/img/book-cover-illustration-1.svg'
    import bookCoverIllustration2 from '@/assets/img/book-cover-illustration-2.svg'
    import BtnArrow from '@/components/SVG/btn-arrow.svg.svelte'
    import { faArrowRight } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'
    import Cropper from 'svelte-easy-crop'
    import getCroppedImg from '@/util/canvasUtils'
    import Range from '@/components/Chapters/Range.svelte'
    import { flash } from '@/components/Toast.svelte'
    import FontSelector from '@/components/FontSelector.svelte'
    import { BOOK_COVER_EXCLUDED_FIELDS } from '@/app.constants'
    import { BookCoverTypes } from '@/types/app'
    import { pickBy } from 'lodash'

    export let story: { data: App.Story }
    export let templateData: { data: App.BookCoverTemplate }
    export let fonts: App.Font[]
    export let templateType: any
    export let templateId: any

    let image = ''
    let aspect = 0.6622621448029057
    let pixelCrop
    let crop = { x: 0, y: 0 }
    let zoom = 1

    const defaultTemplateType = templateType === BookCoverTypes.DEFAULT
    const isFontSize = {
        authorSize: 'authorSize',
        titleSize: 'titleSize',
        descriptionTextSize: 'descriptionTextSize',
        subtitleSize: 'subtitleSize',
        spineTextSize: 'spineTextSize',
    }

    $: grouped = Object.entries(groupBy(templateData.data.template.fields, 'group')) as [
        string,
        Array<{
            name: string
            type: string
            key: string
        }>,
    ][]

    let reloadCover = false
    let changed = false
    let modal: HTMLDialogElement
    let builder: BookCoverBuilder
    let imageKey: string
    let shared = {}
    let parameters = createParameters() ?? {}
    let hiddenParams = {} as any
    let loading: boolean = false

    function createParameters() {
        const fields = templateData.data.template.fields.filter(({ key }) => !BOOK_COVER_EXCLUDED_FIELDS.includes(key))
        const isDefaultTemplate = templateId && templateType === BookCoverTypes.DEFAULT
        const textFields = templateData.data.template.fields
            .filter((field) => field.type === 'text')
            .map((field) => field.key)

        const activeParameters = templateData.data.parameters || {}

        if (!activeParameters?.user_template_id) {
            changed = true
        }

        const currentParameters = isDefaultTemplate
            ? pickBy(activeParameters, (_, key) => textFields.includes(key))
            : activeParameters

        const preparedParameters: { [key: string]: any } = {
            ...currentParameters,
            ...fields.reduce((acc, field) => {
                const { key, defaultValue } = field

                acc[key] = currentParameters?.[key] || defaultValue

                return acc
            }, {}),
        }

        if (isDefaultTemplate) {
            preparedParameters.template_id = templateId
            preparedParameters.user_template_id = null
        } else if (templateType === BookCoverTypes.USER) {
            preparedParameters.template_id = templateData.data.template_id
            preparedParameters.user_template_id = templateId || currentParameters.user_template_id
        }

        preparedParameters.template_type = templateType
        shared = preparedParameters

        return preparedParameters
    }

    function canvelEdit() {
        modal.close()
        pixelCrop = null
        crop = { x: 0, y: 0 }
        zoom = 1
    }

    async function createImageBlob(url): Promise<Blob> {
        let response = await fetch(url)
        let data = await response.blob()
        return data
    }

    async function cropImage() {
        const croppedImage = await getCroppedImg(image, pixelCrop)
        const newBlob = await createImageBlob(croppedImage)

        parameters[imageKey] = await fileToBase64(newBlob)

        hiddenParams[imageKey] = new File([newBlob], 'Cover-Background', {
            type: newBlob.type,
            lastModified: Date.now(),
        })
    }

    function saveImage() {
        cropImage()
        modal.close()
    }

    async function prepareFile(key: string, blob: File) {
        modal.show()

        image = await fileToBase64(blob)
        imageKey = key
    }

    async function submit(options?: { onlySave?: boolean; saveAsNewUserTemplate?: boolean }) {
        if (loading) return

        const { onlySave, saveAsNewUserTemplate } = options || {}
        loading = true

        try {
            const svg = await builder.getSVG()

            svg.querySelectorAll('text').forEach((item) => {
                const key = item.className.baseVal

                if (key) {
                    parameters[`${key}Position`] = {
                        x: item.x.baseVal[0].value,
                        y: item.y.baseVal[0].value,
                    }
                }
            })

            svg.querySelectorAll('foreignObject').forEach((item) => {
                const key = item.className.baseVal

                if (key) {
                    parameters[`${key}Position`] = {
                        x: item.getAttribute('x'),
                        y: item.getAttribute('y'),
                    }
                }
            })

            const { file } = await builder.getFile()

            router.post(
                `/stories/${story.data.id}`,
                {
                    cover: file,
                    saveAsNewUserTemplate,
                    meta: {
                        ...parameters,
                        ...hiddenParams,
                    },
                    _method: 'PUT',
                    redirect: onlySave ? 'dashboard.stories.cover' : 'dashboard.stories.edit',
                },
                {
                    preserveScroll: onlySave,
                    forceFormData: true,
                    onSuccess: () => {
                        changed = false
                        parameters = createParameters()
                        hiddenParams = {}
                    },
                    onFinish: () => {
                        loading = false
                        reloadCover = true
                    },
                }
            )
        } catch (error) {
            console.error(error)
            flash({
                message: error?.message || 'Failed to update cover',
                type: 'alert-error',
                autohide: true,
            })
            loading = false
        }
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<section class="container mx-auto px-4" in:fade>
    <Breadcrumbs step={1} {story} />
</section>

<form on:submit|preventDefault={() => submit()} in:fade id="book-cover">
    <section class="bookCover">
        <div class="container mx-auto">
            <div class="wrap">
                <div class="bookCover__block">
                    <img class="bookCover-illustration-1" src={bookCoverIllustration1} alt="Illustration" />
                    <img class="bookCover-illustration-2" src={bookCoverIllustration2} alt="Illustration" />
                    <div class="flex flex-col gap-4">
                        {#each grouped as [group, fields], index (group)}
                            {#if group}
                                <div class="collapse border border-base-content/60 bg-base-100">
                                    <input type="radio" name="group" checked={index === 0} />
                                    <div class="collapse-title text-xl font-medium">
                                        {group}
                                    </div>
                                    <div class="collapse-content">
                                        {#each fields as field}
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
                                                {:else if isFontSize[field.key]}
                                                    <Range inputName={field.key} bind:values={parameters[field.key]} />
                                                {:else if field.type === 'number'}
                                                    <input
                                                        class="input input-bordered"
                                                        bind:value={parameters[field.key]}
                                                        type="number"
                                                        name={field.key}
                                                        placeholder={field.name}
                                                    />
                                                {:else if field.type === 'font'}
                                                    <FontSelector
                                                        {fonts}
                                                        bind:value={parameters[field.key]}
                                                        on:change={(event) => {
                                                            parameters[field.key] = event.detail.value
                                                        }}
                                                        name={field.key}
                                                        placeholder=""
                                                        labelText=""
                                                    />
                                                {:else if field.type === 'color'}
                                                    <label
                                                        for={field.key}
                                                        class="h-12 w-full cursor-pointer rounded-lg shadow-sm"
                                                        style={`background: ${parameters[field.key] || '#000'}`}
                                                    >
                                                        <input
                                                            id={field.key}
                                                            class="input input-bordered absolute w-0 opacity-0"
                                                            bind:value={parameters[field.key]}
                                                            type="color"
                                                            name={field.key}
                                                            placeholder={field.name}
                                                        />
                                                    </label>
                                                {:else if field.type === 'image'}
                                                    <div class="">
                                                        <FilePond
                                                            name={field.key}
                                                            acceptedFileTypes={[
                                                                'image/jpeg',
                                                                'image/webp',
                                                                'image/png',
                                                            ]}
                                                            onpreparefile={async (file, blob) =>
                                                                prepareFile(field.key, blob)}
                                                            server={false}
                                                            onremovefile={() =>
                                                                (parameters[field.key] =
                                                                    '/build/assets/transparent.png')}
                                                            allowMultiple={false}
                                                            styleImageEditButtonEditItemPosition="top right"
                                                            imageEditIconEdit={`<div class="flex p-1.5 fill-neutral">${editIcon}</div>`}
                                                        />
                                                    </div>
                                                {/if}
                                            </div>
                                        {/each}
                                    </div>
                                </div>
                            {:else}
                                {#each fields as field}
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
                                        {:else if field.type === 'image'}
                                            <div class="">
                                                <FilePond
                                                    name={field.key}
                                                    acceptedFileTypes={['image/jpeg', 'image/webp', 'image/png']}
                                                    onpreparefile={async (file, blob) => prepareFile(field.key, blob)}
                                                    server={false}
                                                    onremovefile={() =>
                                                        (parameters[field.key] = '/build/assets/transparent.png')}
                                                    allowMultiple={false}
                                                    styleImageEditButtonEditItemPosition="top right"
                                                    imageEditIconEdit={`<div class="flex p-1.5 fill-neutral">${editIcon}</div>`}
                                                />
                                            </div>
                                        {/if}
                                    </div>
                                {/each}
                            {/if}
                        {/each}
                    </div>
                </div>
                <div class="bookCover__cover sticky top-32 flex h-fit items-center justify-center">
                    <BookCoverBuilder
                        bind:this={builder}
                        class="select-none"
                        pages={story.data.pages ?? 0}
                        {fonts}
                        {shared}
                        {parameters}
                        template={templateData.data.template}
                        change={() => (changed = true)}
                        bind:reload={reloadCover}
                    />
                </div>
            </div>

            <div class="bookCover__buttons px-4 md:px-0">
                <a href="/stories/{story.data.id}" class="otto-btn-with-arrow-secondary !w-full md:!w-fit" use:inertia>
                    <span class="icon">
                        <BtnArrow />
                    </span>
                    <p>Back</p>
                </a>
                <div class="flex w-full flex-col gap-4 md:flex-row md:justify-end">
                    <a href="/stories/{story.data.id}/covers" use:inertia class="btn btn-primary rounded-full">
                        More Covers
                    </a>
                    {#if changed || templateId}
                        <button
                            class="btn btn-secondary rounded-full"
                            disabled={loading}
                            type="button"
                            on:click={() => submit({ onlySave: true, saveAsNewUserTemplate: true })}
                        >
                            {#if loading}<span class="loading loading-spinner"></span>{/if}
                            <span>Save As New User cover</span>
                        </button>
                        {#if !defaultTemplateType}
                            <button
                                class="btn btn-secondary rounded-full"
                                disabled={loading}
                                type="button"
                                on:click={() => submit({ onlySave: true })}
                            >
                                {#if loading}<span class="loading loading-spinner"></span>{/if}
                                <span>Update</span>
                            </button>
                        {/if}
                        <button class="btn btn-secondary rounded-full pr-0" disabled={loading} type="submit">
                            {#if loading}<span class="loading loading-spinner"></span>{/if}
                            <span>Save & Next</span>
                            <span class="badge mask badge-neutral mask-circle p-4"><Fa icon={faArrowRight} /></span>
                        </button>
                    {:else}
                        <a href="/stories/{story.data.id}/edit" class="btn btn-secondary rounded-full" use:inertia>
                            Continue
                        </a>
                    {/if}
                </div>
            </div>
        </div>
    </section>
</form>

<dialog bind:this={modal} class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <div class="min-h-[500px] [&_.container]:mb-24 [&_.container]:min-h-[500px]">
            <Cropper {image} {aspect} bind:crop bind:zoom on:cropcomplete={(e) => (pixelCrop = e.detail.pixels)} />
        </div>
        <div class="modal-action">
            <button type="button" class="btn btn-primary" on:click|preventDefault={saveImage}>Confirm</button>
            <button type="button" class="btn" on:click|preventDefault={canvelEdit}>Close</button>
        </div>
    </div>
</dialog>

<style lang="scss">
    .bookCover {
        padding-bottom: 100px;

        &-illustration-1 {
            position: absolute;
            right: 0px;
            top: 0px;
        }

        &-illustration-2 {
            position: absolute;
            left: 0;
            bottom: 0;
        }

        .wrap {
            display: flex;
            width: 100%;
            margin-bottom: 34px;
        }

        &__block {
            width: 100%;
            background: #eae4dc;
            border-radius: 24px;
            max-width: 516px;
            margin-right: 24px;
            padding: 70px 50px 90px;
            position: relative;
            overflow: hidden;

            .form-control {
                margin-bottom: 25px;

                &:last-child {
                    margin-bottom: 0;
                }

                .label {
                    padding: 0;
                    &-text {
                        font-size: 16px;
                        color: #333;
                        line-height: 1;
                        margin-bottom: 6px;
                    }
                }

                :global(.filepond--drop-label) {
                    background-color: transparent;
                    border: 1px dashed #4d4d4d;
                }

                input {
                    height: 60px;
                    font-size: 16px;
                    background: #fff;
                    color: #666666;
                    border: 1px solid #c8b69d;

                    &::placeholder {
                        color: #c8b69d;
                    }

                    &:focus {
                        outline: none;
                    }
                }

                textarea {
                    background-color: #fff;
                    border: 1px solid #c8b69d;
                    min-height: 60px;
                    box-shadow: 0 2px 2px #dbd1c2;
                    border-radius: 8px;
                    padding-top: 16px;
                    &:focus {
                        outline: none;
                    }
                    &::placeholder {
                        color: #c8b69d;
                    }
                }
            }
        }

        &__cover {
            border: 3px solid #eae4dc;
            border-radius: 24px;
            width: 100%;
            padding: 60px 120px;
        }

        &__buttons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
    }

    @media (max-width: 1199px) {
        .bookCover {
            &__block {
                padding: 70px 20px 90px;
            }

            &__cover {
                padding: 60px 50px;
            }
        }
    }
    @media (max-width: 767px) {
        .bookCover {
            .wrap {
                flex-direction: column;
            }
            &__buttons {
                flex-direction: column;
                align-items: flex-start;

                .otto-btn-with-arrow-secondary {
                    margin-top: 20px;
                    order: 2;
                }
            }
        }
    }
</style>
