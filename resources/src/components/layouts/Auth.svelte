<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Logo from "@/components/svg/logo.svg.svelte";
</script>

<main class="min-h-screen w-full items-center text-center flex flex-col justify-center p-8 md:p-12 lg:p-16">
    <header>
        <a href="/" use:inertia class="max-w-[300px] mx-auto mt-8 btn btn-ghost h-auto py-2 mb-8 md:mb-12 lg:mb-16">
            <Logo class="w-full" textClass="fill-current" logoClass="fill-primary" logoBgClass="fill-secondary" />
        </a>
    </header>
    <slot />
    <footer class="footer footer-center p-8 md:p-12 lg:p-16 text-base-content">
        <div>
            <p>Copyright © 2023 - All right reserved</p>
        </div>
    </footer>
</main>
