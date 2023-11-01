<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import TermsAndConditions from '@/assets/md/terms.md'
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
                    Terms of <i>Responsibilities</i>
                </h1>
                <img class="articleTop-illustration" src={titleWithIllustration} alt="Illustration" />
            </div>
            <div class="content">
                <TermsAndConditions />
            </div>
        </div>
    </section>
</main>

<Footer />
