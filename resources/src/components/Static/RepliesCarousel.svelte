<script lang="ts">
    import { blur } from 'svelte/transition'
    import replyImg1 from '@/assets/img/reply-1.png'
    import replyImg2 from '@/assets/img/reply-2.png'
    import replyImg3 from '@/assets/img/reply-3.png'
    import replyImg4 from '@/assets/img/reply-4.png'
    import Fa from 'svelte-fa'
    import {
        faCaretLeft,
        faCaretRight,
        faQuoteLeft,
    } from '@fortawesome/free-solid-svg-icons'

    let carousel: HTMLElement

    export let elements = [
        {
            text: `Crafting my own autobiography always seemed like a dream, something unattainable and costly. But then I discovered OttoStory. The platform empowered me to capture my life's journey without spending thousands. It's such a joy to see my story beautifully bound. OttoStory truly made my dream a reality.`,
            name: 'John Doe',
            photo: replyImg1,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `Crafting my own autobiography always seemed like a dream, something unattainable and costly. But then I discovered OttoStory. The platform empowered me to capture my life's journey without spending thousands. It's such a joy to see my story beautifully bound. OttoStory truly made my dream a reality.`,
            name: 'John Doe',
            photo: replyImg2,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `Crafting my own autobiography always seemed like a dream, something unattainable and costly. But then I discovered OttoStory. The platform empowered me to capture my life's journey without spending thousands. It's such a joy to see my story beautifully bound. OttoStory truly made my dream a reality.`,
            name: 'John Doe',
            photo: replyImg3,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `Crafting my own autobiography always seemed like a dream, something unattainable and costly. But then I discovered OttoStory. The platform empowered me to capture my life's journey without spending thousands. It's such a joy to see my story beautifully bound. OttoStory truly made my dream a reality.`,
            name: 'John Doe',
            photo: replyImg4,
            key: Math.random().toString(36).slice(2, 7),
        },
    ]

    function shrink(node, params = { delay: 0, duration: 400 }) {
        const w = parseInt(getComputedStyle(node).width)

        return {
            ...params,
            css: (t) => {
                carousel.scrollLeft = 0
                return `width: ${w * t}px`
            },
        }
    }

    function change(back: boolean = false) {
        const element = back ? elements.pop() : elements.shift()
        back ? elements.unshift(element) : elements.push(element)
        let transition = back ? 0 : elements.length - 1
        elements[transition].key = Math.random().toString(36).slice(2, 7)
        elements = elements
    }
</script>

<section class="flex items-center justify-center bg-base-200 py-16">
    <div class="container mx-auto flex flex-col gap-16 p-4">
        <h2 class="text-2xl text-primary sm:text-4xl md:text-5xl xl:text-7xl">
            What <span class="italic">Customers</span>
            Are <span class="italic">Saying.</span>
        </h2>
        <div class="grid items-stretch justify-stretch gap-8 lg:grid-cols-2">
            <div class="card relative bg-neutral">
                <Fa
                    icon={faQuoteLeft}
                    class="absolute -top-12 left-16 text-8xl text-base-300"
                />
                <div class="card-body gap-4 pt-12">
                    {#key elements[0].key}
                        <p class="md:text-md lg:text-lg" in:blur>
                            “{elements[0].text}”
                        </p>
                        <p
                            class="font-serif text-3xl italic text-primary"
                            in:blur
                        >
                            {elements[0].name}
                        </p>
                    {/key}
                    <div class="card-actions gap-4">
                        <button
                            type="button"
                            class="btn btn-circle btn-sm"
                            on:click={() => change(true)}
                        >
                            <Fa icon={faCaretLeft} />
                        </button>
                        <button
                            type="button"
                            class="btn btn-circle btn-sm"
                            on:click={() => change()}
                        >
                            <Fa icon={faCaretRight} />
                        </button>
                    </div>
                </div>
            </div>
            <div
                class="carousel carousel-center rounded-box hidden space-x-4 overflow-hidden lg:inline-flex"
                bind:this={carousel}
            >
                {#each elements as element (element.key)}
                    <div class="carousel-item" transition:shrink>
                        <img
                            src={element.photo}
                            class="h-96 w-96 object-cover lg:h-[30rem] lg:w-[30rem]"
                            alt={element.name}
                        />
                    </div>
                {/each}
            </div>
        </div>
    </div>
</section>
