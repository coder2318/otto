<script lang="ts">
    import { blur } from 'svelte/transition'
    import replyImg1 from '@/assets/img/reply-1.jpg'
    import replyImg2 from '@/assets/img/reply-2.jpg'
    import replyImg3 from '@/assets/img/reply-3.jpg'
    import replyImg4 from '@/assets/img/reply-4.jpg'
    import Fa from 'svelte-fa'
    import {
        faCaretLeft,
        faCaretRight,
        faQuoteLeft,
    } from '@fortawesome/free-solid-svg-icons'

    let carousel: HTMLElement

    export let elements = [
        {
            text: `OttoStory has given me the most precious gift I could ask for. The ease of recording my life story, having it transcribed flawlessly, and then seeing it beautifully wrapped in a professionally designed book is truly amazing. The attention to detail and the care they put into preserving my memories is evident in every page. I am immensely grateful for this service!`,
            name: 'Frances Klein',
            photo: replyImg1,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `I am in awe of OttoStory and the incredible service they provide. As someone who wanted to share my life story but didn't know where to start, their platform made the process seamless. Recording my experiences felt like having a heartfelt conversation, and the final book is a masterpiece. The transcription accuracy and the elegant book design exceeded my expectations. I highly recommend OttoStory to anyone who wants to leave a lasting legacy.`,
            name: 'Marco Ortiz',
            photo: replyImg2,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `Turning my life story into a professionally crafted book through OttoStory was an exceptional experience. The platform's recording feature made it easy for me to speak my heart out, and the accuracy with which it was transcribed was impressive. The final book presentation is beyond beautiful – it's a treasure that my family and I will cherish forever. OttoStory has made preserving memories and sharing wisdom a true pleasure.`,
            name: 'Danna Weiss',
            photo: replyImg3,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `OttoStory has made me feel like a bestselling author of my own life journey! The convenience of recording my stories and then watching them transform into a polished book is unmatched. The quality of the transcription and the thoughtful design of the book truly captured the essence of my experiences. This service has not only helped me share my story but has also become a legacy that my children and grandchildren will treasure. Kudos to the OttoStory team!`,
            name: 'Lexie Nguyen',
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
