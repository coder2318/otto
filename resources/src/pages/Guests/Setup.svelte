<script context="module" lang="ts">
    import FilePond from '@/components/FilePond.svelte'
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'

    const form = useForm({
        avatar: undefined,
        relationship: '',
    })

    function submit() {
        $form.post(window.location.pathname)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Guest Details</title>
</svelte:head>

<form on:submit|preventDefault={submit} in:fade>
    <main class="container card m-4 mx-auto rounded-xl bg-base-200 px-4">
        <div class="card-body gap-4">
            <h1 class="card-title text-3xl font-normal text-primary">
                Guest Details
            </h1>
            <div class="form-control">
                <label class="label" for="avatar">
                    <span class="label-text">Profile Picture</span>
                </label>
                <div
                    class="rounded-lg border border-base-content/20 {$form
                        .errors.avatar
                        ? 'border-error'
                        : ''}"
                >
                    <FilePond
                        class="rounded-lg"
                        maxFiles="1"
                        server={false}
                        storeAsFile={true}
                        acceptedFileTypes={[
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ]}
                        name="avatar"
                        onaddfile={(err, data) => ($form.avatar = data.file)}
                    />
                </div>
                {#if $form.errors.avatar}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.avatar}
                    </span>
                {/if}
            </div>
            <div class="form-control">
                <label class="label" for="relationship">
                    <span class="label-text">Relationship</span>
                </label>
                <input
                    class="input input-bordered"
                    class:input-error={$form.errors.relationship}
                    bind:value={$form.relationship}
                    type="text"
                    name="relationship"
                    placeholder="Enter Your Relarionship"
                />
                {#if $form.errors.relationship}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.relationship}
                    </span>
                {/if}
            </div>
        </div>
    </main>

    {#if $form.isDirty}
        <section class="container m-4 mx-auto rounded-xl px-4">
            <div class="flex justify-end">
                <button
                    class="btn btn-primary rounded-full"
                    type="submit"
                    disabled={$form.processing}
                >
                    {#if $form.processing}
                        <span class="loading loading-spinner" />
                    {/if}
                    Save
                </button>
            </div>
        </section>
    {/if}
</form>
