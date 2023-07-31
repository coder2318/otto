<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Dashboard from '@/components/Layouts/Dashboard.svelte';
    export const layout = [Base, Dashboard];
</script>

<script lang="ts">
    import { fade, blur } from 'svelte/transition';
    import Fa from 'svelte-fa';
    import Paginator from '@/components/Paginator.svelte';
    import { inertia } from '@inertiajs/svelte';
    import { dayjs } from '@/service';
    import { faPlus } from '@fortawesome/free-solid-svg-icons';
    export let stories : { data: Array<App.Story>, links: App.PaginationLinks, meta: App.PaginationMeta };
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - My Stories</title>
</svelte:head>

<main class="container mx-auto flex-1 flex flex-col gap-8 p-4 py-8" in:fade>
    <div class="flex justify-between">
        <h1 class="text-3xl italic text-primary font-bold">My Stories</h1>
        <a href="/stories/create" use:inertia class="btn btn-secondary">
            <Fa icon={faPlus} /> Create a Story
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 flex-1 justify-center">
            {#each stories.data as story (story.id)}
                    <a class="card bg-neutral hover:scale-105 transition-transform" href="/stories/{story.id}" use:inertia in:fade>
                        <figure>
                            <img src="{story.cover}" alt="{story.title}" />
                        </figure>
                        <div class="card-body">
                            <h2 class="card-title">{story.title}</h2>
                            <p/>
                            <div class="card-actions justify-between">
                                <div>{dayjs(story.created_at).format('MMM DD, YYYY')}</div>
                                <div class="badge badge-outline">{story.status}</div>
                            </div>
                        </div>
                    </a>
            {:else}
            <a href="/stories/create" use:inertia class="card hover:scale-105 transition-transform border-neutral border-dashed border-4">
                <div class="card-body items-center justify-center text-neutral-content">
                    <Fa icon={faPlus} class="text-6xl" />
                    <h2 class="card-title">Create your first story</h2>
                </div>
            </a>
            {/each}
    </div>

    <Paginator meta={stories.meta} class="mx-auto" buttonClass="btn-secondary" activeClass="btn-secondary btn-active" />
</main>
