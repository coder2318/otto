<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia } from '@inertiajs/svelte'
    import bg from '@/assets/img/hero-bg.jpg'
</script>

<header
    class="hero min-h-screen items-end justify-items-start overflow-hidden rounded-b-[2.5rem]"
    style="background-image: url({bg})"
    in:fade
>
    <div class="hero-overlay bg-black bg-opacity-30" />
    <div class="hero-content p-8 md:p-16 lg:p-28">
        <div class="flex w-full flex-col items-start justify-center gap-4">
            <h1 class="lg::text-6xl text-3xl text-white sm:text-4xl md:text-5xl xl:text-7xl">
                Every life is a story. Begin <br />
                telling yours with
                <span class="italic text-secondary">OttoStory</span>.
            </h1>

            <a use:inertia href="/stories" class="btn btn-secondary rounded-full lg:h-20 lg:px-10 lg:text-xl">
                Start Writing
            </a>
        </div>
    </div>
</header>
