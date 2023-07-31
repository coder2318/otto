<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Focus from '@/components/Layouts/Focus.svelte';
    import NameScreen from '@/components/Quickstart/NameScreen.svelte';
    import OpenQuestionScreen from '@/components/Quickstart/OpenQuestionScreen.svelte';
    import QuestionScreen from '@/components/Quickstart/QuestionScreen.svelte';
    import { range } from '@/service';
    export const layout = [Base, Focus];
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';

    export let details;
    export let questions;

    const form = useForm({
        first_name: details?.first_name as string,
        last_name: details?.last_name as string,
        birth_date: details?.birth_date as string,
        answers: details?.answers ?? {},
    })

    let step = 0;
    $: question = questions[step - 1]?.data;
    $: screen = step == 0 ? NameScreen : (question?.answers ? QuestionScreen : OpenQuestionScreen);
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Welcome</title>
</svelte:head>

<div class="z-10 flex flex-col flex-1 items-center">
    {#key step}
        <svelte:component this={screen}
            on:back={() => step = step - 1}
            on:next={() => step = step + 1}
            {form} {question}
        >
            <ul class="steps">
                {#each range(0, questions.data.length + 1) as index (index)}
                    <li class="step {index <= step ? 'step-primary' : ''}" />
                {/each}
            </ul>
        </svelte:component>
    {/key}
</div>


