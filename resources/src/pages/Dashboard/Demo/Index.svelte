<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Focus from '@/components/Layouts/Focus.svelte'
    import { strRandom } from '@/service/helpers'
    export const layout = [Base, Focus]
</script>

<script lang="ts">
    import { router } from '@inertiajs/svelte'
    import { fade } from 'svelte/transition'

    export let questions: { data: App.TimelineQuestion[] }

    function submit(question: number) {
        router.post(window.location.pathname, { question })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Start your story</title>
</svelte:head>

<div class="z-10 flex flex-1 flex-col items-center gap-8" in:fade>
    <h1 class="text-center text-4xl text-primary md:text-5xl">
        Let's answer <span class="italic">one of the three questions</span>
    </h1>

    <div class="grid flex-1 grid-cols-1 gap-4 p-16 md:grid-cols-3">
        {#each questions.data as question}
            <div class="card bg-neutral shadow">
                <div class="card-body gap-4">
                    <figure class="rounded-xl">
                        <img src={question.cover ?? `/random-image?key=${question.id}`} alt="" />
                    </figure>
                    <p class="font-serif text-2xl">
                        {question.context} <i>{question.question}</i>
                    </p>
                    <form class="card-actions" on:submit|preventDefault={() => submit(question.id)}>
                        <button type="submit" class="btn btn-primary btn-outline btn-lg rounded-full">
                            Record Your Answer
                        </button>
                    </form>
                </div>
            </div>
        {/each}
    </div>
</div>
