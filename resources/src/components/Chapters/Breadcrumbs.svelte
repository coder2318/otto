<script lang="ts">
    import { faKeyboard, faPencil, faThumbsUp } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'
    import Otto from '../SVG/otto.svg.svelte'

    export let step: number = 0
    export let guest: boolean = false
</script>

<header
    class="container z-10 m-4 mx-auto hidden grid-cols-1 gap-8 md:grid md:grid-cols-2 {guest
        ? 'md:grid-cols-3'
        : 'lg:grid-cols-4'}"
>
    <div class="step-breadcrumb" class:inactive={step < 1}>
        <span class="step-icon">
            <Fa icon={faPencil} />
        </span>
        Edit your question
    </div>
    <div class="step-breadcrumb" class:inactive={step < 2}>
        <span class="step-icon">
            <Fa icon={faKeyboard} />
        </span>
        Tell your story
    </div>
    {#if !guest}
        <div class="step-breadcrumb" class:inactive={step < 3}>
            <span class="step-icon">
                <Otto class="w-4 leading-none" />
            </span>
            Collaborate with OTTO
        </div>
    {/if}
    <div class="step-breadcrumb" class:inactive={step < 4}>
        <span class="step-icon">
            <Fa icon={faThumbsUp} />
        </span>
        Save
    </div>
</header>
