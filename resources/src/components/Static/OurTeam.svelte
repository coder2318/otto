<script lang="ts">
    import Splash from '@/components/SVG/splash-page-header.svg.svelte'
    import imgTeam1 from '@/assets/img/team-1.jpg'
    import imgTeam2 from '@/assets/img/team-2.jpg'
    import imgTeam3 from '@/assets/img/team-3.jpg'
</script>

<section class="container mx-auto mb-28 px-[15px] max-lg:mb-20 max-sm:mb-14">
    <h2
        class="relative mx-auto mb-14 w-fit text-6xl leading-[1.13] tracking-[-1.2px] max-lg:mb-10 max-lg:text-4xl max-sm:mb-7 max-sm:text-3xl"
    >
        <div
            class="absolute left-[-79px] top-[-43px] z-[-1] h-[119px] w-[149px] max-lg:left-[-58px] max-lg:top-[-31px] max-lg:h-[88px] max-lg:w-[111px] max-sm:left-[-39px] max-sm:top-[-21px] max-sm:h-[59px] max-sm:w-[74px]"
        >
            <Splash />
        </div>

        Our
        <span class="italic">Team</span>
    </h2>

    <div class="mb-[-30px] ml-[-30px] flex flex-wrap max-sm:justify-center">
        <div class="mb-[30px] w-1/3 pl-[30px] max-sm:w-full max-sm:max-w-xs">
            <div
                class="rounded-2xl bg-white p-4 text-center text-primary max-lg:p-3"
            >
                <div class="mb-4 overflow-hidden rounded-2xl max-lg:mb-3">
                    <img
                        src={imgTeam1}
                        alt="Team member"
                        class="h-auto w-full object-cover"
                    />
                </div>

                <div class="px-4 max-lg:px-3">
                    <div
                        class="font-serif text-3xl tracking-[-0.64px] max-lg:text-2xl max-sm:text-2xl max-sm:text-xl"
                    >
                        Darlene
                    </div>

                    <div
                        class="text-xl font-medium capitalize tracking-[-0.64px] max-lg:text-lg max-md:text-base max-sm:text-lg"
                    >
                        Founders
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-[30px] w-1/3 pl-[30px] max-sm:w-full max-sm:max-w-xs">
            <div
                class="rounded-2xl bg-white p-4 text-center text-primary max-lg:p-3"
            >
                <div class="mb-4 overflow-hidden rounded-2xl max-lg:mb-3">
                    <img
                        src={imgTeam2}
                        alt="Team member"
                        class="h-auto w-full object-cover"
                    />
                </div>

                <div class="px-4 max-lg:px-3">
                    <div
                        class="font-serif text-3xl tracking-[-0.64px] max-lg:text-2xl max-sm:text-2xl max-sm:text-xl"
                    >
                        John Due
                    </div>

                    <div
                        class="text-xl font-medium capitalize tracking-[-0.64px] max-lg:text-lg max-md:text-base max-sm:text-lg"
                    >
                        Customer Care Lead
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-[30px] w-1/3 pl-[30px] max-sm:w-full max-sm:max-w-xs">
            <div
                class="rounded-2xl bg-white p-4 text-center text-primary max-lg:p-3"
            >
                <div class="mb-4 overflow-hidden rounded-2xl max-lg:mb-3">
                    <img
                        src={imgTeam3}
                        alt="Team member"
                        class="h-auto w-full object-cover"
                    />
                </div>

                <div class="px-4 max-lg:px-3">
                    <div
                        class="font-serif text-3xl tracking-[-0.64px] max-lg:text-2xl max-sm:text-2xl max-sm:text-base max-sm:text-xl"
                    >
                        Alejandro
                    </div>

                    <div
                        class="text-xl font-medium capitalize tracking-[-0.64px] max-lg:text-lg max-md:text-base max-sm:text-lg"
                    >
                        Manager
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
