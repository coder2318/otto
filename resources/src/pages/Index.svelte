<script context="module" lang="ts">
    export { default as layout } from '@/components/Layouts/Base.svelte'
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import Hero from '@/components/Static/Hero.svelte'
    import StackCarousel from '@/components/Static/StackCarousel.svelte'
    import Focus from '@/components/Layouts/Focus.svelte'
    import RepliesCarousel from '@/components/Static/RepliesCarousel.svelte'

    import img1 from '@/assets/img/index-img-1.jpg'
    import img2 from '@/assets/img/index-img-2.jpg'
    import img3 from '@/assets/img/index-img-3.jpg'
    import img4 from '@/assets/img/index-img-4.jpg'
    import splash1 from '@/assets/img/splash-1.svg'
    import splash2 from '@/assets/img/splash-2.svg'
    import splash3 from '@/assets/img/splash-3.svg'
    import splash4 from '@/assets/img/splash-4.svg'
    import Fa from 'svelte-fa'
    import { faCheck } from '@fortawesome/free-solid-svg-icons'
    import Footer from '@/components/Static/Footer.svelte'
    import Contact from '@/components/Static/Contact.svelte'
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

<section class="flex items-center justify-center py-16">
    <div
        class="container grid items-center justify-center gap-20 md:grid-cols-2"
    >
        <div class="card order-1 md:order-2">
            <div class="flex flex-col gap-8 p-4">
                <div class="relative flex">
                    <div class="absolute -left-16 -top-12 -z-10">
                        <img src={splash1} alt="splash" />
                    </div>
                    <div class="badge badge-neutral gap-4 px-2 py-6 text-lg">
                        <span class="badge badge-accent h-8 w-8">1</span>
                        <span>Kick-starting Your Story</span>
                    </div>
                </div>
                <h5
                    class="card-title block text-2xl font-normal text-primary md:text-3xl lg:text-4xl xl:text-5xl"
                >
                    Your Story <i>Starts Here</i>
                </h5>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Begin your storytelling by diving into a random chapter
                    presented by Otto, navigate through your life's timeline
                    divided into distinct periods like childhood, adolescence,
                    and beyond, or revise your previous drafts.
                </p>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Each question you encounter has been meticulously designed
                    by experienced psychologists, ensuring meaningful and
                    reflective storytelling. Your journey through the past,
                    present, and future is just a story-start away.
                </p>
                <ul class="md:text-md flex flex-col gap-4 lg:text-lg xl:pl-20">
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>

                        Carefully curated questions and prompts
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>

                        Organize your story by key lifetime events or milestones
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>
                        Timeline structure
                    </li>
                </ul>
            </div>
        </div>
        <div class="card order-1">
            <figure>
                <img class="rounded-2xl" src={img1} alt="" />
            </figure>
        </div>
    </div>
</section>

<section class="flex items-center justify-center py-16">
    <div
        class="container grid items-center justify-center gap-20 md:grid-cols-2"
    >
        <div class="card order-1">
            <div class="flex flex-col gap-8 p-4">
                <div class="relative flex">
                    <div class="absolute -left-16 -top-12 -z-10">
                        <img src={splash2} alt="splash" />
                    </div>
                    <div class="badge badge-neutral gap-4 px-2 py-6 text-lg">
                        <span class="badge badge-primary h-8 w-8 text-neutral"
                            >2</span
                        >
                        <span>Intuitive Engagement with Otto</span>
                    </div>
                </div>
                <h5
                    class="card-title block text-2xl font-normal text-primary md:text-3xl lg:text-4xl xl:text-5xl"
                >
                    Your Story, <i>Your Way</i>
                </h5>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Sharing your life with OttoStory is a breeze. Engage with
                    Otto Story in the way that suits you best. Reply to our
                    curated questions and prompts through spoken voice
                    recordings, uploaded pre-written files, or simply type out
                    your tales on the keyboard.
                </p>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Each entry allows room for drafting, editing, and
                    personalizing your story. And remember, Otto is always there
                    to listen and to refine your narrative, ensuring your memoir
                    shines as brightly as your memories.
                </p>
                <ul class="md:text-md flex flex-col gap-4 lg:text-lg xl:pl-20">
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>

                        Unlimited draft editing and recordings
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>

                        Upload PDFs, Doc or TXT files
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>
                        Multi-lingual translations
                    </li>
                </ul>
            </div>
        </div>
        <div class="card order-1 md:order-2">
            <figure>
                <img class="rounded-2xl" src={img2} alt="" />
            </figure>
        </div>
    </div>
</section>

<section class="flex items-center justify-center py-16">
    <div
        class="container grid items-center justify-center gap-20 md:grid-cols-2"
    >
        <div class="card order-1 md:order-2">
            <div class="flex flex-col gap-8 p-4">
                <div class="relative flex">
                    <div class="absolute -left-16 -top-12 -z-10">
                        <img src={splash3} alt="splash" />
                    </div>
                    <div class="badge badge-neutral gap-4 px-2 py-6 text-lg">
                        <span class="badge badge-success h-8 w-8">3</span>
                        <span>Finalizing Your Masterpiece</span>
                    </div>
                </div>
                <h5
                    class="card-title block text-2xl font-normal text-primary md:text-3xl lg:text-4xl xl:text-5xl"
                >
                    From Digital Pages to <i>Tangible Keepsake</i>
                </h5>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Take artistic control and create a personalized cover that
                    encapsulates the essence of your story. From choosing
                    captivating images to selecting the perfect font, you shape
                    the face of your autobiography.
                </p>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Finally, your digital memoir is transformed into a tangible
                    keepsake, professionally printed and bound in hardback.
                    Every page you turn is a testament to your life's unique
                    journey, now immortalized in print, waiting to be shared and
                    cherished forever.
                </p>
                <ul class="md:text-md flex flex-col gap-4 lg:text-lg xl:pl-20">
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>

                        Physical hardcopy
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>

                        Digital version
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>
                        Custom cover designs
                    </li>
                </ul>
            </div>
        </div>
        <div class="card order-1">
            <figure>
                <img class="rounded-2xl" src={img3} alt="" />
            </figure>
        </div>
    </div>
</section>

<section class="flex items-center justify-center py-16">
    <div
        class="container grid items-center justify-center gap-20 md:grid-cols-2"
    >
        <div class="card order-1">
            <div class="flex flex-col gap-8 p-4">
                <div class="relative flex">
                    <div class="absolute -left-16 -top-12 -z-10">
                        <img src={splash4} alt="splash" />
                    </div>
                    <div class="badge badge-neutral gap-4 px-2 py-6 text-lg">
                        <span class="badge badge-secondary h-8 w-8">4</span>
                        <span>Share and Sell Your Autobiography</span>
                    </div>
                </div>
                <h5
                    class="card-title block text-2xl font-normal text-primary md:text-3xl lg:text-4xl xl:text-5xl"
                >
                    Memoirs Meet <i>Marketplace</i>
                </h5>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    OttoStory isn't just about preserving your life story for
                    yourself and your loved ones. We provide a platform for you
                    to share your memoir with the world. Imagine self-publishing
                    your autobiography online and reaching an audience eager to
                    hear your unique journey.
                </p>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    That's the beauty of OttoStory's marketplace - not just a
                    writing platform but a stage for your narrative. Here, your
                    story can inspire, entertain, and touch lives far beyond
                    your immediate circle.
                </p>
                <ul class="md:text-md flex flex-col gap-4 lg:text-lg xl:pl-20">
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>

                        Worldwide exposure
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>

                        Monetize your life's experiences
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="badge badge-primary h-8 w-8 text-secondary">
                            <Fa icon={faCheck} />
                        </div>
                        Inspire, uplift, and influence
                    </li>
                </ul>
            </div>
        </div>
        <div class="card order-1 md:order-2">
            <figure>
                <img class="rounded-2xl" src={img4} alt="" />
            </figure>
        </div>
    </div>
</section>

<RepliesCarousel />

<Focus>
    <div class="flex w-full items-center justify-center">
        <h6
            class="text-center text-xl text-primary sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl"
        >
            Turn your memories into stories, experiences into wisdom, and a
            lifetime into a beautiful book with OttoStory.
            <br />
            <br />
            Ready to tell <i>your story?</i>
        </h6>
    </div>
</Focus>

<Contact />

<Footer />
