<script lang="ts">
    import Splash from '@/components/SVG/splash-page-header.svg.svelte'

    let className = ''
    export { className as class }

    export let element = 'section'
</script>

<svelte:element
    this={element}
    class="container relative mx-auto flex justify-center text-center text-primary {className}"
>
    <h1 class="relative text-4xl md:text-5xl lg:text-6xl">
        <Splash
            class="right absolute -left-8 top-1/2 -z-10 h-16 -translate-y-1/2 md:h-24 lg:h-36"
        />
        <slot />
    </h1>
</svelte:element>
