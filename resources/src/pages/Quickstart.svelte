<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Background from '@/components/Quickstart/Background.svelte';
    export const layout = [Base, Background];
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import NameScreen from '@/components/Quickstart/NameScreen.svelte';
    import MotivationScreen from '@/components/Quickstart/MotivationScreen.svelte';
    import WritingStyleScreen from '@/components/Quickstart/WritingStyleScreen.svelte';
    import GoalScreen from '@/components/Quickstart/GoalScreen.svelte';
    import TimelineScreen from '@/components/Quickstart/TimelineScreen.svelte';
    import ParchasePlanScreen from '@/components/Quickstart/ParchasePlanScreen.svelte';

    export let details;

    const form = useForm({
        first_name: details.first_name as string,
        last_name: details.last_name as string,
        birth_date: details.birth_date ? new Date(details.birth_date) : null,
        motivation: details.motivation as string,
        writing_style: details.writing_style as string,
        goals: details.goals as string,
        timeline: details.timeline as string,
    })

    let step = 0;
    const steps = [
        NameScreen,
        MotivationScreen,
        WritingStyleScreen,
        GoalScreen,
        TimelineScreen,
        ParchasePlanScreen,
    ]

    $: current = steps[step];

    function nextStep() {
        step++;
    }
</script>

<div class="flex flex-col h-full w-full items-center justify-center">
    <div class="flex-1">
        <svelte:component this={current} on:next={nextStep} {form} />
    </div>

    <ul class="steps">
        {#each Array(6) as _, index (index)}
            <li class="step {index <= step ? 'step-primary' : ''}"/>
        {/each}
    </ul>
</div>

