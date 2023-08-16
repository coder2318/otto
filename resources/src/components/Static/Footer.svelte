<script>
    import { inertia } from '@inertiajs/svelte'
    import Fa from 'svelte-fa'
    import Logo from '../SVG/logo.svg.svelte'
    import { faFacebook, faInstagram } from '@fortawesome/free-brands-svg-icons'
</script>

<footer class="bg-primary px-4 text-primary-content">
    <div
        class="container mx-auto flex gap-8 py-8 text-2xl md:text-3xl lg:text-4xl xl:text-5xl"
    >
        <span class="font-serif"
            >Preserve meaningful moments & memories in a beautiful <i
                >OttoBook</i
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
            <a class="link-hover link">Features</a>
            <a class="link-hover link">Our Story</a>
            <a class="link-hover link">Contact</a>
        </div>
        <div>
            <a class="link-hover link">FAQs</a>
            <a class="link-hover link">Terms & Conditions</a>
            <a class="link-hover link">Privacy policy</a>
        </div>
    </div>
    <div
        class="container footer mx-auto justify-between border-t border-primary-content/20 py-8"
    >
        <div>
            &copy; 2023-{new Date().getFullYear()} OttoStory. All rights reserved
        </div>
        <div class="flex gap-2">
            <a
                href="#"
                class="btn btn-primary bg-primary-focus/20 text-xl"
                target="_blank"
            >
                <Fa icon={faInstagram} />
            </a>
            <a
                href="#"
                class="btn btn-primary bg-primary-focus/20 text-xl"
                target="_blank"
            >
                <Fa icon={faFacebook} />
            </a>
        </div>
    </div>
</footer>
