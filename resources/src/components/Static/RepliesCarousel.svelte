<script lang="ts">
    import { blur } from 'svelte/transition'
    import replyImg1 from '@/assets/img/reply-1.jpg'
    import replyImg2 from '@/assets/img/reply-2.jpg'
    import replyImg3 from '@/assets/img/reply-3.jpg'
    import replyImg4 from '@/assets/img/reply-4.jpg'
    import iconQuote from '@/assets/img/quote-icon.svg'
    import arrowLeft from '@/assets/img/arrow-left.svg'
    import arrowRight from '@/assets/img/arrow-right.svg'

    let carousel: HTMLElement

    export let elements = [
        {
            text: `OttoStory has given me the most precious gift I could ask for. The ease of recording my life story, having it transcribed flawlessly, and then seeing it beautifully wrapped in a professionally designed book is truly amazing. The attention to detail and the care they put into preserving my memories is evident in every page. I am immensely grateful for this service!`,
            name: 'Verified 2023 OttoStory Customer',
            photo: replyImg1,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `I am in awe of OttoStory and the incredible service they provide. As someone who wanted to share my life story but didn't know where to start, their platform made the process seamless. Recording my experiences felt like having a heartfelt conversation, and the final book is a masterpiece. The transcription accuracy and the elegant book design exceeded my expectations. I highly recommend OttoStory to anyone who wants to leave a lasting legacy.`,
            name: 'Verified 2023 OttoStory Customer',
            photo: replyImg2,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `Turning my life story into a professionally crafted book through OttoStory was an exceptional experience. The platform's recording feature made it easy for me to speak my heart out, and the accuracy with which it was transcribed was impressive. The final book presentation is beyond beautiful – it's a treasure that my family and I will cherish forever. OttoStory has made preserving memories and sharing wisdom a true pleasure.`,
            name: 'Verified 2023 OttoStory Customer',
            photo: replyImg3,
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            text: `OttoStory has made me feel like a bestselling author of my own life journey! The convenience of recording my stories and then watching them transform into a polished book is unmatched. The quality of the transcription and the thoughtful design of the book truly captured the essence of my experiences. This service has not only helped me share my story but has also become a legacy that my children and grandchildren will treasure. Kudos to the OttoStory team!`,
            name: 'Verified 2023 OttoStory Customer',
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

<section class=" repliesCarousel relative flex items-center justify-center overflow-hidden pb-36 pt-32">
    <div class="backGround rounded-3xl bg-base-200"></div>
    <div class="otto-container z-10 mx-auto flex flex-col">
        <h2 class="fz_h1 mb-20 text-primary">
            What <span class="italic">Customers</span>
            Are <span class="italic">Saying.</span>
        </h2>

        <div class="wrap flex items-stretch justify-stretch">
            <!-- Quote -->
            <div class="card relative block bg-neutral">
                <img class="iconQuote" src={iconQuote} alt="iconQuote" />
                <div class="flex h-full flex-col justify-between">
                    <div class="cardContent">
                        <div class="cardContent__image">
                            <img src={elements[0].photo} alt={elements[0].name} />
                        </div>
                        {#key elements[0].key}
                            <p class="desc" in:blur>
                                “{elements[0].text}”
                            </p>
                            <p class="name font-serif" in:blur>
                                {elements[0].name}
                            </p>
                        {/key}
                    </div>
                    <div class="slide__nav">
                        <button type="button" class="slide__nav-item" on:click={() => change(true)}>
                            <img src={arrowLeft} alt="arrow" />
                        </button>
                        <button type="button" class="slide__nav-item" on:click={() => change()}>
                            <img src={arrowRight} alt="arrow" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Slider -->
            <div
                class="carousel carousel-center rounded-box space-x-4 overflow-visible lg:inline-flex"
                bind:this={carousel}
            >
                {#each elements as element (element.key)}
                    <div class="carousel-item overflow-hidden rounded-xl" transition:shrink>
                        <img
                            src={element.photo}
                            class="h-96 w-96 object-cover lg:h-[600px] lg:w-[600px]"
                            alt={element.name}
                        />
                    </div>
                {/each}
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .repliesCarousel {
        .backGround {
            position: absolute;
            width: 100%;
            max-width: calc(100% - 40px);
            height: 100%;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
        }
        .block {
            min-width: 640px;
            max-width: 640px;
            padding: 100px 80px 60px;
            margin-right: 24px;
        }
        .iconQuote {
            position: absolute;
            left: 80px;
            top: -30px;
        }
        .cardContent {
            margin-bottom: 20px;

            &__image {
                width: 100%;
                height: auto;
                aspect-ratio: 16/14;
                position: relative;
                overflow: hidden;
                margin-bottom: 15px;
                display: none;
                border-radius: 16px;
                transition: 0.5s;

                img {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    object-position: center;
                }
            }
            .name {
                font-size: 1.5rem;
                color: #0c345c;
            }
            .desc {
                font-size: 1.25rem;
                color: #474747;
                line-height: 1.6;
                margin-bottom: 20px;
            }
        }
        .slide__nav {
            display: flex;
            align-items: center;

            &-item {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 32px;
                height: 32px;
                border-radius: 100%;
                background: #f1ede7;
                margin-right: 12px;
                transition: 0.3s;

                &:hover {
                    background: #e3dfda;
                }

                &:last-child {
                    margin-right: 0;
                }
            }
        }
    }
    @media (max-width: 1280px) {
        .repliesCarousel {
            .backGround {
                max-width: none;
                border-radius: 0;
            }
            .block {
                min-width: 50%;
                max-width: 50%;
                padding: 80px 40px 40px;
            }
            .carousel-item {
                width: 100%;
            }
        }
    }
    @media (max-width: 991px) {
        .repliesCarousel {
            padding: 100px 0;
            .iconQuote {
                left: 50%;
                transform: translateX(-50%);
                width: 60px;
                top: -21px;
            }
            .wrap {
                flex-direction: column;
            }
            .block {
                min-width: 100%;
                max-width: 100%;
                margin-right: 0;
                margin-bottom: 0;
                padding: 50px 20px 30px 20px;
            }
            .cardContent {
                margin-bottom: 30px;
                &__image {
                    display: block;
                }
                p {
                    filter: none !important;
                    transition: 0s !important;
                }
            }
            .carousel {
                display: none;
            }
        }
    }
</style>
