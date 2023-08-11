<script lang="ts">
    import {
        faBook,
        faCreditCard,
        faList,
    } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'

    export let step: number = 0
</script>

<header
    class="container m-4 mx-auto hidden grid-cols-1 gap-8 md:grid md:grid-cols-3"
>
    <div class="step-breadcrumb" class:inactive={step < 1}>
        <span class="step-icon">
            <Fa icon={faBook} />
        </span>
        Make a book cover
    </div>
    <div class="step-breadcrumb" class:inactive={step < 2}>
        <span class="step-icon">
            <Fa icon={faList} />
        </span>
        Table of contents
    </div>
    <div class="step-breadcrumb" class:inactive={step < 3}>
        <span class="step-icon">
            <Fa icon={faCreditCard} />
        </span>
        Order your book
    </div>
</header>
