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

<div class="questions flex h-full w-full flex-col items-center justify-center" in:fade>
    <div class="flex flex-1 flex-col items-center justify-center">
        <h1 class="fz_h2 text-center text-primary">
            {question.question}
        </h1>
        <div class="wrap mt-8 flex gap-4">
            {#each question.answers as answer (answer)}
                <button
                    on:click={() => toggleAnswer(answer)}
                    class="questions-item font-serif {answer === $form.quiz?.[question.id]
                        ? 'questions-item-active'
                        : ''}">{answer}</button
                >
            {/each}
        </div>
        <div class="buttons">
            <button class="otto-btn-outline otto-btn" on:click={() => dispatch('back')}>Back</button>
            <button class="otto-btn-primary otto-btn" on:click={next}>Continue</button>
        </div>
    </div>
</div>

<style lang="scss">
    .questions {
        .wrap {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .buttons {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 36px;
        }
        .otto-btn {
            height: 48px;
            font-size: 16px;
            margin: 6px 6px;
            width: 200px;

            @media (max-width: 767px) {
                width: 130px;
            }
        }

        &-item {
            display: flex;
            align-items: center;
            height: 64px;
            border: 1px solid #0c345c;
            padding: 0 32px;
            border-radius: 40px;
            font-size: 24px;
            line-height: 1;
            color: #333333;
            transition: 0.3s;
            padding-bottom: 3px;
            @media (max-width: 767px) {
                height: 48px;
                padding: 0 24px;
                font-size: 16px;
            }

            &:hover {
                background: #0c345c;
                color: #fff;
            }

            &-active {
                background: #0c345c;
                color: #fff;
            }
        }
    }
</style>
