<script lang="ts">
    export let reversed: boolean = false
    export let img: string = ''
</script>

<section
    class="container mx-auto mb-36 mt-48 grid items-start gap-20 max-lg:mb-16 max-lg:mt-24 max-md:p-4 max-sm:mb-8 max-sm:mt-12 lg:grid-cols-2"
>
    <div
        class="mt-14 w-fit overflow-hidden rounded-3xl max-lg:mx-auto max-lg:mt-0
        {reversed ? 'order-1 ml-auto mr-7 max-lg:order-none' : ''}"
    >
        <img src={img} alt="img" />
    </div>

    <div>
        <slot />
    </div>
</section>
