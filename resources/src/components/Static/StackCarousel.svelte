<script lang="ts">
    import { fly } from 'svelte/transition'
    import person1 from '@/assets/img/stack-person1.jpg'
    import person2 from '@/assets/img/stack-person2.jpg'
    import person3 from '@/assets/img/stack-person3.jpg'
    import person4 from '@/assets/img/stack-person4.jpg'
    import bg from '@/assets/img/stack-bg.svg'

    let elements = [
        {
            index: 0,
            image: person1,
            class: 'slide-card slide-1',
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            index: 1,
            image: person2,
            class: 'slide-card slide-2',
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            index: 2,
            image: person3,
            class: 'slide-card slide-3',
            key: Math.random().toString(36).slice(2, 7),
        },
        {
            index: 3,
            image: person4,
            class: 'slide-card slide-4',
            key: Math.random().toString(36).slice(2, 7),
        },
    ]

    function change(back: boolean = false, update: boolean = true) {
        const element = back ? elements.pop() : elements.shift()
        back ? elements.unshift(element) : elements.push(element)
        let transition = back ? 0 : elements.length - 1
        elements[transition].key = Math.random().toString(36).slice(2, 7)

        if (update) {
            elements = elements
        }
    }

    function setActive(index: number) {
        const back = elements[0].index - index > 0 && elements[0].index - index != 3

        while (elements[0].index !== index) {
            change(back, false)
        }
        elements = elements
    }
</script>

<section class="firstSlider">
    <div class="container">
        <div class="wrap">
            <!-- Content -->
            <div class="cardContent card">
                <h5 class="fz_h2 card-title mb-12 block font-normal text-primary">
                    You've shown the world chapters of your life, <i>now give them the complete book.</i>
                </h5>
                <div class="box-content max-w-[32rem] xl:pl-28">
                    <p>
                        Everyone has a unique story, and at OttoStory, we turn it into an extraordinary autobiography.
                        Meet Otto, your AI biographer, guiding you through a reflective journey of your life's
                        experiences.
                    </p>
                    <p>
                        Say goodbye to professional writers and high costs. Otto helps transform your cherished memories
                        into a captivating memoir, allowing you to share your journey or simply reflect on your life.
                        With OttoStory, your story is immortalized in a personal hardback book.
                    </p>
                    <p>Begin with us today and watch your life's narrative come alive in print. Welcome to OttoStory</p>
                </div>
            </div>

            <!-- Slider -->
            <div class="cardSlider">
                <div class="cardBody">
                    <img class="cardBody-illustration" src={bg} alt="illustration" />
                    <div class="slBLock flex">
                        {#each [...elements].reverse() as element (element.key)}
                            <img
                                out:fly={{ y: 100, duration: 250 }}
                                in:fly={{ y: 100, duration: 250, delay: 250 }}
                                src={element.image}
                                class="mask absolute rounded-xl object-cover shadow-lg drop-shadow-xl {element.class}"
                                alt=""
                            />
                        {/each}
                    </div>

                    <div class="slider-dots z-10 flex w-full justify-center pt-16">
                        {#each [...elements].sort((a, b) => (a.index > b.index ? 1 : -1)) as { index } (index)}
                            <button
                                on:click|preventDefault={() => setActive(index)}
                                type="button"
                                class="dot"
                                class:btn-primary={index === elements[0].index}
                            />
                        {/each}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style lang="scss">
    .firstSlider {
        overflow: hidden;
        padding: 220px 0 240px;

        .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cardContent {
            width: 100%;
            max-width: 624px;
            margin-right: 15px;
        }
        .box-content {
            p {
                color: #474747;
                margin-bottom: 30px;
                font-size: 1.125rem;
                line-height: 1.6;
            }
        }

        .slider-dots {
            .dot {
                border: none;
                margin: 0 6px;
                width: 12px;
                height: 12px;
                border-radius: 100%;
                background: #fff;
                cursor: pointer;

                &:hover {
                    opacity: 0.6;
                }
            }

            .btn-primary {
                background-color: #124d87;
                cursor: default;
                &:hover {
                    opacity: 1;
                }
            }
        }

        .cardSlider {
            top: 50px;
            position: relative;

            .cardBody {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                // padding: 128px 64px 32px;

                &-illustration {
                    position: absolute;
                    right: -42%;
                    top: -24%;
                    min-width: 156%;
                }
            }
        }
        .slBLock {
            width: 420px;
            max-width: 420px;
            aspect-ratio: 420/484;
            position: relative;

            .slide-1 {
                transform: rotate(-3deg);
            }
            .slide-2 {
                transform: rotate(6deg);
                top: -48px;
            }
            .slide-3 {
                transform: rotate(10deg);
                left: 24px;
                bottom: -21px;
            }
            .slide-4 {
                transform: rotate(-1deg);
                left: 40px;
                bottom: -30px;
            }
        }
        .slide-card {
            width: 100%;
            height: 100%;
        }
    }
    @media (max-width: 1280px) {
        .firstSlider {
            padding: 144px 0 240px;
            .card-body {
                padding: 64px 62px 16px;
            }
        }
    }
    @media (max-width: 991px) {
        .firstSlider {
            padding: 100px 0 240px;
            .wrap {
                flex-direction: column;
                align-items: flex-start;
            }
            .cardContent {
                width: 100%;
                max-width: 100%;
                margin-right: 0;
            }
            .box-content {
                max-width: 100%;
            }

            .cardSlider {
                top: 0;
                margin: 150px auto 0 auto;
                max-width: 400px;
                .cardBody-illustration {
                    width: 400px;
                }
            }
        }
    }
    @media (max-width: 767px) {
        .firstSlider {
            padding-bottom: 200px;
            .slBLock {
                width: 100%;
                min-width: 300px;
                left: -15px;
            }
            .cardSlider {
                .cardBody {
                    width: 100%;
                }
            }
        }
    }
</style>
