<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia } from '@inertiajs/svelte'
    import writing from '@/assets/img/dashboard-writing.jpg'
    import editing from '@/assets/img/dashboard-editing.jpg'
</script>

<svelte:head>
    <title
        >{import.meta.env.VITE_APP_NAME} - Shaping the Story of Your Journey</title
    >
</svelte:head>

<main
    class="container mx-auto flex flex-1 flex-col items-center justify-center gap-8 p-4 py-8"
    in:fade
>
    <h1 class="text-center text-2xl text-primary lg:text-4xl">
        Crafting Your Narrative:<br /><i>Shaping the Story of Your Journey</i>
    </h1>

    <div class="grid grid-cols-2 gap-4 lg:gap-8">
        <a
            use:inertia
            href="/stories"
            class="card bg-neutral transition-transform hover:scale-105"
        >
            <figure><img src={writing} alt="writing room" /></figure>
            <div class="card-body items-center">
                <h2 class="card-title md:text-2xl">Writing Room</h2>
            </div>
        </a>
        <a
            use:inertia
            href="/editing"
            class="card bg-neutral transition-transform hover:scale-105"
        >
            <figure><img src={editing} alt="editing room" /></figure>
            <div class="card-body items-center">
                <h2 class="card-title md:text-2xl">Editing Room</h2>
            </div>
        </a>
    </div>
</main>
