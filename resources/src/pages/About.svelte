<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import Navbar from '@/components/Navbar.svelte'
    import Hero from '@/components/Static/Hero.svelte'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - About</title>
</svelte:head>

<main class="flex min-h-screen flex-col items-center justify-center">
    <Navbar />
    <Hero />
</main>
