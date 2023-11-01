<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import PrivacyPolicy from '@/assets/md/privacy.md'
    import titleWithIllustration from '@/assets/img/title-with-illustration.svg'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Privacy Policy</title>
</svelte:head>

<Navbar class="relative bg-primary text-primary-content" />

<main>
    <section class="dArticle">
        <div class="otto-container">
            <div class="articleTop">
                <h1 class="fz_h2 title">
                    Our <i>Commitment</i> to <i>Privacy</i>
                </h1>
                <img class="articleTop-illustration" src={titleWithIllustration} alt="Illustration" />
            </div>
            <div class="content">
                <PrivacyPolicy />
            </div>
        </div>
    </section>
</main>

<Footer />

<style lang="scss">
</style>
