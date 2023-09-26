<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import User from '@/components/SVG/user.svg.svelte'

    export let chapter: { data: App.Chapter }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {chapter.data.title}</title>
</svelte:head>

<main
    class="container mx-auto flex min-h-[calc(100vh-67px)] flex-col gap-4 p-4 py-8"
>
    <div class="flex flex-col justify-between gap-y-4 md:flex-row">
        <h1 class="text-4xl text-primary">
            View <i>Response</i>
        </h1>
    </div>

    <section
        class="container card m-4 mx-auto rounded-xl bg-base-200 px-4"
        in:fade
    >
        <div class="card-body gap-4">
            <div
                class="card-title font-serif text-2xl font-normal italic text-primary md:text-3xl lg:text-4xl"
            >
                {chapter.data.title}
            </div>
            <div class="flex items-center gap-4">
                <div class="avatar">
                    <div class="w-20 rounded-full">
                        {#if chapter.data.guest.avatar}
                            <img
                                src={chapter.data.guest.avatar}
                                alt={chapter.data.guest.name}
                            />
                        {:else}
                            <User
                                class="bg-secondary"
                                pathClass="fill-secondary-content"
                            />
                        {/if}
                    </div>
                </div>
                <div>
                    <h2 class="card-title text-3xl text-primary">
                        {chapter.data.guest.name}
                    </h2>
                    {#if chapter.data?.guest?.details?.relationship}
                        <p class="text-base-content/80">
                            {chapter.data.guest.details.relationship}
                        </p>
                    {/if}
                </div>
            </div>
        </div>
    </section>

    <section class="container card mx-auto mb-8 flex justify-end bg-neutral">
        <div class="card-body whitespace-pre-wrap">
            {#if chapter.data.content}
                {@html chapter.data.content}
            {:else}
                <p class="text-base-content/40">No content</p>
            {/if}
        </div>
    </section>
</main>
