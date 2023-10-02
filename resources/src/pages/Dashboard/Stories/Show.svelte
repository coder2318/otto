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
    <h1 class="relative z-20 text-center text-4xl text-primary lg:text-5xl">
        <svg
            class="absolute top-8 -z-10 -translate-y-1/2 sm:-left-4 sm:top-1/2"
            xmlns="http://www.w3.org/2000/svg"
            width="75"
            height="76"
            viewBox="0 0 75 76"
            fill="none"
        >
            <path
                d="M52.1743 4.29486C1.93868 42.2423 0.891336 41.7166 0.179599 46.665C-2.00782 61.8312 42.9614 95.7636 66.2895 57.5354C77.8701 38.5541 76.0808 24.4206 68.6329 6.39384C65.2065 -1.89722 57.822 0.0174307 52.177 4.2832L52.1743 4.29486Z"
                fill="#A9D392"
            />
        </svg>
        Crafting Your Narrative: <i>{story.data.title}</i>
    </h1>

    <div class="grid grid-cols-2 gap-4 lg:gap-8">
        <a
            use:inertia
            href="/stories/{story.data.id}/chapters"
            class="card bg-neutral transition-transform hover:scale-105"
        >
            <div class="card-body items-center gap-4 p-4 md:p-8">
                <figure class="rounded-lg">
                    <img src={writing} alt="writing room" />
                </figure>
                <h2
                    class="card-title text-3xl font-normal text-primary md:text-4xl"
                >
                    Writing <i>Room</i>
                </h2>
            </div>
        </a>
        <a
            use:inertia
            href="/stories/{story.data.id}/edit"
            class="card bg-neutral transition-transform hover:scale-105"
        >
            <div class="card-body items-center gap-4 p-4 md:p-8">
                <figure class="rounded-lg">
                    <img src={editing} alt="editing room" />
                </figure>
                <h2
                    class="card-title text-3xl font-normal text-primary md:text-4xl"
                >
                    Editing <i>Room</i>
                </h2>
            </div>
        </a>
    </div>
</main>
