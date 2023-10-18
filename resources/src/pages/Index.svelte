<script context="module" lang="ts">
    export { default as layout } from '@/components/Layouts/Base.svelte'
</script>

<script lang="ts">
    // import { inertia } from '@inertiajs/svelte'
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
    import checkIcon from '@/assets/img/check-icon.svg'
    // import Fa from 'svelte-fa'
    // import { faCheck } from '@fortawesome/free-solid-svg-icons'
    import Footer from '@/components/Static/Footer.svelte'
    import Contact from '@/components/Static/Contact.svelte'
    import Plates from '@/components/Static/Plates.svelte'
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME}</title>
</svelte:head>

<Navbar class="text-neutral" />

<Hero />

<StackCarousel />

<Focus>
    <div class="flex w-full items-center justify-center">
        <h6 class="fz_h2 focusTitle text-center text-primary">
            Wave goodbye to expensive fees and embrace
            <br class="hidden lg:inline" />
            the chance to author your life story.
        </h6>
    </div>
</Focus>

<section class="imageContent flex items-center justify-center pb-16 pt-56 md:pt-72">
    <div class="otto-container">
        <div class="wrap">
            <div class="cardContent card order-1 md:order-2">
                <img class="cardContent-illustration" src={splash1} alt="splash" />
                <div class="z-10 flex flex-col">
                    <div class="relative flex items-center justify-between">
                        <div class="cardContent__target">
                            <span class="cardContent__target-number"> 1 </span>
                            <p>Kick-starting Your Story</p>
                        </div>
                        <!-- <a href="/stories" use:inertia class="btn btn-secondary btn-lg rounded-full"> Start Writing </a> -->
                    </div>
                    <h5 class="fz_h2 card-title mb-8 block font-normal text-primary">
                        Your Story <i>Starts Here</i>
                    </h5>
                    <div class="cardContent__wrap">
                        <div class="cardContent__desc">
                            <p>
                                Begin your storytelling by diving into a random chapter presented by Otto, navigate
                                through your life's timeline divided into distinct periods like childhood, adolescence,
                                and beyond, or revise your previous drafts.
                            </p>
                            <p>
                                Each question you encounter has been meticulously designed by experienced psychologists,
                                ensuring meaningful and reflective storytelling. Your journey through the past, present,
                                and future is just a story-start away.
                            </p>
                        </div>
                        <ul class="cardContent__list">
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>

                                <p>Carefully curated questions and prompts</p>
                            </li>
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>

                                <p>Organize your story by key lifetime events or milestones</p>
                            </li>
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Timeline structure</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="personPhoto order-1 mr-8">
                <img src={img1} alt="Person" />
            </div>
        </div>
    </div>
</section>

<section class="imageContent flex items-center justify-center py-20">
    <div class="otto-container">
        <div class="wrap flex flex-col justify-between md:flex-row">
            <div class="cardContent card order-1 items-center">
                <img class="cardContent-illustration" src={splash2} alt="splash" />
                <div class="z-10 flex flex-col">
                    <div class="relative flex items-center justify-between">
                        <div class="cardContent__target second">
                            <span class="cardContent__target-number second"> 2 </span>
                            <p>Intuitive Engagement with Otto</p>
                        </div>
                        <!-- <a href="/stories" use:inertia class="btn btn-secondary btn-lg rounded-full"> Start Writing </a> -->
                    </div>
                    <h5 class="fz_h2 card-title mb-8 block font-normal text-primary">
                        Your Story, <i>Your Way</i>
                    </h5>
                    <div class="cardContent__wrap">
                        <div class="cardContent__desc">
                            <p>
                                Sharing your life with OttoStory is a breeze. Engage with Otto Story in the way that
                                suits you best. Reply to our curated questions and prompts through spoken voice
                                recordings, uploaded pre-written files, or simply type out your tales on the keyboard.
                            </p>
                            <p>
                                Each entry allows room for drafting, editing, and personalizing your story. And
                                remember, Otto is always there to listen and to refine your narrative, ensuring your
                                memoir shines as brightly as your memories.
                            </p>
                        </div>
                        <ul class="cardContent__list">
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Unlimited draft editing and recordings</p>
                            </li>
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Upload PDFs, Doc or TXT files</p>
                            </li>
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Multi-lingual translations</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="personPhoto order-2 ml-8">
                <img src={img2} alt="Person" />
            </div>
        </div>
    </div>
</section>

<section class="imageContent flex items-center justify-center py-20">
    <div class="otto-container">
        <div class="wrap flex flex-col justify-between md:flex-row">
            <div class="cardContent card order-1 md:order-2">
                <img class="cardContent-illustration" src={splash3} alt="splash" />
                <div class="z-10 flex flex-col">
                    <div class="relative flex items-center justify-between">
                        <div class="cardContent__target">
                            <span class="cardContent__target-number third"> 3 </span>
                            <p>Finalizing Your Masterpiece</p>
                        </div>
                        <!-- <a href="/stories" use:inertia class="btn btn-secondary btn-lg rounded-full"> Start Writing </a> -->
                    </div>

                    <h5 class="fz_h2 card-title mb-8 block font-normal text-primary">
                        From Digital Pages to <i>Tangible Keepsake</i>
                    </h5>
                    <div class="cardContent__wrap">
                        <div class="cardContent__desc">
                            <p>
                                Take artistic control and create a personalized cover that encapsulates the essence of
                                your story. From choosing captivating images to selecting the perfect font, you shape
                                the face of your autobiography.
                            </p>
                            <p>
                                Finally, your digital memoir is transformed into a tangible keepsake, professionally
                                printed and bound in hardback. Every page you turn is a testament to your life's unique
                                journey, now immortalized in print, waiting to be shared and cherished forever.
                            </p>
                        </div>
                        <ul class="cardContent__list">
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Worldwide exposure</p>
                            </li>
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Digital version</p>
                            </li>
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Custom cover designs</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="personPhoto order-1 mr-8">
                <img src={img3} alt="Person" />
            </div>
        </div>
    </div>
</section>

<section class="imageContent flex items-center justify-center pb-36 pt-20">
    <div class="otto-container">
        <div class="wrap flex flex-col justify-between md:flex-row">
            <div class="cardContent card order-1 items-center">
                <img class="cardContent-illustration" src={splash4} alt="splash" />
                <div class="z-10 flex flex-col">
                    <div class="relative flex items-center justify-between">
                        <div class="cardContent__target">
                            <span class="cardContent__target-number fourth"> 4 </span>
                            <p>Share and Sell Your Autobiography</p>
                        </div>
                        <!-- <a href="/stories" use:inertia class="btn btn-secondary btn-lg rounded-full"> Start Writing </a> -->
                    </div>

                    <h5 class="fz_h2 card-title mb-8 block font-normal text-primary">
                        Memoirs Meet <i>Marketplace</i>
                    </h5>
                    <div class="cardContent__wrap">
                        <div class="cardContent__desc">
                            <p>
                                OttoStory isn't just about preserving your life story for yourself and your loved ones.
                                We provide a platform for you to share your memoir with the world. Imagine
                                self-publishing your autobiography online and reaching an audience eager to hear your
                                unique journey.
                            </p>
                            <p>
                                That's the beauty of OttoStory's marketplace - not just a writing platform but a stage
                                for your narrative. Here, your story can inspire, entertain, and touch lives far beyond
                                your immediate circle.
                            </p>
                        </div>
                        <ul class="cardContent__list">
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Worldwide exposure</p>
                            </li>
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Monetize your life's experiences</p>
                            </li>
                            <li>
                                <div class="checkIcon">
                                    <img src={checkIcon} alt="check" />
                                </div>
                                <p>Inspire, uplift, and influence</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="personPhoto order-2 ml-8">
                <img src={img4} alt="Person" />
            </div>
        </div>
    </div>
</section>

<RepliesCarousel />

<Plates />

<Focus>
    <div class="flex w-full items-center justify-center">
        <h6 class="fz_h2 focusTitle text-center text-primary">
            Turn your memories into stories, experiences
            <br class="hidden lg:inline" />
            into wisdom, and a lifetime into a beautiful
            <br class="hidden lg:inline" />
            book with OttoStory.
            <br />
            <br />
            Ready to tell <i>your story?</i>
        </h6>
    </div>
</Focus>

<Contact />

<Footer />

<style lang="scss">
    .imageContent {
        .wrap {
            display: flex;
            justify-content: space-between;
        }
        .cardContent {
            width: 100%;
            max-width: 650px;
            position: relative;

            &-illustration {
                position: absolute;
                left: -60px;
                top: -48px;
            }

            &__target {
                margin-bottom: 20px;
                background-color: #fff;
                height: 54px;
                border-radius: 40px;
                display: flex;
                align-items: center;
                padding: 0 18px 0 12px;

                &-number {
                    width: 36px;
                    height: 36px;
                    border-radius: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 12px;
                    background-color: #f7a392;
                    color: #fff;
                    font-size: 14px;

                    &.second {
                        background-color: #112948;
                    }

                    &.third {
                        background-color: #c0dfb0;
                        color: #112948;
                    }
                    &.fourth {
                        background-color: #ffd27b;
                        color: #112948;
                    }
                }

                p {
                    color: #06192d;
                    font-size: 1.125rem;
                    line-height: 1;
                }
            }

            &__wrap {
                padding-left: 108px;
                padding-right: 26px;
            }
            &__desc {
                p {
                    color: #474747;
                    margin-bottom: 20px;
                    font-size: 1.125rem;
                    line-height: 1.6;
                }
            }
            &__list {
                li {
                    display: flex;
                    align-items: center;
                    padding: 16px 0;
                    border-bottom: 1px solid #fff;

                    .checkIcon {
                        margin-right: 18px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background-color: #172f50;
                        border-radius: 100%;
                        min-width: 30px;
                        height: 30px;

                        img {
                            max-width: 20px;
                        }
                    }

                    p {
                        margin: 0;
                        color: #474747;
                        font-size: 1.125rem;
                        line-height: 1.1;
                    }

                    &:last-child {
                        border-bottom: 0;
                    }
                }
            }
        }
        .personPhoto {
            position: relative;
            width: 100%;
            min-width: 360px;
            max-width: 408px;
            height: 540px;
            overflow: hidden;
            margin-top: 82px;
            border-radius: 24px;
            z-index: 3;

            img {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }
    }
    @media (max-width: 1280px) {
        .imageContent {
            .cardContent__wrap {
                padding-left: 0;
            }
        }
    }
    @media (max-width: 991px) {
        .imageContent {
            .wrap {
                flex-direction: column;
            }
            .cardContent {
                max-width: 100%;
                &__wrap {
                    padding-right: 0;
                }
            }
            .personPhoto {
                order: 2 !important;
                margin: 0;
                margin-top: 30px;
                width: 100%;
                min-width: auto;
                max-width: 400px;
                height: auto;
                aspect-ratio: 16/20;
            }
        }
    }
    @media (max-width: 767px) {
        .focusTitle {
            font-size: 28px;
        }
    }
</style>
