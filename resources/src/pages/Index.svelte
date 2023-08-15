<script context="module" lang="ts">
    export { default as layout } from '@/components/Layouts/Base.svelte'
</script>

<script lang="ts">
    import Navbar from '@/components/Static/Navbar.svelte'
    import Hero from '@/components/Static/Hero.svelte'
    import StackCarousel from '@/components/Static/StackCarousel.svelte'
    import Focus from '@/components/Layouts/Focus.svelte'
    import TwoColumns from '@/components/Static/TwoColumns.svelte'

    import checkMark from '@/assets/img/checkmark.svg'
    import img1 from '@/assets/img/img-1.jpg'
    import img2 from '@/assets/img/img-2.jpg'
    import img3 from '@/assets/img/img-3.jpg'
    import img4 from '@/assets/img/img-4.jpg'
    import splash1 from '@/assets/img/splash-1.svg'
    import splash2 from '@/assets/img/splash-2.svg'
    import splash3 from '@/assets/img/splash-3.svg'
    import splash4 from '@/assets/img/splash-4.svg'
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

<TwoColumns img={img1}>
    <div
        class="relative mb-7 flex w-fit items-center rounded-full bg-white py-2 pl-3 pr-4 text-lg max-sm:text-base"
    >
        <div class="absolute left-[-64px] top-[-48px] z-[-1]">
            <img src={splash1} alt="splash" />
        </div>

        <span
            class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-[#F7A392] text-sm text-white max-sm:h-6 max-sm:w-6 max-sm:text-xs"
        >
            1
        </span>

        Kick-starting Your Story
    </div>

    <h2
        class="mb-7 text-6xl tracking-[-1.2px] text-primary max-sm:mb-3 max-sm:text-3xl"
    >
        Your Story

        <span class="text-[64px] italic tracking-[-1.28px] max-sm:text-3xl">
            Starts Here
        </span>
    </h2>

    <div class="pl-28 text-lg max-sm:pl-7 max-sm:text-base">
        <p class="mb-7 max-sm:mb-5">
            Begin your storytelling by diving into a random chapter presented by
            Otto, navigate through your life's timeline divided into distinct
            periods like childhood, adolescence, and beyond, or revise your
            previous drafts.
        </p>

        <p class="mb-7 max-sm:mb-5">
            Each question you encounter has been meticulously designed by
            experienced psychologists, ensuring meaningful and reflective
            storytelling. Your journey through the past, present, and future is
            just a story-start away.
        </p>

        <ul>
            <li class="flex border-b border-white pb-4">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Carefully curated questions and prompts
            </li>

            <li class="flex border-b border-white pb-4 pt-3.5">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Organize your story by key lifetime events or milestones
            </li>

            <li class="flex pt-3.5">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Timeline structure
            </li>
        </ul>
    </div>
</TwoColumns>

<TwoColumns reversed img={img2}>
    <div
        class="relative mb-7 flex w-fit items-center rounded-full bg-white py-2 pl-3 pr-4 text-lg max-sm:text-base"
    >
        <div class="absolute left-[-64px] top-[-48px] z-[-1]">
            <img src={splash2} alt="splash" />
        </div>

        <span
            class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-[#112948] text-sm text-white max-sm:h-6 max-sm:w-6 max-sm:text-xs"
        >
            2
        </span>

        Intuitive Engagement with Otto
    </div>

    <h2
        class="mb-7 text-6xl tracking-[-1.2px] text-primary max-sm:mb-3 max-sm:text-3xl"
    >
        Your Story,

        <span class="text-[64px] italic tracking-[-1.28px] max-sm:text-3xl">
            Your Way
        </span>
    </h2>

    <div class="pl-28 text-lg max-sm:pl-7 max-sm:text-base">
        <p class="mb-7 max-sm:mb-5">
            Sharing your life with OttoStory is a breeze. Engage with Otto Story
            in the way that suits you best. Reply to our curated questions and
            prompts through spoken voice recordings, uploaded pre-written files,
            or simply type out your tales on the keyboard.
        </p>

        <p class="mb-7 max-sm:mb-5">
            Each entry allows room for drafting, editing, and personalizing your
            story. And remember, Otto is always there to listen and to refine
            your narrative, ensuring your memoir shines as brightly as your
            memories.
        </p>

        <ul>
            <li class="flex border-b border-white pb-4">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Unlimited draft editing and recordings
            </li>

            <li class="flex border-b border-white pb-4 pt-3.5">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Upload PDFs, Doc or TXT files
            </li>

            <li class="flex pt-3.5">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Multi-lingual translations
            </li>
        </ul>
    </div>
</TwoColumns>

<TwoColumns img={img3}>
    <div
        class="relative mb-7 flex w-fit items-center rounded-full bg-white py-2 pl-3 pr-4 text-lg max-sm:text-base"
    >
        <div class="absolute left-[-64px] top-[-48px] z-[-1]">
            <img src={splash3} alt="splash" />
        </div>

        <span
            class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-[#C0DFB0] text-sm text-white max-sm:h-6 max-sm:w-6 max-sm:text-xs"
        >
            3
        </span>

        Finalizing Your Masterpiece
    </div>

    <h2
        class="mb-7 text-6xl tracking-[-1.2px] text-primary max-sm:mb-3 max-sm:text-3xl"
    >
        From Digital Pages to

        <span class="text-[64px] italic tracking-[-1.28px] max-sm:text-3xl">
            Tangible Keepsake
        </span>
    </h2>

    <div class="pl-28 text-lg max-sm:pl-7 max-sm:text-base">
        <p class="mb-7 max-sm:mb-5">
            Take artistic control and create a personalized cover that
            encapsulates the essence of your story. From choosing captivating
            images to selecting the perfect font, you shape the face of your
            autobiography.
        </p>

        <p class="mb-7 max-sm:mb-5">
            Finally, your digital memoir is transformed into a tangible
            keepsake, professionally printed and bound in hardback. Every page
            you turn is a testament to your life's unique journey, now
            immortalized in print, waiting to be shared and cherished forever.
        </p>

        <ul>
            <li class="flex border-b border-white pb-4">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Physical hardcopy
            </li>

            <li class="flex border-b border-white pb-4 pt-3.5">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Digital version
            </li>

            <li class="flex pt-3.5">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Custom cover designs
            </li>
        </ul>
    </div>
</TwoColumns>

<TwoColumns img={img4} reversed>
    <div
        class="relative mb-7 flex w-fit items-center rounded-full bg-white py-2 pl-3 pr-4 text-lg max-sm:text-base"
    >
        <div class="absolute left-[-64px] top-[-48px] z-[-1]">
            <img src={splash4} alt="splash" />
        </div>

        <span
            class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-[#C0DFB0] text-sm text-white max-sm:h-6 max-sm:w-6 max-sm:text-xs"
        >
            4
        </span>

        Share and Sell Your Autobiography
    </div>

    <h2
        class="mb-7 text-6xl tracking-[-1.2px] text-primary max-sm:mb-3 max-sm:text-3xl"
    >
        Memoirs Meet

        <span class="text-[64px] italic tracking-[-1.28px] max-sm:text-3xl">
            Marketplace
        </span>
    </h2>

    <div class="pl-28 text-lg max-sm:pl-7 max-sm:text-base">
        <p class="mb-7 max-sm:mb-5">
            OttoStory isn't just about preserving your life story for yourself
            and your loved ones. We provide a platform for you to share your
            memoir with the world. Imagine self-publishing your autobiography
            online and reaching an audience eager to hear your unique journey.
        </p>

        <p class="mb-7 max-sm:mb-5">
            That's the beauty of OttoStory's marketplace - not just a writing
            platform but a stage for your narrative. Here, your story can
            inspire, entertain, and touch lives far beyond your immediate
            circle.
        </p>

        <ul>
            <li class="flex border-b border-white pb-4">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Worldwide exposure
            </li>

            <li class="flex border-b border-white pb-4 pt-3.5">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Monetize your life's experiences
            </li>

            <li class="flex pt-3.5">
                <span class="mr-4">
                    <img src={checkMark} alt="Checkmark" />
                </span>

                Inspire, uplift, and influence
            </li>
        </ul>
    </div>
</TwoColumns>
