<script lang="ts">
    import { flash } from '@/components/Toast.svelte'
    import { fade } from 'svelte/transition'
    import { createEventDispatcher } from 'svelte'
    const dispatch = createEventDispatcher()
    export let form
    export let question

    function next() {
        if ($form.quiz?.[question.id]) {
            return dispatch('next')
        }

        flash({
            message: 'Please choose an answer first!',
            type: 'alert-warning',
            autohide: true,
        })
    }

    function toggleAnswer(answer) {
        if ($form.quiz?.[question.id] === answer) {
            return ($form.quiz[question.id] = null)
        }

        $form.quiz[question.id] = answer
    }
</script>

<div class="flex h-full w-full flex-col items-center justify-center" in:fade>
    <div class="flex flex-1 flex-col items-center justify-center">
        <h1 class="text-center text-2xl text-primary md:text-4xl lg:text-6xl">
            {question.question}
        </h1>
        <div class="mt-8 flex gap-4">
            {#each question.answers as answer (answer)}
                <button
                    on:click={() => toggleAnswer(answer)}
                    class="btn btn-primary btn-outline btn-lg w-40 rounded-full {answer ===
                    $form.quiz?.[question.id]
                        ? 'btn-active'
                        : ''}">{answer}</button
                >
            {/each}
        </div>
    </div>
    <div class="mb-4 flex items-center justify-center gap-4">
        <button class="btn btn-primary w-40" on:click={() => dispatch('back')}
            >Back</button
        >
        <button class="btn btn-primary w-40" on:click={next}>Continue</button>
    </div>
</div>
