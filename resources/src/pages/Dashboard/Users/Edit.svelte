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
    import type { FilePond as FilePondType, FilePondFile } from 'filepond'
    import editIcon from '@fortawesome/fontawesome-free/svgs/solid/pen-to-square.svg?raw'
    import { onMount } from 'svelte'
    import { createCropperForFilepond } from '@/service/cropper'
    import { imask } from '@imask/svelte'
    import dayjs from 'dayjs'

    export let user: { data: App.User }
    export let countries: App.Country[] = []
    export let languages: App.Language[] = []
    export let questions: { data: any[] }

    const form = useForm({
        avatar: undefined,
        name: user.data.name,
        email: user.data.email,
        details: {
            ...user.data.details,
            social: { ...user.data.details.social },
            birth_date: dayjs(user.data.details.birth_date).format(
                'DD/MM/YYYY'
            ),
        },
    })

    let element: HTMLElement
    let modal: HTMLDialogElement
    let pond: FilePondType
    let editor: any = null
    let initialFile: File
    let skipUpdate: boolean = false

    onMount(() => {
        editor = createCropperForFilepond(element, {
            aspectRatio: 1,
            viewMode: 2,
            autoCropArea: 1,
            background: false,
            autoCrop: true,
            ready: () => modal.showModal(),
        })

        fetch(user.data.avatar)
            .then(async (response) => {
                initialFile = new File(
                    [await response.blob()],
                    user.data.avatar.split('/').pop(),
                    {
                        type: response.headers.get('content-type'),
                    }
                )

                const interval = setInterval(() => {
                    if (!pond) return
                    clearInterval(interval)

                    // Remember a file so we do not upload it again
                    skipUpdate = true
                    pond.addFile(initialFile, { type: 'local' })
                })
            })
            .catch(() => {})
    })

    function reset() {
        $form.reset()
        pond.removeFiles()

        if (initialFile) {
            skipUpdate = true
            pond.addFile(initialFile, { type: 'local' })
        }
    }

    function addFile(file: FilePondFile, blob: Blob) {
        if (skipUpdate) {
            // On second edit same file can only be added only after edit, so we remove it to allow upload
            return (skipUpdate = false)
        }
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
        <div class="card-body gap-8">
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
                        bind:instance={pond}
                        server={false}
                        allowMultiple={false}
                        allowImagePreview={true}
                        acceptedFileTypes={['image/*']}
                        imageEditEditor={editor}
                        allowImageEdit={!!editor}
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

            <div class="form-control">
                <div class="font-serif text-3xl text-primary">Profile</div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-control">
                        <label class="label" for="full_name">
                            <span class="label-text">Full Name</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors['details.name']}
                            bind:value={$form.details.name}
                            type="text"
                            name="name"
                            placeholder="Full Name"
                        />
                        {#if $form.errors['details.full_name']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.full_name']}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="username">
                            <span class="label-text">User Name</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors.name}
                            bind:value={$form.name}
                            type="text"
                            name="username"
                            placeholder="User Name"
                        />
                        {#if $form.errors.name}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.name}
                            </span>
                        {/if}
                    </div>
                </div>
            </div>

            <div class="form-control">
                <div class="font-serif text-3xl text-primary">
                    Personal <i>Information</i>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-control">
                        <label class="label" for="email">
                            <span class="label-text">Email</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors.email}
                            bind:value={$form.email}
                            type="email"
                            name="email"
                            placeholder="Email"
                        />
                        {#if $form.errors.email}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors.email}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="phone">
                            <span class="label-text">Phone</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors['details.phone']}
                            bind:value={$form.details.phone}
                            type="tel"
                            name="phone"
                            placeholder="Phone"
                        />
                        {#if $form.errors['details.phone']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.phone']}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="language">
                            <span class="label-text">Language</span>
                        </label>
                        <select
                            class="select select-bordered"
                            class:select-error={$form.errors[
                                'details.language'
                            ]}
                            name="language"
                            bind:value={$form.details.language}
                        >
                            <option value={null} disabled selected>
                                Select Language...
                            </option>
                            {#each languages as language}
                                <option value={language.code}>
                                    {language.name}
                                </option>
                            {/each}
                        </select>
                        {#if $form.errors['details.language']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.language']}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="country">
                            <span class="label-text">Country</span>
                        </label>
                        <select
                            class="select select-bordered"
                            class:select-error={$form.errors['details.country']}
                            name="country"
                            bind:value={$form.details.country}
                        >
                            <option value={null} disabled selected
                                >Select Country...</option
                            >
                            {#each countries as country}
                                <option value={country.code}>
                                    {country.name}
                                </option>
                            {/each}
                        </select>
                        {#if $form.errors['details.country']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.country']}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control col-span-2">
                        <label class="label" for="bio">
                            <span class="label-text">Bio</span>
                        </label>
                        <textarea
                            class="textarea textarea-bordered"
                            class:textarea-error={$form.errors['details.bio']}
                            bind:value={$form.details.bio}
                            name="bio"
                            placeholder="Bio"
                        />
                        {#if $form.errors['details.bio']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.bio']}
                            </span>
                        {/if}
                    </div>
                </div>
            </div>

            <div class="form-control">
                <div class="font-serif text-3xl text-primary">
                    Onboarding <i>Information</i>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-control col-span-2">
                        <label class="label" for="birth_date">
                            <span class="label-text">Date of Birth</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors[
                                'details.birth_date'
                            ]}
                            bind:value={$form.details.birth_date}
                            use:imask={{ mask: '00/00/0000' }}
                            pattern="\d&lcub;1,2&rcub;/\d&lcub;1,2&rcub;/\d&lcub;4&rcub;"
                            placeholder="DD/MM/YYYY"
                            type="text"
                            name="birth_date"
                        />
                        {#if $form.errors['details.birth_date']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.birth_date']}
                            </span>
                        {/if}
                    </div>

                    {#each questions.data as question}
                        <div class="form-control">
                            <label class="label" for="question-{question.id}">
                                <span class="label-text">
                                    {question.question}
                                </span>
                            </label>
                            <select
                                class="select select-bordered"
                                class:select-error={$form.errors[
                                    `quiz.${question.id}`
                                ]}
                                bind:value={$form.details.quiz[question.id]}
                                name={`question.${question.id}`}
                            >
                                <option value={null} disabled
                                    >Select Answer...</option
                                >
                                {#each question.answers as answer}
                                    <option>{answer}</option>
                                {/each}
                            </select>
                            {#if $form.errors[`details.quiz.${question.id}`]}
                                <span
                                    class="label-text-alt mt-1 text-left text-error"
                                >
                                    {$form.errors[
                                        `details.quiz.${question.id}`
                                    ]}
                                </span>
                            {/if}
                        </div>
                    {/each}
                </div>
            </div>

            <div class="form-control">
                <div class="font-serif text-3xl text-primary">
                    Social <i>Links</i>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-control">
                        <label class="label" for="facebook">
                            <span class="label-text">Facebook</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors[
                                'details.social.facebook'
                            ]}
                            bind:value={$form.details.social.facebook}
                            type="text"
                            name="facebook"
                            placeholder="Facebook"
                        />
                        {#if $form.errors['details.social.facebook']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.social.facebook']}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="telegram">
                            <span class="label-text">Telegram</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors[
                                'details.social.telegram'
                            ]}
                            bind:value={$form.details.social.telegram}
                            type="text"
                            name="telegram"
                            placeholder="Telegram"
                        />
                        {#if $form.errors['details.social.telegram']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.social.telegram']}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="instagram">
                            <span class="label-text">Instagram</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors[
                                'details.social.instagram'
                            ]}
                            bind:value={$form.details.social.instagram}
                            type="text"
                            name="instagram"
                            placeholder="Instagram"
                        />
                        {#if $form.errors['details.social.instagram']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.social.instagram']}
                            </span>
                        {/if}
                    </div>
                    <div class="form-control">
                        <label class="label" for="linkedin">
                            <span class="label-text">LinkedIn</span>
                        </label>
                        <input
                            class="input input-bordered"
                            class:input-error={$form.errors[
                                'details.social.linkedin'
                            ]}
                            bind:value={$form.details.social.linkedin}
                            type="text"
                            name="linkedin"
                            placeholder="LinkedIn"
                        />
                        {#if $form.errors['details.social.linkedin']}
                            <span
                                class="label-text-alt mt-1 text-left text-error"
                            >
                                {$form.errors['details.social.linkedin']}
                            </span>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="mb-4 flex w-full items-center justify-end gap-4">
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
