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
    <div class="hero-content pb-16 pl-4 md:pb-16 md:pl-16 lg:pb-28 lg:pl-28">
        <div class="flex w-full flex-col items-start justify-center">
            <h1 class="fz_h1 mb-12 text-white">
                Every life is a story. Begin
                <br class="hidden sm:inline" />
                telling
                <span class="italic text-secondary">yours with Otto</span>.
            </h1>

            <a use:inertia href="/stories" class="btn btn-secondary h-16 rounded-full px-10 text-xl"> Start Writing </a>
        </div>
    </div>
</header>
