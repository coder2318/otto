<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    export const layout = [Base]
</script>

<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Navbar from '@/components/Static/Navbar.svelte'
    import PageHeader from '@/components/Static/PageHeader.svelte'
    import Footer from '@/components/Static/Footer.svelte'
    import img from '@/assets/img/about-img.jpg'
    import splash from '@/assets/img/about-splash.svg'
    import imgTeam1 from '@/assets/img/team-1.jpg'
    import imgTeam2 from '@/assets/img/team-2.jpg'
    import imgTeam3 from '@/assets/img/team-3.jpg'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - About</title>
</svelte:head>

<Navbar class="bg-primary text-primary-content" />

<PageHeader
    class="container relative mx-auto mb-16 mt-36 flex justify-center text-center text-primary lg:mb-24 lg:mt-48"
>
    About <i>Us</i>
</PageHeader>

<section class="container mx-auto mb-16">
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="card">
            <div class="card-body">
                <h5
                    class="card-title mb-8 block text-2xl font-normal text-primary md:text-3xl lg:text-4xl xl:text-5xl"
                >
                    You've shown the world chapters of your life, <i
                        >now give them the complete book</i
                    >.
                </h5>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Everyone has a unique story, and at OttoStory, we turn it
                    into an extraordinary autobiography. Meet Otto, your AI
                    biographer, guiding you through a reflective journey of your
                    life's experiences.
                </p>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Say goodbye to professional writers and high costs. Otto
                    helps transform your cherished memories into a captivating
                    memoir, allowing you to share your journey or simply reflect
                    on your life. With OttoStory, your story is immortalized in
                    a personal hardback book.
                </p>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Begin with us today and watch your life's narrative come
                    alive in print. Welcome to OttoStory
                </p>
            </div>
        </div>
        <div
            class="mx-auto bg-contain bg-no-repeat p-16"
            style="background-image: url({splash})"
        >
            <img src={img} alt={img} class="h-auto w-full" />
        </div>
    </div>
</section>

<section class="cta-bg bg-base-200">
    <div
        class="container mx-auto mb-16 flex flex-col items-center justify-center gap-8 py-16 text-center"
    >
        <h2 class="text-4xl text-primary md:text-5xl lg:text-6xl">
            Memoirs of a Year : <i
                >A Beautiful Book Encompassing a Lifetime of Stories</i
            >
        </h2>
        <a
            href="/plans"
            class="btn btn-primary btn-lg rounded-full px-16"
            use:inertia
        >
            Buy Now
        </a>
    </div>
</section>

<section class="container mx-auto mb-16">
    <PageHeader element="div" class="my-16 lg:my-24">Our <i>Team</i></PageHeader
    >

    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <div class="card bg-neutral text-neutral-content">
            <figure>
                <img src={imgTeam1} alt="" class="w-full" />
            </figure>
            <div class="card-body items-center text-primary">
                <h2 class="card-title text-2xl">Darlene</h2>
                <p>Founder</p>
            </div>
        </div>
        <div class="card bg-neutral text-neutral-content">
            <figure>
                <img src={imgTeam2} alt="" class="w-full" />
            </figure>
            <div class="card-body items-center text-primary">
                <h2 class="card-title text-2xl">John Due</h2>
                <p>Customer Care Lead</p>
            </div>
        </div>
        <div class="card bg-neutral text-neutral-content">
            <figure>
                <img src={imgTeam3} alt="" class="w-full" />
            </figure>
            <div class="card-body items-center text-primary">
                <h2 class="card-title text-2xl">Alejandro</h2>
                <p>Manager</p>
            </div>
        </div>
    </div>
</section>

<Footer />

<style lang="scss">
    .cta-bg {
        background-image: url(@/assets/img/about-cta-2.svg),
            url(@/assets/img/about-cta-1.svg);
        background-repeat: no-repeat, no-repeat;
        background-position:
            top 0 right 0,
            bottom 0 left 0;

        background-size: 9rem, 6rem;
    }
</style>
