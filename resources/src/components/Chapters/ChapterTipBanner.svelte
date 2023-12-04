<script lang="ts">
    import blueMicrophone from '@/assets/img/blue-microphone.svg'
    import { onMount } from 'svelte'
    export let title: string
    export let questions: string[] = []

    let carousel: HTMLDivElement

    onMount(() => {
        const interval = setInterval(() => {
            if (!carousel) return

            carousel.scrollLeft =
                carousel.scrollLeft >= carousel.scrollWidth - carousel.offsetWidth
                    ? 0
                    : carousel.scrollLeft + carousel.offsetWidth
        }, 10000)

        return () => clearInterval(interval)
    })
</script>

{#if questions.length > 0}
    <section class="relative mb-5 text-primary">
        <div class="otto-container">
            <div class="flex flex-col gap-2 rounded-3xl bg-[#cfe3f3] p-6">
                <div class="flex items-center gap-2">
                    <img src={blueMicrophone} alt="Microphone" />
                    <span class="text-xl font-bold">{title}</span>
                </div>

                <div bind:this={carousel} class="carousel rounded-box w-full">
                    {#each questions as question}
                        <div class="carousel-item w-full">
                            <p class="text-3xl">{question}</p>
                        </div>
                    {/each}
                </div>
            </div>
        </div>
    </section>
{/if}
