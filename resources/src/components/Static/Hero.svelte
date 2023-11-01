<script lang="ts">
    import { fade } from 'svelte/transition'
    import { inertia, page } from '@inertiajs/svelte'
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
                <span class="italic text-secondary">yours with OttoStory</span>.
            </h1>

            {#if $page.props?.auth?.user?.data?.subscribed}
                <a use:inertia href="/stories" class="otto-btn-secondary">Start Writing</a>
            {:else}
                <span class="flex gap-4">
                    <a use:inertia href="/plans" class="otto-btn-secondary">Start Your Book</a>
                    <a use:inertia href="/demo" class="otto-btn-primary">Try A Free Chapter</a>
                </span>
            {/if}
        </div>
    </div>
</header>

<style lang="scss">
    .hero {
        .fz_h1 {
            color: #fff;
        }
    }
</style>
