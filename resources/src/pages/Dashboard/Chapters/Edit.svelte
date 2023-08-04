<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { useForm, inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
    import record from '@/assets/img/chapter-record.jpg'
    import upload from '@/assets/img/chapter-upload.jpg'
    import write from '@/assets/img/chapter-type.jpg'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'

    export let chapter: { data: App.Chapter }

    let el: HTMLFormElement
    const form = useForm({
        title: chapter.data?.title ?? '',
        status: chapter.data.status,
    })

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                ...data,
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .patch(`/chapters/${chapter.data.id}`)
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={1} />

<form on:submit|preventDefault={submit}>
    <main class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
        <div class="card-body gap-4">
            <input
                class="input card-title input-ghost font-serif text-2xl text-primary"
                bind:value={$form.title}
            />
            <div class="grid grid-cols-1 gap-8 p-4 md:grid-cols-3">
                <a
                    class="card bg-neutral transition-transform hover:scale-105"
                    href="/chapters/{chapter.data.id}/record"
                    use:inertia
                >
                    <figure>
                        <img src={record} alt="record" />
                    </figure>
                    <div class="card-body">
                        <h2 class="card-title">Record your Answer</h2>
                    </div>
                </a>
                <a
                    class="card bg-neutral transition-transform hover:scale-105"
                    href="/chapters/{chapter.data.id}/upload"
                    use:inertia
                >
                    <figure>
                        <img src={upload} alt="upload" />
                    </figure>
                    <div class="card-body">
                        <h2 class="card-title">Upload File</h2>
                    </div>
                </a>
                <a
                    class="card bg-neutral transition-transform hover:scale-105"
                    href="/chapters/{chapter.data.id}/type"
                    use:inertia
                >
                    <figure>
                        <img src={write} alt="write" />
                    </figure>
                    <div class="card-body">
                        <h2 class="card-title">Type your Story</h2>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/stories/{chapter.data.story_id}/chapters"
            class="btn btn-neutral rounded-full pl-0"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            > Back
        </a>

        {#if $form.title != chapter.data.title}
            <button
                type="submit"
                class="btn btn-secondary rounded-full"
                data-status="draft"
            >
                Save
            </button>
        {/if}
    </section>
</form>
