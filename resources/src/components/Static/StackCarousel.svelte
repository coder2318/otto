<script lang="ts">
    import { blur } from 'svelte/transition'
    import person1 from '@/assets/img/stack-person1.jpg'
    import person2 from '@/assets/img/stack-person2.jpg'
    import person3 from '@/assets/img/stack-person3.jpg'
    import person4 from '@/assets/img/stack-person4.jpg'
    import bg from '@/assets/img/stack-bg.svg'
    import { onMount } from 'svelte'

    let elements = [
        {
            index: 0,
            image: person1,
            class: '-rotate-3',
        },
        {
            index: 1,
            image: person2,
            class: '-rotate-6',
        },
        {
            index: 2,
            image: person3,
            class: '-rotate-10',
        },
        {
            index: 3,
            image: person4,
            class: '',
        },
    ]

    function change(back: boolean = false, update: boolean = true) {
        back
            ? elements.unshift(elements.pop())
            : elements.push(elements.shift())

        if (!update) {
            return
        }

        elements = elements
    }

    function setActive(index: number) {
        while (elements[0].index !== index) {
            change(false, false)
        }
        elements = elements
    }
</script>

<section class="flex min-h-screen items-center justify-center py-16">
    <div class="container grid gap-20 lg:grid-cols-2">
        <div class="card">
            <div class="flex flex-col gap-8">
                <h5
                    class="card-title block text-2xl font-normal text-primary md:text-3xl lg:text-4xl xl:text-5xl"
                >
                    You've shown the world chapters of your life, <i
                        >now give them the complete book.</i
                    >
                </h5>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Everyone has a unique story, and at OttoStory, we turn it
                    into an extraordinary autobiography. Meet Otto, your AI
                    biographer, guiding you through a reflective journey of your
                    life's experiences.
                </p>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Say goodbye to professional writers and high costs. Otto
                    helps transform your cherished memories into a captivating
                    memoir, allowing you to share your journey or simply reflect
                    on your life. With OttoStory, your story is immortalized in
                    a personal hardback book.
                </p>
                <p class="md:text-md lg:text-lg xl:pl-20">
                    Begin with us today and watch your life's narrative come
                    alive in print. Welcome to OttoStory
                </p>
            </div>
        </div>
        <div
            class="card bg-contain bg-center bg-no-repeat"
            style="background-image: url({bg})"
        >
            <div class="items-bottom card-body justify-center p-20">
                <div class="relative h-full min-h-[25rem] w-full">
                    {#each elements as element (element.index)}
                        {#key Math.random().toString(36).slice(2, 7)}
                            <img
                                in:blur
                                src={element.image}
                                class="mask absolute h-full w-full rounded-lg object-cover drop-shadow-lg {element.class}"
                                alt=""
                            />
                        {/key}
                    {/each}
                </div>

                <div class="flex w-full justify-center gap-2 pt-8">
                    {#each [...elements].sort( (a, b) => (a.index > b.index ? 1 : -1) ) as { index } (index)}
                        <button
                            on:click|preventDefault={() => setActive(index)}
                            type="button"
                            class="btn btn-circle btn-xs"
                            class:btn-primary={index === elements[0].index}
                        />
                    {/each}
                </div>
            </div>
        </div>
    </div>
</section>
