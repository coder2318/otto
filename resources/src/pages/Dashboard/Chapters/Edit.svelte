<script context="module" lang="ts">
    import Base from '@/components/Layouts/Base.svelte'
    import Dashboard from '@/components/Layouts/Dashboard.svelte'
    export const layout = [Base, Dashboard]
</script>

<script lang="ts">
    import Fa from 'svelte-fa'
    import Otto from '@/components/SVG/otto.svg.svelte'
    import {
        faPencil,
        faKeyboard,
        faThumbsUp,
    } from '@fortawesome/free-solid-svg-icons'
</script>

<header
    class="container mx-auto grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4"
>
    <div class="rounded-none border-b-4 border-b-primary py-4 text-left">
        <span class="badge mask badge-secondary mask-circle rounded-full p-4">
            <Fa icon={faPencil} />
        </span>
        Edit your question
    </div>
    <div
        class="hidden rounded-none border-b-4 border-b-primary/20 py-4 text-left md:block"
    >
        <span class="badge mask badge-neutral mask-circle rounded-full p-4">
            <Fa icon={faKeyboard} />
        </span>
        Tell your story
    </div>
    <div
        class="hidden rounded-none border-b-4 border-b-primary/20 py-4 text-left md:block"
    >
        <span class="badge mask badge-neutral mask-circle rounded-full p-4">
            <Otto class="w-4 leading-none" />
        </span>
        Colaborate with OTTO
    </div>
    <div
        class="hidden rounded-none border-b-4 border-b-primary/20 py-4 text-left md:block"
    >
        <span class="badge mask badge-neutral mask-circle rounded-full p-4">
            <Fa icon={faThumbsUp} />
        </span>
        Save
    </div>
</header>
