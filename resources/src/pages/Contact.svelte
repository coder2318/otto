<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import ContactForm from '@/components/Static/ContactForm.svelte'
    import ContactLinks from '@/components/Static/ContactLinks.svelte'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Contact</title>
</svelte:head>

<Navbar class="bg-primary text-primary-content" />

<PageHeader class="mb-16 mt-36 lg:mb-24 lg:mt-48">
    Get in <i>Touch</i>
</PageHeader>

<ContactForm />

<ContactLinks />

<Footer />
