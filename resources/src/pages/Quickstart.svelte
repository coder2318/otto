<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte';
    import Background from '@/components/Quickstart/Background.svelte';
    export const layout = [Base, Background];
</script>

<script lang="ts">
    let step = 0;
</script>

<div class="flex flex-col h-full w-full items-center justify-center">
    <div class="flex-1">
        123123
    </div>

    <ul class="steps">
        {#each Array(6) as _, index (index)}
            <li class="step {index <= step ? 'step-primary' : ''}"/>
        {/each}
    </ul>
</div>

