<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { page, inertia } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'
    import bg from '@/assets/img/profile-bg.jpg'
    import User from '@/components/SVG/user.svg.svelte'

    export let user: { data: App.User }
    export let stories: { data: App.Story[] }

    $: authId = $page.props?.auth?.user?.id
</script>

<svelte:head>
    <title
        >{import.meta.env.VITE_APP_NAME} - {user.data.details.first_name}
        {user.data.details.last_name}</title
    >
</svelte:head>

<main class="container mx-auto p-4" in:fade>
    <div class="card rounded-xl bg-neutral text-neutral-content">
        <figure class="max-h-[30rem]">
            <img src={bg} alt="background" />
        </figure>
        <div class="card-body -mt-20 block">
            <div class="float-left mr-8 flex max-w-[10rem] flex-col gap-4">
                <div class="avatar">
                    <div class="h-40 w-40 rounded-full border-8 border-neutral">
                        {#if user.data.avatar}
                            <img src={user.data.avatar} alt="avatar" />
                        {:else}
                            <User
                                class="bg-secondary"
                                pathClass="fill-secondary-content"
                            />
                        {/if}
                    </div>
                </div>
                {#if user.data.id === authId}
                    <a
                        href="/profile"
                        use:inertia
                        class="btn btn-primary btn-outline rounded-full"
                    >
                        Edit
                    </a>
                {/if}
            </div>
            <div class="pt-20">
                <h2 class="card-title text-3xl text-primary">
                    {user.data.details.first_name}
                    {user.data.details.last_name}
                </h2>
                <a href="mailto:{user.data.email}" class="link-hover link">
                    {user.data.email}
                </a>
                {#if user.data.details.bio}
                    <p>{@html user.data.details.bio}</p>
                {/if}
            </div>
        </div>
    </div>
</main>

<section class="container mx-auto my-8 flex flex-col gap-8 px-4" in:fade>
    <h1 class="text-4xl text-primary">View <i>OTTO Biography</i></h1>

    {#each stories.data as story}
        <div class="card border-4 border-base-300 bg-base-200">
            <div class="card-body flex-row items-center gap-4 p-4 md:p-8">
                <img
                    src={story.cover}
                    alt="cover"
                    class="float-left max-h-32 rounded-xl"
                />
                <div class="flex flex-col items-start gap-4">
                    <h2 class="card-title">{story.title}</h2>
                    <a
                        href="/stories/{story.id}"
                        use:inertia
                        class="btn btn-primary rounded-full px-8"
                    >
                        View Book
                    </a>
                </div>
            </div>
        </div>
    {/each}
</section>
