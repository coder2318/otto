<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { useForm } from '@inertiajs/svelte'
    import FilePond from '@/components/FilePond.svelte'

    export let story_types: { data: App.StoryType[] }

    $: console.log(story_types)

    const form = useForm({
        title: '',
        cover: '',
        story_type_id: null,
    })
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Create New Story</title>
</svelte:head>

<main class="container mx-auto flex flex-1 flex-col gap-8 p-4 py-8" in:fade>
    <form
        class="card bg-neutral shadow-sm"
        on:submit|preventDefault={() =>
            $form.post('/stories', { forceFormData: true })}
    >
        <div class="card-body gap-4">
            <h2 class="card-title text-3xl italic text-primary">
                Create New Story
            </h2>
            <div class="form-control">
                <label class="label" for="title">
                    <span class="label-text">Title</span>
                </label>
                <input
                    class="input input-bordered"
                    class:input-error={$form.errors.title}
                    bind:value={$form.title}
                    type="text"
                    name="title"
                    placeholder="Title"
                />
                {#if $form.errors.title}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.title}
                    </span>
                {/if}
            </div>
            <div class="form-control">
                <label class="label" for="cover">
                    <span class="label-text">Cover</span>
                </label>
                <FilePond
                    maxFiles="1"
                    server={false}
                    storeAsFile={true}
                    name="cover"
                    onaddfile={(err, data) => ($form.cover = data.file)}
                />
                {#if $form.errors.cover}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.cover}
                    </span>
                {/if}
            </div>
            <div class="form-control">
                <label class="label" for="story_type_id">
                    <span class="label-text">Story Type</span>
                </label>
                <select
                    class="select select-bordered"
                    class:select-error={$form.errors.story_type_id}
                    bind:value={$form.story_type_id}
                    name="story_type_id"
                >
                    <option value={null} selected disabled class="hidden">
                        Select Story Type
                    </option>
                    {#each story_types.data as story_type}
                        <option value={story_type.id}>
                            {story_type.name}
                        </option>
                    {/each}
                </select>
                {#if $form.errors.story_type_id}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.story_type_id}
                    </span>
                {/if}
            </div>
            <div class="card-actions justify-end">
                <button
                    class="btn btn-secondary"
                    class:disabled={$form.processing}
                    disabled={$form.processing}
                    type="submit"
                >
                    {#if $form.processing}
                        <span class="loading loading-spinner" />
                    {/if}
                    Save
                </button>
            </div>
        </div>
    </form>
</main>
