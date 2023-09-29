<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import writing from '@/assets/img/dashboard-writing.jpg'
    import editing from '@/assets/img/dashboard-editing.jpg'
    export let story: { data: App.Story }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - {story.data.title}</title>
</svelte:head>

<main
    class="container mx-auto flex flex-1 flex-col items-center justify-center gap-8 p-4 py-8"
>
    <div class="relative">
        <div
            class="absolute left-[70px] top-[10px] z-10 z-[1] h-[46px] w-[72px] rotate-[-35deg] rounded-t-[10px] rounded-bl-[8000px] rounded-br-full rounded-tl-[3000px] rounded-tr-[3000px] bg-[#A9D392]"
        ></div>
        <h1 class="relative z-20 text-center text-[44px] text-primary">
            Crafting Your Narrative: <i>{story.data.title}</i>
        </h1>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:gap-8">
        <a
            use:inertia
            href="/stories/{story.data.id}/chapters"
            class="card bg-neutral transition-transform hover:scale-105"
        >
            <figure class="m-3 rounded-lg">
                <img src={writing} alt="writing room" />
            </figure>
            <div class="card-body items-center">
                <h2 class="card-title font-normal text-primary md:text-3xl">
                    Writing <i>Room</i>
                </h2>
            </div>
        </a>
        <a
            use:inertia
            href="/stories/{story.data.id}/edit"
            class="card bg-neutral transition-transform hover:scale-105"
        >
            <figure class="m-3 rounded-lg">
                <img src={editing} alt="editing room" />
            </figure>
            <div class="card-body items-center">
                <h2 class="card-title font-normal text-primary md:text-3xl">
                    Editing <i>Room</i>
                </h2>
            </div>
        </a>
    </div>
</main>
