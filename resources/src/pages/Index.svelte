<script context="module" lang="ts">
    export { default as layout } from '@/components/Layouts/Base.svelte'
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import Hero from '@/components/Static/Hero.svelte'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME}</title>
</svelte:head>

<Navbar />

<Hero />
