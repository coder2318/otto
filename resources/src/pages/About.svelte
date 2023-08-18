<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import AboutContent from '@/components/Static/AboutContent.svelte'
    import AboutCta from '@/components/Static/AboutCta.svelte'
    import OurTeam from '@/components/Static/OurTeam.svelte'
    import Footer from '@/components/Static/Footer.svelte'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - About</title>
</svelte:head>

<Navbar />

<PageHeader />

<AboutContent />

<AboutCta />

<OurTeam />

<Footer />
