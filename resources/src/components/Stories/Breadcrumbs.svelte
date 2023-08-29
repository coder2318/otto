<script lang="ts">
    import { inertia } from '@inertiajs/svelte'
    import {
        faBook,
        faCreditCard,
        faList,
    } from '@fortawesome/free-solid-svg-icons'
    import Fa from 'svelte-fa'

    export let step: number = 0
    export let story: { data: App.Story }
</script>

<header class="container m-4 mx-auto grid grid-cols-1 gap-8 md:grid-cols-3">
    <a
        class="step-breadcrumb"
        class:inactive={step < 1}
        href="/stories/{story.data.id}/edit"
        use:inertia
    >
        <span class="step-icon">
            <Fa icon={faList} />
        </span>
        Table of contents
    </a>
    <a
        class="step-breadcrumb"
        class:inactive={step < 2}
        href="/stories/{story.data.id}/cover"
        use:inertia
    >
        <span class="step-icon">
            <Fa icon={faBook} />
        </span>
        Make a book cover
    </a>
    <a
        class="step-breadcrumb"
        class:inactive={step < 3}
        href="/stories/{story.data.id}/order"
        use:inertia
    >
        <span class="step-icon">
            <Fa icon={faCreditCard} />
        </span>
        Order your book
    </a>
</header>
