<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Focus from '@/components/Layouts/Focus.svelte'
    export const layout = [Base, Focus]
</script>

<script lang="ts">
    import { onMount } from 'svelte'
    import { useForm } from '@inertiajs/svelte'
    import NameScreen from '@/components/Quickstart/NameScreen.svelte'
    import OpenQuestionScreen from '@/components/Quickstart/OpenQuestionScreen.svelte'
    import QuestionScreen from '@/components/Quickstart/QuestionScreen.svelte'
    import { range } from '@/service/helpers'
    import { dayjs } from '@/service/dayjs'

    export let details
    export let questions

    const form = useForm({
        first_name: details?.first_name as string,
        last_name: details?.last_name as string,
        birth_date: details?.birth_date ? dayjs(details?.birth_date).format('DD/MM/YYYY') : (null as string),
        quiz: details?.quiz ?? {},
    })

    let step = 0
    $: question = questions?.data?.[step - 1]
    $: Question = question?.answers ? QuestionScreen : OpenQuestionScreen
    $: Screen = step == 0 ? NameScreen : Question

    onMount(() => {
        let quickstart: string | any | null = localStorage.getItem('quickstart')

        if (!quickstart) {
            return
        }

        quickstart = quickstart ? JSON.parse(quickstart) : null

        step = quickstart?.step ?? step
        $form.first_name = quickstart?.form?.first_name ?? $form.first_name ?? 'User'
        $form.last_name = quickstart?.form?.last_name ?? $form.last_name ?? ''
        $form.birth_date = quickstart?.form?.birth_date ?? $form.birth_date
        $form.quiz = quickstart?.form?.quiz ?? $form.answers

        if (step >= questions.data.length + 1) {
            step = questions.data.length
        }
    })

    function move(to: number) {
        if (to >= questions.data.length + 1) {
            localStorage.removeItem('quickstart')

            return $form.post(window.location.href)
        }

        step = to
        localStorage.quickstart = JSON.stringify({ step, form: $form })
    }
</script>

<svelte:head>
    <title>{import.meta.env.VITE_APP_NAME} - Welcome</title>
</svelte:head>

<div class="z-10 flex flex-1 flex-col items-center">
    {#key step}
        <svelte:component
            this={Screen}
            on:back={() => move(step - 1)}
            on:next={() => move(step + 1)}
            {form}
            {question}
        />
    {/key}

    <ul class="steps">
        {#each range(0, questions.data.length + 1) as index (index)}
            <li class="step {index <= step ? 'step-primary' : ''}" />
        {/each}
    </ul>
</div>

<style lang="scss">
    .steps {
        .step {
            min-width: 40px;
            &:after {
                content: '';
                width: 10px;
                height: 10px;
            }
            &:before {
                height: 2px;
            }
        }
    }
</style>
