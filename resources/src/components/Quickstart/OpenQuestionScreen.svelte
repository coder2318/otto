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

<div class="flex h-full w-full flex-col items-center justify-center" in:fade>
    <div class="flex flex-1 flex-col items-center justify-center gap-8">
        <h1 class="text-center text-2xl text-primary md:text-4xl lg:text-6xl">
            {question.question}
        </h1>
        <input
            type="text"
            class="input input-bordered input-lg w-full text-center font-serif"
            placeholder="Type your answer..."
            name="answer"
            id="answer"
            required
            bind:value={$form.quiz[question.id]}
        />
    </div>
    <div class="mb-4 flex items-center justify-center gap-4">
        <button class="btn btn-primary w-40" on:click={() => dispatch('back')}>Back</button>
        <button class="btn btn-primary w-40" on:click={next}>Continue</button>
    </div>
</div>
