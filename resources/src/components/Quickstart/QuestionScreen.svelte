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

<div class="flex flex-col h-full w-full items-center justify-center" in:fade>
    <div class="flex-1 flex flex-col items-center justify-center">
        <h1 class="text-2xl md:text-4xl lg:text-6xl text-primary text-center">
            {question.question}
        </h1>
        <div class="flex gap-4 mt-8">
            {#each question.answers as answer (answer)}
                <button
                    on:click={() => toggleAnswer(answer)}
                    class="btn btn-primary btn-outline w-40 rounded-full {answer ===
                    $form.quiz?.[question.id]
                        ? 'btn-active'
                        : ''}">{answer}</button
                >
            {/each}
        </div>
    </div>
    <div class="flex mb-4 items-center justify-center gap-4">
        <button class="btn btn-primary w-40" on:click={() => dispatch('back')}
            >Back</button
        >
        <button class="btn btn-primary w-40" on:click={next}>Continue</button>
    </div>
</div>
