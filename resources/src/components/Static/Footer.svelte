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

<footer class="bg-primary px-8 text-primary-content">
    <div
        class="container relative mx-auto flex flex-col gap-8 py-8 text-2xl sm:flex-row sm:items-center md:text-3xl lg:text-4xl"
    >
        <div
            class="absolute left-[-10px] top-[20px] z-10 z-[1] h-[48px] w-[77px] rotate-[-35deg] rounded-t-[10px] rounded-bl-[8000px] rounded-br-full rounded-tl-[3000px] rounded-tr-[3000px] bg-[#124D87]"
        ></div>
        <span class="z-20 font-serif">
            Preserve meaningful moments & memories in a beautiful book with<i
                >OttoStory</i
            >.
        </span>

        <a
            href="/stories"
            use:inertia
            class="btn btn-secondary rounded-full font-normal lg:btn-lg"
        >
            Start Writing
        </a>
    </div>
    <div
        class="container footer mx-auto border-t border-primary-content/20 font-serif text-sm"
    >
        <div class="py-8">
            <Logo class="h-10" />
        </div>
        <div class="border-l border-primary-content/20 py-8 pl-10">
            <a
                href="/"
                use:inertia
                class="link-hover link opacity-20 hover:text-[#ffbe32] hover:opacity-100"
                >Features</a
            >
            <a
                href="/about"
                use:inertia
                class="link-hover link opacity-20 hover:text-[#ffbe32] hover:opacity-100"
                >Our Story</a
            >
            <a
                href="/contact"
                use:inertia
                class="link-hover link opacity-20 hover:text-[#ffbe32] hover:opacity-100"
                >Contact</a
            >
        </div>
        <div class="border-l border-primary-content/20 py-8 pl-10">
            <a
                href="/faq"
                use:inertia
                class="link-hover link opacity-20 hover:text-[#ffbe32] hover:opacity-100"
                >FAQs</a
            >
            <a
                href="/terms-and-conditions"
                use:inertia
                class="link-hover link opacity-20 hover:text-[#ffbe32] hover:opacity-100"
                >Terms & Conditions</a
            >
            <a
                href="/privacy-policy"
                use:inertia
                class="link-hover link opacity-20 hover:text-[#ffbe32] hover:opacity-100"
                >Privacy policy</a
            >
        </div>
    </div>
    <div
        class="container footer mx-auto justify-between border-t border-primary-content/20 py-8"
    >
        <div class="opacity-20">
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
