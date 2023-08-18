<script lang="ts">
    import SplashTop from '@/components/SVG/splash-cta-top.svg.svelte'
    import SplashBottom from '@/components/SVG/splash-cta-bottom.svg.svelte'
</script>

<section
    class="relative mb-20 bg-[#EAE4DC] px-20 py-44 text-center max-lg:mb-14 max-lg:px-14 max-lg:py-32 max-sm:mb-10 max-sm:px-10 max-sm:py-20"
>
    <div class="relative z-[1]">
        <h2
            class="mb-14 text-6xl leading-[1.13] tracking-[-1.2px] text-primary max-lg:mb-10 max-lg:text-4xl max-sm:mb-7 max-sm:text-3xl"
        >
            Memoirs of a Year :

            <span class="italic">
                A Beautiful Book Encompassing a Lifetime of Stories
            </span>
        </h2>

        <a
            href="#"
            class="rounded-[63px] bg-primary px-10 py-4 text-[20px] capitalize text-white max-lg:px-7 max-lg:py-3 max-lg:text-[15px] max-sm:px-5 max-sm:py-2 max-sm:text-[16px]"
        >
            Buy Now
        </a>
    </div>

    <div
        class="absolute right-[27px] top-[-23px] w-[200px] max-lg:right-[20px] max-lg:top-[-17px] max-lg:w-[150px] max-sm:right-[13px] max-sm:top-[-11px] max-sm:w-[100px]"
    >
        <SplashTop />
    </div>

    <div
        class="absolute bottom-[-24px] left-0 w-[181px] max-lg:bottom-[-18px] max-lg:w-[136px] max-sm:bottom-[-12px] max-sm:w-[90px]"
    >
        <SplashBottom />
    </div>
</section>
