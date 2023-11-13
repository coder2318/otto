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
    import { createCropperForFilepond } from '@/service/cropper'
    import { fade } from 'svelte/transition'
    import { onMount } from 'svelte'
    import editIcon from '@fortawesome/fontawesome-free/svgs/solid/pen-to-square.svg?raw'
    import { fileToBase64, groupBy, listFonts } from '@/service/helpers'
    import bookCoverIllustration1 from '@/assets/img/book-cover-illustration-1.svg'
    import bookCoverIllustration2 from '@/assets/img/book-cover-illustration-2.svg'
    import BtnArrow from '@/components/SVG/btn-arrow.svg.svelte'

    export let story: { data: App.Story }
    export let template: { data: App.BookCoverTemplate }

    $: grouped = Object.entries(groupBy(template.data.fields, 'group')) as [
        string,
        Array<{
            name: string
            type: string
            key: string
        }>,
    ][]

    let element: HTMLElement, modal: HTMLDialogElement, builder: BookCoverBuilder, editor: any

    let parameters = {} as any

    let editing: boolean = !story.data.cover

    let loading: boolean = false

    let fonts = []

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

        listFonts().then(
            (list) =>
                (fonts = list
                    .map((font) => font.family)
                    .filter((value, index, array) => array.indexOf(value) === index))
        )

        return () => {
            editor.clear()
            editor = null
        }
    })

    function canvelEdit() {
        modal.close()
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
    <section class="bookCover">
        <div class="container mx-auto">
            <div class="wrap">
                <div class="bookCover__block">
                    <img class="bookCover-illustration-1" src={bookCoverIllustration1} alt="Illustration" />
                    <img class="bookCover-illustration-2" src={bookCoverIllustration2} alt="Illustration" />
                    <div class="flex flex-col gap-4">
                        {#if editing}
                            {#each grouped as [group, fields] (group)}
                                {#if group}
                                    <div class="collapse border border-base-content/60 bg-base-100">
                                        <input type="radio" name="group" />
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
                                                    {:else if field.type === 'number'}
                                                        <input
                                                            class="input input-bordered"
                                                            bind:value={parameters[field.key]}
                                                            type="number"
                                                            name={field.key}
                                                            placeholder={field.name}
                                                        />
                                                    {:else if field.type === 'font'}
                                                        <select
                                                            class="select select-bordered"
                                                            bind:value={parameters[field.key]}
                                                            name={field.key}
                                                        >
                                                            {#each fonts as font}
                                                                <option value={font}>{font}</option>
                                                            {/each}
                                                        </select>
                                                    {:else if field.type === 'color'}
                                                        <input
                                                            class="input input-bordered w-full"
                                                            bind:value={parameters[field.key]}
                                                            type="color"
                                                            name={field.key}
                                                            placeholder={field.name}
                                                        />
                                                    {:else if field.type === 'image' && editor}
                                                        <div class="">
                                                            <FilePond
                                                                name={field.key}
                                                                server={false}
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
                                            {:else if field.type === 'image' && editor}
                                                <div class="">
                                                    <FilePond
                                                        name={field.key}
                                                        server={false}
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
                                {/if}
                            {/each}
                        {:else}
                            <div class="bookCover__block_buttons">
                                <button
                                    type="button"
                                    class="otto-btn-primary otto-btn"
                                    on:click={() => (editing = true)}
                                >
                                    Change Cover
                                </button>
                                <a use:inertia href="/stories/{story.data.id}/order" class="otto-btn-outline otto-btn">
                                    Order Book
                                </a>
                            </div>
                        {/if}
                    </div>
                </div>
                <div class="bookCover__cover flex items-center justify-center">
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

            <div class="bookCover__buttons">
                <a href="/stories/{story.data.id}" class="otto-btn-with-arrow-secondary" use:inertia>
                    <span class="icon">
                        <BtnArrow />
                    </span>
                    <p>Back</p>
                </a>
                {#if editing}
                    <div class="flex">
                        <a href="/stories/{story.data.id}/covers" use:inertia class="otto-btn-primary"> More Covers </a>
                        <button class="otto-btn-with-arrow" disabled={loading} type="submit">
                            {#if loading}<span class="loading loading-spinner"></span>{/if}
                            <p>Save & Next</p>
                            <span class="icon">
                                <BtnArrow />
                            </span>
                        </button>
                    </div>
                {:else}
                    <a href="/stories/{story.data.id}/order" class="btn btn-secondary rounded-full" use:inertia>
                        Continue
                    </a>
                {/if}
            </div>
        </div>
    </section>
</form>

<dialog bind:this={modal} class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <div bind:this={element} class="min-h-[500px]" />
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

            &_buttons {
                display: flex;
                flex-direction: column;
                width: 100%;
                z-index: 2;
                padding-top: 60px;
                .otto-btn {
                    width: 100%;
                    margin-bottom: 20px;
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

            .otto-btn-primary {
                height: 54px;
                margin-right: 24px;
                padding: 0 30px;
            }
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
