<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import PrivacyPolicy from '@/assets/md/privacy.md'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Privacy Policy</title>
</svelte:head>

<Navbar class="bg-primary text-primary-content" />

<PageHeader class="container relative mx-auto mb-16 mt-36 flex px-4 text-primary lg:mb-24 lg:mt-48">
    Our <i>Commitment</i> to <i>Privacy</i>
</PageHeader>

<main class="!container prose mx-auto mb-16 px-4 lg:prose-lg xl:prose-xl prose-h1:mt-16 prose-h1:text-primary">
    <PrivacyPolicy />
</main>

<Footer />
