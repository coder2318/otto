<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    import Breadcrumbs from '@/components/Stories/Breadcrumbs.svelte'
    import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    export let story: { data: App.Story }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<section
    class="container mx-auto flex min-h-[calc(100vh-66px)] flex-col items-stretch px-4 pb-4"
>
    <Breadcrumbs step={2} {story} />
    <h1 class="my-4 text-3xl text-primary">
        Book Preview - <i>{story.data.title}</i>
    </h1>
    <embed
        src="/stories/{story.data.id}/book"
        width="100%"
        class="flex-1 bg-base-300"
        type="application/pdf"
    />
    <div class="mt-4 flex items-center justify-between">
        <a
            href="/stories/{story.data.id}/contents"
            use:inertia
            class="btn btn-neutral rounded-full pl-0"
        >
            <span class="badge mask badge-accent mask-circle p-4">
                <Fa icon={faArrowLeft} />
            </span>
            Go Back
        </a>
        <a
            href="/stories/{story.data.id}/order"
            use:inertia
            class="btn btn-primary rounded-full"
        >
            Finalize Your Order
        </a>
    </div>
</section>
