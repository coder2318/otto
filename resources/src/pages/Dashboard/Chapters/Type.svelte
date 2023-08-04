<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia, useForm } from '@inertiajs/svelte'
    import Breadcrumbs from '@/components/Chapters/Breadcrumbs.svelte'
    import Fa from 'svelte-fa'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'

    export let chapter: { data: App.Chapter }

    const form = useForm({
        content: chapter.data.content,
        status: chapter.data.status,
    })

    function submit(event: SubmitEvent) {
        $form
            .transform((data) => ({
                ...data,
                status: event.submitter.dataset?.status ?? data.status,
            }))
            .put(
                `/stories/${chapter.data.story_id}/chapters/${chapter.data.id}`
            )
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<Breadcrumbs step={2} />

<section class="container card m-4 mx-auto rounded-xl bg-base-300 px-4">
    <div class="card-body gap-4">
        <h1 class="card-title">{chapter.data.title}</h1>
    </div>
</section>

<form on:submit|preventDefault={submit}>
    <main class="container card m-4 mx-auto rounded-xl bg-neutral px-4">
        <div class="card-body gap-4">
            <div class="form-control">
                <div
                    class="textarea textarea-ghost min-h-[200px] font-serif"
                    contenteditable="true"
                    bind:innerHTML={$form.content}
                />
            </div>
        </div>
    </main>

    <section class="container mx-auto mb-8 flex justify-between">
        <a
            href="/stories/{chapter.data.story_id}/chapters/{chapter.data
                .id}/edit"
            class="btn btn-neutral rounded-full pl-0"
            use:inertia
        >
            <span class="badge mask badge-accent mask-circle p-4"
                ><Fa icon={faArrowLeft} /></span
            >
            Back
        </a>
        {#if $form.content != chapter.data.content}
            <button
                type="submit"
                class="btn btn-secondary rounded-full"
                data-status="draft"
            >
                Save it as Draft
            </button>
        {:else}
            <div class="flex gap-4">
                <a
                    class="btn btn-primary btn-outline btn-lg rounded-full"
                    href="/stories/{chapter.data.story_id}/chapters/{chapter
                        .data.id}/finish"
                >
                    Complete &<br /> Finish this Chapter
                </a>

                <a
                    class="btn btn-primary btn-lg rounded-full"
                    href="/stories/{chapter.data.story_id}/chapters/{chapter
                        .data.id}/enchance"
                >
                    Ask Otto AI to<br />Enchance the Writing
                </a>
            </div>
        {/if}
    </section>
</form>
