<script lang="ts">
    import Phone from '@/components/SVG/contact-phone.svg.svelte'
    import Mail from '@/components/SVG/contact-mail.svg.svelte'
    import Location from '@/components/SVG/contact-location.svg.svelte'
</script>

<section class="container mx-auto mb-20 px-[15px] max-lg:mb-14 max-sm:mb-10">
    <div class="mb-[-30px] ml-[-30px] flex flex-wrap justify-center">
        <div
            class="c-col relative flex flex-col items-center rounded-2xl bg-white px-11 pb-11 max-lg:px-5 max-lg:pb-5 max-md:px-2.5 max-md:pb-2.5 max-sm:max-w-[358px] max-sm:px-11 max-sm:pb-11"
        >
            <a
                href="tel:+444123456789"
                class="absolute bottom-0 left-0 right-0 top-0 opacity-0"
            >
                Call Us Today
            </a>

            <div class="mb-2.5 w-full px-8 max-lg:mb-1.5 max-sm:mb-1">
                <Phone />
            </div>

            <div class="text-center text-primary">
                <h2
                    class="mb-1 font-serif text-2xl tracking-[-0.48px] max-lg:mb-0.5 max-lg:text-xl max-sm:text-lg"
                >
                    Call Us Today
                </h2>

                <div
                    class="text-lg font-medium max-lg:text-base max-sm:text-sm"
                >
                    +444 123 456 789
                </div>
            </div>
        </div>

        <div
            class="c-col relative flex flex-col items-center rounded-2xl bg-white px-11 pb-11 max-lg:px-5 max-lg:pb-5 max-md:px-2.5 max-md:pb-2.5 max-sm:max-w-[358px] max-sm:px-11 max-sm:pb-11"
        >
            <a
                href="mailto:info@ottostory.com"
                class="absolute bottom-0 left-0 right-0 top-0 opacity-0"
            >
                Email Us On
            </a>

            <div class="mb-2.5 w-full px-8 max-lg:mb-1.5 max-sm:mb-1">
                <Mail />
            </div>

            <div class="text-center text-primary">
                <h2
                    class="mb-1 font-serif text-2xl tracking-[-0.48px] max-lg:mb-0.5 max-lg:text-xl max-sm:text-lg"
                >
                    Email Us On
                </h2>

                <div
                    class="text-lg font-medium max-lg:text-base max-sm:text-sm"
                >
                    info@ottostory.com
                </div>
            </div>
        </div>

        <div
            class="c-col relative flex flex-col items-center rounded-2xl bg-white px-11 pb-11 max-lg:px-5 max-lg:pb-5 max-md:px-2.5 max-md:pb-2.5 max-sm:max-w-[358px] max-sm:px-11 max-sm:pb-11"
        >
            <div class="mb-2.5 w-full px-8 max-lg:mb-1.5 max-sm:mb-1">
                <Location />
            </div>

            <div class="text-center text-primary">
                <h2
                    class="mb-1 font-serif text-2xl tracking-[-0.48px] max-lg:mb-0.5 max-lg:text-xl max-sm:text-lg"
                >
                    Location
                </h2>

                <div
                    class="text-lg font-medium max-lg:text-base max-sm:text-sm"
                >
                    New York, USA - 100001
                </div>
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .c-col {
        width: calc(100% / 3 - 30px);
        margin: 0 0 30px 30px;
        box-shadow: 0 0 55px 0 rgba(219, 209, 194, 0.1);

        @media screen and (max-width: 640px) {
            width: 100%;
        }
    }
</style>
