<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { useForm, inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import FilePond from '@/components/FilePond.svelte'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'

    export let timelines: { data: App.Timeline[] }
    export let story: { data: App.Story }

    let el: HTMLFormElement
    const form = useForm({
        title: '',
        timeline_id: null,
        cover: null,
    })

    function submit() {
        $form.clearErrors()
        $form.post(`/stories/${story.data.id}/chapters`, {
            forceFormData: true,
        })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Create New Chapter</title>
</svelte:head>

<Breadcrumbs step={1} />

<form on:submit|preventDefault={submit} bind:this={el}>
    <main class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
        <div class="card-body gap-4">
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
                <label class="label" for="timeline_id">
                    <span class="label-text">Timeline</span>
                </label>
                <select
                    bind:value={$form.timeline_id}
                    class:select-error={$form.errors.timeline_id}
                    class="select select-bordered"
                    name="timeline_id"
                >
                    <option value={null} disabled>Select Timeline...</option>
                    {#each timelines.data as timeline}
                        <option value={timeline.id}>{timeline.title}</option>
                    {/each}
                </select>
                {#if $form.errors.timeline_id}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.timeline_id}
                    </span>
                {/if}
            </div>
            <div class="form-control">
                <label class="label" for="cover">
                    <span class="label-text">Cover</span>
                </label>
                <div class={$form.errors.cover ? 'border border-error' : ''}>
                    <FilePond
                        maxFiles="1"
                        storeAsFile={true}
                        name="cover"
                        onaddfile={(err, data) => ($form.cover = data.file)}
                    />
                </div>
                {#if $form.errors.cover}
                    <span class="label-text-alt mt-1 text-left text-error">
                        {$form.errors.cover}
                    </span>
                {/if}
            </div>
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/stories/{story.data.id}/chapters"
            class="btn btn-neutral rounded-full pl-0"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            > Back
        </a>

        {#if $form.isDirty}
            <button
                type="submit"
                class="btn btn-secondary rounded-full"
                disabled={$form.processing}
            >
                {#if $form.processing}
                    <span class="loading loading-spinner" />
                {/if} Save
            </button>
        {/if}
    </section>
</form>
