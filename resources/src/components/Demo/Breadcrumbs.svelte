<script lang="ts">
    import { faKeyboard, faThumbsUp } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'
    import Otto from '../SVG/otto.svg.svelte'

    export let step: number = 0
</script>

<header class="container m-4 mx-auto hidden grid-cols-1 gap-8 md:grid md:grid-cols-3">
    <div class="step-breadcrumb" class:inactive={step < 2}>
        <span class="step-icon">
            <Fa icon={faKeyboard} />
        </span>
        Tell your story
    </div>
    <div class="step-breadcrumb" class:inactive={step < 3}>
        <span class="step-icon">
            <Otto class="w-4 leading-none" />
        </span>
        Collaborate with OTTO
    </div>
    <div class="step-breadcrumb" class:inactive={step < 4}>
        <span class="step-icon">
            <Fa icon={faThumbsUp} />
        </span>
        Save
    </div>
</header>
