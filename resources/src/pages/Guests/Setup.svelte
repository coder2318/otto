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
        relarionship: '',
    })

    function submit() {}
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Guest Details</title>
</svelte:head>

<form on:submit|preventDefault={submit} in:fade>
    <main class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
        <div class="card-body gap-4">
            <div class="form-control">
                <label class="label" for="avatar">
                    <span class="label-text">Avatar</span>
                </label>
                <div class={$form.errors.avatar ? 'border border-error' : ''}>
                    <FilePond
                        maxFiles="1"
                        server={false}
                        storeAsFile={true}
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
                <label class="label" for="relarionship">
                    <span class="label-text">Relarionship</span>
                </label>
                <input
                    class="input input-bordered"
                    class:input-error={$form.errors.relarionship}
                    bind:value={$form.relarionship}
                    type="text"
                    name="relarionship"
                    placeholder="Enter Your Relarionship"
                />
                {#if $form.errors.relarionship}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.relarionship}
                    </span>
                {/if}
            </div>
        </div>
    </main>
</form>
