<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import Logo from '../SVG/logo.svg.svelte'
</script>

<nav class="navbar absolute top-0 z-20 transition-all">
    <div class="flex-1">
        <a class="btn btn-ghost text-xl normal-case" href="/" use:inertia>
            <Logo class="h-full" textClass="fill-neutral" />
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
