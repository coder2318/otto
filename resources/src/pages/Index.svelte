<script context="module" lang="ts">
    export { default as layout } from '@/components/Layouts/Base.svelte'
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import Hero from '@/components/Static/Hero.svelte'
    import StackCarousel from '@/components/Static/StackCarousel.svelte'
    import Focus from '@/components/Layouts/Focus.svelte'
    import TwoColumns from '@/components/Static/TwoColumns.svelte'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME}</title>
</svelte:head>

<Navbar />

<Hero />

<StackCarousel />

<Focus>
    <div class="flex w-full items-center justify-center">
        <h6
            class="text-center text-xl text-primary sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl"
        >
            Wave goodbye to expensive fees and embrace the chance to author your
            life story.
        </h6>
    </div>
</Focus>

<TwoColumns />
