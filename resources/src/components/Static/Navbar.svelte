<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Logo from '../SVG/logo.svg.svelte'
    import { onMount } from 'svelte'

    let scrolled = false

    onMount(() => {
        const setScrolled = () => (scrolled = window.scrollY > 0)
        window.addEventListener('scroll', setScrolled)

        return () => window.removeEventListener('scroll', setScrolled)
    })
</script>

<nav class="navbar fixed top-0 z-20 transition-all" class:bg-primary={scrolled}>
    <div class="flex-1">
        <a class="btn btn-ghost text-xl normal-case" href="/" use:inertia>
            <Logo
                class="h-full"
                textClass={scrolled ? 'fill-primary-content' : 'fill-neutral'}
            />
        </a>
    </div>
    <div class="flex-none gap-2">
        <a
            class="btn btn-secondary btn-outline rounded-full"
            href="/stories"
            use:inertia>Start Writing</a
        >
    </div>
</nav>
