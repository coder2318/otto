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
</script>

<div class="flex flex-col h-full w-full items-center justify-center" in:fade>
    <div class="flex-1 flex flex-col items-center justify-center gap-8">
        <h1 class="text-2xl md:text-4xl lg:text-6xl text-primary text-center">
            {question.question}
        </h1>
        <input
            type="text"
            class="input input-bordered text-center font-serif w-full input-lg"
            placeholder="Type your answer..."
            name="answer"
            id="answer"
            required
            bind:value={$form.quiz[question.id]}
        />
    </div>
    <div class="flex mb-4 items-center justify-center gap-4">
        <button class="btn btn-primary w-40" on:click={() => dispatch('back')}
            >Back</button
        >
        <button class="btn btn-primary w-40" on:click={next}>Continue</button>
    </div>
</div>
