<script lang="ts">
    import image from '@/assets/img/auth-girl.jpg'
    import background from '@/assets/img/auth-bg.svg'
</script>

<main class="grid grid-cols-12 p-2 lg:p-4 min-h-screen gap-4">
    <div
        class="md:col-span-6 lg:col-span-8 hidden md:block h-full w-full bg-cover rounded-xl bg-top"
        style="background-image: url({image})"
    />
    <div
        class="col-span-12 md:col-span-6 lg:col-span-4 bg-no-repeat flex items-center justify-center"
        style="background-image: url({background})"
    >
        <slot />
    </div>
</main>
