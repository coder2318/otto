<script lang="ts">
    import img from '@/assets/img/about-img.jpg'
    import Splash from '@/components/SVG/splash-about-content.svg.svelte'
</script>

<section class="max-md:mb-15 container mx-auto mb-20 px-[15px] max-sm:mb-10">
    <div class="mb-[-30px] ml-[-30px] flex flex-wrap items-center">
        <div class="mb-[30px] w-1/2 pl-[30px] max-md:w-full">
            <h2
                class="mb-12 text-6xl leading-[1.13] tracking-[-1.2px] text-primary max-xl:text-5xl max-lg:mb-9 max-lg:text-4xl max-sm:mb-6 max-sm:text-3xl"
            >
                You've shown the world chapters of your life,
                <span class="italic">now give them the complete book.</span>
            </h2>

            <div
                class="pl-12 text-lg max-lg:pl-9 max-lg:text-base max-sm:pl-6 max-sm:text-sm"
            >
                <p class="mb-7 max-lg:mb-5 max-sm:mb-3.5">
                    Everyone has a unique story, and at OttoStory, we turn it
                    into an extraordinary autobiography. Meet Otto, your AI
                    biographer, guiding you through a reflective journey of your
                    life's experiences.
                </p>

                <p class="mb-7 max-lg:mb-5 max-sm:mb-3.5">
                    Say goodbye to professional writers and high costs. Otto
                    helps transform your cherished memories into a captivating
                    memoir, allowing you to share your journey or simply reflect
                    on your life. With OttoStory, your story is immortalized in
                    a personal hardback book.
                </p>

                <p>
                    Begin with us today and watch your life's narrative come
                    alive in print. Welcome to OttoStory
                </p>
            </div>
        </div>

        <div
            class="relative mb-[30px] w-1/2 pl-[30px] max-md:order-first max-md:w-full"
        >
            <Splash />

            <div
                class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 overflow-hidden rounded-[11px]"
            >
                <img src={img} alt={img} />
            </div>
        </div>
    </div>
</section>
