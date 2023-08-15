<script lang="ts">
    import img from '@/assets/img/img-1.jpg'
</script>

<section
    class="container mx-auto mb-36 mt-48 grid gap-20 max-lg:mb-16 max-lg:mt-24 max-md:p-4 max-sm:mb-8 max-sm:mt-12 lg:grid-cols-2"
>
    <img
        src={img}
        alt="Your Story Starts Here"
        class="pt-14 max-lg:mx-auto max-lg:pt-0"
    />

    <div>
        <div
            class="relative mb-7 flex w-fit items-center rounded-full bg-white py-2 pl-3 pr-4 text-lg max-sm:text-base"
        >
            <div class="absolute left-[-64px] top-[-48px] z-[-1]">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="269"
                    height="234"
                    fill="none"
                >
                    <path
                        fill="#EAE4DC"
                        fill-rule="evenodd"
                        d="M119.76 3.292c32.023 4.223 55.418 25.545 78.554 48.085 29.933 29.162 77.279 55.394 69.877 96.523-7.478 41.549-60.069 52.623-99.455 67.821-33.831 13.054-70.864 27.263-102.89 10.255-31.053-16.491-36.3-55.341-45.77-89.202-10.452-37.379-33.222-79.123-9.998-110.22C33.606-4.953 80.777-1.85 119.76 3.291Z"
                        clip-rule="evenodd"
                    />
                </svg>
            </div>

            <span
                class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-[#F7A392] text-sm text-white max-sm:h-6 max-sm:w-6 max-sm:text-xs"
            >
                1
            </span>

            Kick-starting Your Story
        </div>

        <h2
            class="mb-7 text-6xl tracking-[-1.2px] text-primary max-sm:mb-3 max-sm:text-3xl"
        >
            Your Story

            <span class="text-[64px] italic tracking-[-1.28px] max-sm:text-3xl">
                Starts Here
            </span>
        </h2>

        <div class="pl-28 text-lg max-sm:pl-7 max-sm:text-base">
            <p class="mb-7 max-sm:mb-5">
                Begin your storytelling by diving into a random chapter
                presented by Otto, navigate through your life's timeline divided
                into distinct periods like childhood, adolescence, and beyond,
                or revise your previous drafts.
            </p>

            <p class="mb-7 max-sm:mb-5">
                Each question you encounter has been meticulously designed by
                experienced psychologists, ensuring meaningful and reflective
                storytelling. Your journey through the past, present, and future
                is just a story-start away.
            </p>

            <ul>
                <li class="flex border-b border-white pb-4">
                    <span class="mr-4">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="30"
                            height="31"
                            fill="none"
                        >
                            <rect
                                width="30"
                                height="30"
                                y=".035"
                                fill="#172F50"
                                rx="15"
                            />
                            <path
                                fill="#FFD27B"
                                d="M20.317 10.077c-3.419.977-6.095 4.216-7.934 7.284l-2.046-2.739c-.724-.97-2.388-.026-1.654.957l2.985 3.997c.401.536 1.286.684 1.655 0a25.122 25.122 0 0 1 2.208-3.362c1.353-1.759 3.08-3.675 5.295-4.308 1.183-.338.68-2.169-.51-1.829Z"
                            />
                        </svg>
                    </span>

                    Carefully curated questions and prompts
                </li>

                <li class="flex border-b border-white pb-4 pt-3.5">
                    <span class="mr-4">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="30"
                            height="31"
                            fill="none"
                        >
                            <rect
                                width="30"
                                height="30"
                                y=".035"
                                fill="#172F50"
                                rx="15"
                            />
                            <path
                                fill="#FFD27B"
                                d="M20.317 10.077c-3.419.977-6.095 4.216-7.934 7.284l-2.046-2.739c-.724-.97-2.388-.026-1.654.957l2.985 3.997c.401.536 1.286.684 1.655 0a25.122 25.122 0 0 1 2.208-3.362c1.353-1.759 3.08-3.675 5.295-4.308 1.183-.338.68-2.169-.51-1.829Z"
                            />
                        </svg>
                    </span>

                    Organize your story by key lifetime events or milestones
                </li>

                <li class="flex pt-3.5">
                    <span class="mr-4">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="30"
                            height="31"
                            fill="none"
                        >
                            <rect
                                width="30"
                                height="30"
                                y=".035"
                                fill="#172F50"
                                rx="15"
                            />
                            <path
                                fill="#FFD27B"
                                d="M20.317 10.077c-3.419.977-6.095 4.216-7.934 7.284l-2.046-2.739c-.724-.97-2.388-.026-1.654.957l2.985 3.997c.401.536 1.286.684 1.655 0a25.122 25.122 0 0 1 2.208-3.362c1.353-1.759 3.08-3.675 5.295-4.308 1.183-.338.68-2.169-.51-1.829Z"
                            />
                        </svg>
                    </span>

                    Timeline structure
                </li>
            </ul>
        </div>
    </div>
</section>
