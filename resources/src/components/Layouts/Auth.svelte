<script lang="ts">
    import image from '@/assets/img/auth-girl.jpg'
    import background from '@/assets/img/auth-bg.svg'
</script>

<main class="grid min-h-screen grid-cols-12 gap-4 p-2 lg:p-4">
    <div
        class="hidden h-full w-full rounded-xl bg-cover bg-top md:col-span-6 md:block lg:col-span-8"
        style="background-image: url({image})"
    />
    <div
        class="col-span-12 flex items-center justify-center bg-no-repeat md:col-span-6 lg:col-span-4"
        style="background-image: url({background})"
    >
        <slot />
    </div>
</main>
