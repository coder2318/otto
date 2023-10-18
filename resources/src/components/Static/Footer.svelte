<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import Logo from '../SVG/logo.svg.svelte'
    import { faFacebook, faInstagram, faTiktok } from '@fortawesome/free-brands-svg-icons'
    import footerIllustration from '@/assets/img/footer-illustration.svg'

    function footerDate(): string {
        const start = 2023
        const end = new Date().getFullYear()

        return start === end ? `${start}` : `${start}-${end}`
    }
</script>

<footer class="overflow-hidden bg-primary text-primary-content">
    <!-- Header -->
    <div class="otto-container">
        <div class="f-top-block mx-auto pb-20 pt-24">
            <div class="f-top relative flex flex-col items-center justify-between sm:flex-row">
                <img class="footerIllustration" src={footerIllustration} alt="Illustration" />
                <h2 class="ftTitle z-10 font-serif text-white">
                    Preserve meaningful moments & memories
                    <br class="hidden lg:inline" />
                    in a beautiful <i>OttoStory</i>.
                </h2>
                <a href="/stories" use:inertia class="btn btn-secondary h-16 rounded-full px-10 text-xl text-primary">
                    Start Writing
                </a>
            </div>
        </div>
        <div class="wrap mx-auto flex flex-row items-stretch border-t border-primary-content/20 font-serif text-lg">
            <div class="f-item basis-1/3 border-r border-primary-content/20 px-4 py-8">
                <div class="relative">
                    <Logo class="h-12" />
                </div>
            </div>
            <div class="f-item basis-1/3 border-r border-primary-content/20 px-8 py-8 pb-16">
                <div class="footer-menu flex flex-col">
                    <a href="/" use:inertia class="link-hover link">Home</a>
                    <a href="/" use:inertia class="link-hover link">Features</a>
                    <a href="/about" use:inertia class="link-hover link">Our Story</a>
                    <a href="/contact" use:inertia class="link-hover link">Contact</a>
                </div>
            </div>
            <div class="f-item basis-1/3 px-8 py-8 pb-16">
                <div class="footer-menu flex flex-col">
                    <a href="/faq" use:inertia class="link-hover link">FAQs</a>
                    <a href="/terms-and-conditions" use:inertia class="link-hover link">Terms & Conditions</a>
                    <a href="/privacy-policy" use:inertia class="link-hover link">Privacy policy</a>
                </div>
            </div>
        </div>
        <div class="footer mx-auto items-center justify-between border-t border-primary-content/20 py-8">
            <p class="text-base text-white text-opacity-30">
                &copy; {footerDate()} OttoStory. All rights reserved
            </p>
            <div class="flex gap-2">
                <a
                    href="https://www.instagram.com/myottostory/"
                    class="btn btn-primary bg-primary-focus/20 text-xl"
                    target="_blank"
                >
                    <Fa icon={faInstagram} />
                </a>
                <a
                    href="https://www.facebook.com/MyOttoStory"
                    class="btn btn-primary bg-primary-focus/20 text-xl"
                    target="_blank"
                >
                    <Fa icon={faFacebook} />
                </a>
                <a
                    href="https://www.tiktok.com/@myottostory"
                    class="btn btn-primary bg-primary-focus/20 text-xl"
                    target="_blank"
                >
                    <Fa icon={faTiktok} />
                </a>
            </div>
        </div>
    </div>
</footer>

<style lang="scss">
    .ftTitle {
        font-size: 50px;
        line-height: 1.1;
        margin-right: 20px;
    }
    .footerIllustration {
        position: absolute;
        left: -25px;
        top: -14px;
    }
    .footer-menu {
        a {
            font-size: 24px;
            line-height: 1.4;
            margin-bottom: 12px;
            color: rgba(255, 255, 255, 0.3);
            transition: 0.3s linear;
            width: fit-content;

            &:focus,
            &:active {
                outline: none;
                border: none;
            }

            &:hover {
                color: rgba(255, 255, 255, 1);
            }

            &:last-child {
                margin-bottom: 0;
            }
        }
    }
    @media (max-width: 1280px) {
        .ftTitle {
            font-size: 40px;
        }
    }
    @media (max-width: 767px) {
        footer {
            .f-top-block {
                padding: 60px 0 44px;
            }
            .f-top {
                flex-direction: column;
                align-items: flex-start;
            }
            .ftTitle {
                font-size: 32px;
                margin-right: 0;
                margin-bottom: 30px;
            }

            .wrap {
                flex-direction: column;
                padding: 32px 0;
            }

            .f-item {
                border: none;
                padding: 0;
                margin-bottom: 32px;
                &:last-child {
                    margin-bottom: 0;
                }
            }
        }
    }
</style>
