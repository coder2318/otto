<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import Logo from '../SVG/logo.svg.svelte'
    import {
        faFacebook,
        faInstagram,
        faTiktok,
    } from '@fortawesome/free-brands-svg-icons'

    function footerDate(): string {
        const start = 2023
        const end = new Date().getFullYear()

        return start === end ? `${start}` : `${start}-${end}`
    }
</script>

<footer class="bg-primary px-4 text-primary-content">
    <div
        class="container mx-auto flex flex-col gap-8 py-8 text-2xl sm:flex-row sm:items-center md:text-3xl lg:text-4xl"
    >
        <span class="font-serif"
            >Preserve meaningful moments & memories in a beautiful book with <i
                >OttoStory</i
            >.</span
        >

        <a
            href="/stories"
            use:inertia
            class="btn btn-secondary rounded-full lg:btn-lg"
        >
            Start Writing
        </a>
    </div>
    <div
        class="container footer mx-auto border-t border-primary-content/20 py-8 font-serif text-lg"
    >
        <div>
            <Logo class="h-16" />
        </div>
        <div>
            <a href="/" use:inertia class="link-hover link">Features</a>
            <a href="/about" use:inertia class="link-hover link">Our Story</a>
            <a href="/contact" use:inertia class="link-hover link">Contact</a>
        </div>
        <div>
            <a href="/faq" use:inertia class="link-hover link">FAQs</a>
            <a href="/terms-and-conditions" use:inertia class="link-hover link"
                >Terms & Conditions</a
            >
            <a href="/privacy-policy" use:inertia class="link-hover link"
                >Privacy policy</a
            >
        </div>
    </div>
    <div
        class="container footer mx-auto justify-between border-t border-primary-content/20 py-8"
    >
        <div>
            &copy; {footerDate()} OttoStory. All rights reserved
        </div>
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
</footer>
